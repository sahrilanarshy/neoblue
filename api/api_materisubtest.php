<?php
require_once __DIR__ . '/api_common.php';
session_start();
header('Content-Type: application/json');

include '../config/koneksi.php';

// --- FUNGSI UNTUK MENGIRIM RESPONSE ---
function send_response($status_code, $status, $message, $data = null) {
    http_response_code($status_code);
    $response = ['status' => $status, 'message' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}

// --- AUTENTIKASI ---
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true || !isset($_SESSION['user_id'])) {
    send_response(401, 'error', 'Akses ditolak. Silakan login terlebih dahulu.');
}

$user_id = $_SESSION['user_id'];
$tipe_user = $_SESSION['tipe_user'] ?? 'Free'; // Ambil tipe user dari session

// Ambil subtest_id dari query parameter, contoh: /api/api_materisubtest.php?subtest_id=1
$subtest_id = isset($_GET['subtest_id']) ? intval($_GET['subtest_id']) : 0;

if ($subtest_id <= 0) {
    send_response(400, 'error', 'ID Subtest tidak valid.');
}

try {
    // 1. Ambil data subtest
    $stmt_subtest = mysqli_prepare($koneksi, "SELECT nama_subtest, singkatan FROM subtest WHERE id = ?");
    mysqli_stmt_bind_param($stmt_subtest, "i", $subtest_id);
    mysqli_stmt_execute($stmt_subtest);
    $result_subtest = mysqli_stmt_get_result($stmt_subtest);
    $subtest = mysqli_fetch_assoc($result_subtest);

    if (!$subtest) {
        send_response(404, 'error', 'Subtest tidak ditemukan.');
    }

    // 2. Ambil semua materi yang terkait dengan subtest ini
    $query_materi = "
        SELECT id, judul, deskripsi, tipe, 
        (CASE 
            WHEN tipe = 'Premium' AND ? = 'Premium' THEN 1
            WHEN tipe = 'Free' THEN 1
            ELSE 0 
        END) as is_accessible
        FROM materi WHERE subtest_id = ? ORDER BY urutan ASC
    ";
    $stmt_materi = mysqli_prepare($koneksi, $query_materi);
    mysqli_stmt_bind_param($stmt_materi, "si", $tipe_user, $subtest_id);
    mysqli_stmt_execute($stmt_materi);
    $result_materi = mysqli_stmt_get_result($stmt_materi);
    $materials = mysqli_fetch_all($result_materi, MYSQLI_ASSOC);

    // 3. Siapkan data untuk output JSON
    $output = [
        'subtest' => $subtest,
        'materi' => $materials
    ];

    send_response(200, 'success', 'Data materi berhasil diambil.', $output);

} catch (Exception $e) {
    send_response(500, 'error', 'Terjadi kesalahan pada server: ' . $e->getMessage());
} finally {
    if (isset($stmt_subtest)) mysqli_stmt_close($stmt_subtest);
    if (isset($stmt_materi)) mysqli_stmt_close($stmt_materi);
    mysqli_close($koneksi);
}
?>