<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
session_start();
include '../config/koneksi.php';

$response = [
    'status' => 'error',
    'message' => 'Akses ditolak atau parameter salah.',
    'data' => null
];

// Validasi Login & Role
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); // Forbidden
    $response['message'] = 'Akses ditolak. Hanya admin.';
    echo json_encode($response);
    exit();
}

// Validasi Method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    $response['message'] = 'Method not allowed';
    echo json_encode($response);
    exit();
}

if (isset($_GET['search'])) {
    $search = "%" . $_GET['search'] . "%";

    // Gunakan Prepared Statement (Lebih Aman)
    $stmt = $koneksi->prepare("SELECT id, nama, email, tipe_user, masa_aktif FROM users WHERE (email LIKE ? OR nama LIKE ?) AND role = 'siswa' LIMIT 1");
    $stmt->bind_param("ss", $search, $search);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $response['status'] = 'success';
            $response['message'] = 'Pengguna ditemukan.';
            $response['data'] = $data;
        } else {
            http_response_code(404);
            $response['status'] = 'not_found';
            $response['message'] = 'Pengguna tidak ditemukan.';
        }
    } else {
        http_response_code(500);
        $response['message'] = 'Database error.';
    }
}

echo json_encode($response);
?>