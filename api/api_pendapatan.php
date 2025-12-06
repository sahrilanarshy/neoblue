<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
session_start();
include '../config/koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); // Forbidden
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit();
}

try {
    // Query untuk mengambil rekap pendapatan bulanan dari pembayaran yang diterima
    $query = "
        SELECT 
            DATE_FORMAT(p.tanggal_konfirmasi, '%M %Y') as bulan,
            COUNT(p.id) as jumlah_transaksi,
            SUM(pk.harga) as total_pendapatan
        FROM 
            pembayaran p
        JOIN 
            paket pk ON p.paket_id = pk.id
        WHERE 
            p.status_pembayaran = 'Diterima'
        GROUP BY 
            DATE_FORMAT(p.tanggal_konfirmasi, '%M %Y')
        ORDER BY 
            MIN(p.tanggal_konfirmasi) DESC
    ";

    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        throw new Exception("Query gagal: " . mysqli_error($koneksi));
    }

    $pendapatan_bulanan = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $pendapatan_bulanan[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $pendapatan_bulanan
    ]);

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>