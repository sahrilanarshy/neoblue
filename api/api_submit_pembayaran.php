<?php
require_once __DIR__ . '/api_common.php';
// submit pembayaran — integrate bootstrap, keep multipart handling and session auth
include_once __DIR__ . '/bootstrap.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Pastikan user sudah login sebagai siswa
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true || !isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    send_json(401, ['status' => 'error', 'message' => 'Akses ditolak. Anda harus login sebagai siswa.']);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') send_json(405, ['status' => 'error', 'message' => 'Metode request tidak diizinkan.']);

$user_id = $_SESSION['user_id'];
$paket_id = isset($_POST['paket_id']) ? intval($_POST['paket_id']) : 0;
$metode_id = isset($_POST['metode_pembayaran_id']) ? intval($_POST['metode_pembayaran_id']) : 0;
$catatan = $_POST['catatan'] ?? '';
$bukti_path = '';

if ($paket_id <= 0 || $metode_id <= 0 || !isset($_FILES['bukti_pembayaran']) || $_FILES['bukti_pembayaran']['error'] !== UPLOAD_ERR_OK) {
    send_json(400, ['status' => 'error', 'message' => 'Data tidak lengkap atau bukti pembayaran tidak valid.']);
}

$upload_dir = __DIR__ . '/../uploads/bukti_pembayaran/';
if (!is_dir($upload_dir) && !mkdir($upload_dir, 0777, true)) send_json(500, ['status' => 'error', 'message' => 'Gagal membuat direktori upload.']);

$file_info = pathinfo($_FILES["bukti_pembayaran"]["name"]);
$file_extension = strtolower($file_info['extension']);
$allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
if (!in_array($file_extension, $allowed_extensions)) send_json(400, ['status' => 'error', 'message' => 'Format file tidak diizinkan. Hanya JPG, JPEG, PNG, dan PDF.']);

$file_name = $user_id . '_' . time() . '.' . $file_extension;
$target_file = $upload_dir . $file_name;
if (move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], $target_file)) {
    $bukti_path = 'uploads/bukti_pembayaran/' . $file_name;
} else {
    send_json(500, ['status' => 'error', 'message' => 'Gagal mengunggah bukti pembayaran.']);
}

$koneksi->begin_transaction();
try {
    $stmt = $koneksi->prepare("INSERT INTO pembayaran (user_id, paket_id, metode_pembayaran_id, catatan, bukti_pembayaran, status_pembayaran) VALUES (?, ?, ?, ?, ?, 'Menunggu')");
    $stmt->bind_param('iiiss', $user_id, $paket_id, $metode_id, $catatan, $bukti_path);
    if ($stmt->execute()) {
        $koneksi->commit();
        send_json(201, ['status' => 'success', 'message' => 'Konfirmasi pembayaran berhasil dikirim. Mohon tunggu verifikasi dari admin.']);
    } else {
        throw new Exception('Gagal menyimpan data pembayaran: ' . $stmt->error);
    }
    $stmt->close();
} catch (Exception $e) {
    $koneksi->rollback();
    if (!empty($bukti_path) && file_exists(__DIR__ . '/../' . $bukti_path)) unlink(__DIR__ . '/../' . $bukti_path);
    send_json(500, ['status' => 'error', 'message' => $e->getMessage()]);
}

$koneksi->close();
?>