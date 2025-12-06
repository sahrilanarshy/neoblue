<?php
require_once __DIR__ . '/api_common.php';
// Default header JSON untuk error handling
header('Content-Type: application/json');
include '../config/koneksi.php';

// 1. Validasi Method (Hanya POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// 2. Menangani Input (JSON atau Form Data biasa)
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$nama = ''; 
$email = ''; 
$subjek = ''; 
$pesan = '';

if (stripos($contentType, 'application/json') !== false) {
    // Jika input berupa Raw JSON
    $body = json_decode(file_get_contents('php://input'), true);
    $nama = $body['name'] ?? '';
    $email = $body['email'] ?? '';
    $subjek = $body['subject'] ?? '';
    $pesan = $body['message'] ?? '';
} else {
    // Jika input berupa Form Data (x-www-form-urlencoded / multipart)
    $nama = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subjek = $_POST['subject'] ?? '';
    $pesan = $_POST['message'] ?? '';
}

// 3. Validasi Data Kosong
if (empty($nama) || empty($email) || empty($subjek) || empty($pesan)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Semua field (nama, email, subject, pesan) wajib diisi.']);
    exit;
}

// 4. Simpan ke Database (Prepared Statement)
try {
    $stmt = $koneksi->prepare("INSERT INTO kontak (nama, email, subjek, pesan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $nama, $email, $subjek, $pesan);

    if ($stmt->execute()) {
        // PENTING: Mengubah header menjadi text/plain khusus untuk sukses
        // agar sesuai dengan "legacy behavior" validate.js frontend kamu.
        header('Content-Type: text/plain');
        http_response_code(200);
        echo 'OK'; 
        exit();
    } else {
        throw new Exception("Gagal menyimpan data.");
    }

} catch (Exception $e) {
    http_response_code(500); // Server Error
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan: ' . $e->getMessage()]);
}

$koneksi->close();
?>