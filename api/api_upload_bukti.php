<?php
require_once __DIR__ . '/api_common.php';
// Matikan display error agar tidak merusak JSON
// Untuk debugging sementara, aktifkan reporting agar kita bisa log error detail.
// Setelah masalah diperbaiki, kembalikan ke error_reporting(0) dan hide errors.
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Bersihkan output buffer
ob_start();
// Pastikan kita memuat file koneksi yang benar (relatif ke folder project)
include __DIR__ . '/../config/koneksi.php';

// Bersihkan output buffer jika koneksi.php menambahkan spasi
ob_clean();

header('Content-Type: application/json');

// Simple error log file untuk membantu debugging 500
$logFile = __DIR__ . '/../uploads/upload_errors.log';
function write_debug_log($msg) {
    global $logFile;
    error_log(date('[Y-m-d H:i:s] ') . $msg . PHP_EOL, 3, $logFile);
}

try {
    // 1. Cek Method
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Method Not Allowed', 405);
    }

    // 2. Cek Parameter Wajib
    if (!isset($_POST['user_id']) || !isset($_POST['paket_id']) || !isset($_POST['metode_pembayaran_id'])) {
        throw new Exception('Parameter kurang lengkap (user_id, paket_id, metode_pembayaran_id)', 400);
    }

    // Cast parameter ke tipe yang sesuai untuk mencegah kesalahan bind_param
    $user_id = (int) $_POST['user_id'];
    $paket_id = (int) $_POST['paket_id'];
    $metode_id = (int) $_POST['metode_pembayaran_id'];
    $catatan = isset($_POST['catatan']) ? $_POST['catatan'] : '';

    write_debug_log("Received upload request: user_id={$user_id}, paket_id={$paket_id}, metode_id={$metode_id}");

    // 3. Cek File
    if (!isset($_FILES['bukti_bayar']) || $_FILES['bukti_bayar']['error'] != 0) {
        $errCode = isset($_FILES['bukti_bayar']['error']) ? $_FILES['bukti_bayar']['error'] : 'no_file';
        write_debug_log("File upload error, code: {$errCode}");
        throw new Exception('File bukti bayar tidak valid atau kosong', 400);
    }

    // 4. Siapkan Folder
    $target_dir = "../uploads/bukti_pembayaran/"; 
    if (!file_exists($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            write_debug_log("Gagal membuat folder: {$target_dir}");
            throw new Exception('Gagal membuat folder upload di server', 500);
        }
    }

    $file_ext = strtolower(pathinfo($_FILES["bukti_bayar"]["name"], PATHINFO_EXTENSION));
    $new_name = $user_id . "_" . time() . "." . $file_ext;
    $target_file = $target_dir . $new_name;
    $db_path = "uploads/bukti_pembayaran/" . $new_name;

    // 5. Upload
    if (move_uploaded_file($_FILES["bukti_bayar"]["tmp_name"], $target_file)) {
        write_debug_log("File moved to: {$target_file}");
        
        // 6. Insert Database
        // Gunakan nilai enum yang valid ('Menunggu') untuk kolom status_pembayaran
        $sql = "INSERT INTO pembayaran (user_id, paket_id, metode_pembayaran_id, tanggal_pembayaran, catatan, bukti_pembayaran, status_pembayaran) 
            VALUES (?, ?, ?, NOW(), ?, ?, 'Menunggu')";
                
        $stmt = $koneksi->prepare($sql);
        if (!$stmt) {
            write_debug_log('Prepare failed: ' . $koneksi->error);
            throw new Exception('Query Prepare Error: ' . $koneksi->error, 500);
        }

        // Bind sebagai integer untuk id dan string untuk catatan/path
        if (!$stmt->bind_param("iiiss", $user_id, $paket_id, $metode_id, $catatan, $db_path)) {
            write_debug_log('bind_param failed: ' . $stmt->error);
            throw new Exception('Bind param gagal: ' . $stmt->error, 500);
        }

        if ($stmt->execute()) {
            write_debug_log('DB insert successful, id: ' . $stmt->insert_id);
            echo json_encode(['status' => 'success', 'message' => 'Upload berhasil']);
        } else {
            write_debug_log('Execute failed: ' . $stmt->error);
            throw new Exception('Gagal simpan database: ' . $stmt->error, 500);
        }
        $stmt->close();
        
    } else {
        throw new Exception('Gagal memindahkan file upload. Cek permission folder.', 500);
    }

} catch (Exception $e) {
    $code = $e->getCode() ?: 500;
    write_debug_log('Exception: ' . $e->getMessage() . ' Code: ' . $code);
    http_response_code($code);
    // Kembalikan pesan yang aman untuk client, simpan detail di log
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$koneksi->close();
// Akhiri buffer output
ob_end_flush();
?>