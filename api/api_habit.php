<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
session_start();
include '../config/koneksi.php';

// 1. Validasi Method (Hanya izinkan GET)
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed. Gunakan GET.']);
    exit;
}

// 2. Cek Otentikasi & Role
if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['admin', 'guru','siswa'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak. Anda tidak memiliki izin.']);
    exit;
}

try {
    // 3. Query Data
    $query = "SELECT 
                hh.id, 
                hh.tanggal, 
                s.nama_subtest, 
                hh.judul, 
                hh.jenis 
              FROM habit_harian hh
              JOIN subtest s ON hh.subtest_id = s.id
              ORDER BY hh.tanggal DESC, hh.id DESC";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        // Response Sukses
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Data habit berhasil diambil.',
            'data' => $data
        ]);
    } else {
        throw new Exception(mysqli_error($koneksi));
    }

} catch (Exception $e) {
    // Response Error Server
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
    ]);
}

mysqli_close($koneksi);
?>