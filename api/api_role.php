<?php
require_once __DIR__ . '/api_common.php';
session_start();

// Set header untuk response JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Sesuaikan dengan domain Anda di produksi

// Inisialisasi array response
$response = [];

// Cek apakah pengguna sudah login dan memiliki role
if (isset($_SESSION['status_login']) && $_SESSION['status_login'] === true && isset($_SESSION['role'])) {
    // Jika login, kirim data pengguna
    http_response_code(200); // OK
    $response = [
        'status' => 'success',
        'message' => 'Pengguna terautentikasi.',
        'data' => [
            'user_id' => $_SESSION['user_id'],
            'nama' => $_SESSION['nama'],
            'email' => $_SESSION['email'],
            'role' => $_SESSION['role'],
            'tipe_user' => $_SESSION['tipe_user']
        ]
    ];
} else {
    // Jika tidak login, kirim error
    http_response_code(401); // Unauthorized
    $response = [
        'status' => 'error',
        'message' => 'Akses ditolak. Silakan login terlebih dahulu.'
    ];
    // Hapus semua variabel session
    session_unset();
    // Hancurkan session
    session_destroy();
}

// Tampilkan response dalam format JSON
echo json_encode($response);
?>