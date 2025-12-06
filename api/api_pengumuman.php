<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
include '../config/koneksi.php';
session_start(); // Mulai sesi untuk memeriksa role jika diperlukan

try {
    // Cek apakah request datang dari admin/guru atau user biasa
    $is_admin_or_guru = isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'guru']);

    if ($is_admin_or_guru && isset($_GET['source']) && $_GET['source'] === 'admin') {
        // Jika dari admin, tampilkan semua data untuk manajemen
        $query = "SELECT id, judul, isi, link, tanggal_terbit, tanggal_selesai, status FROM pengumuman ORDER BY created_at DESC, id DESC";
    } else {
        // Jika dari user, tampilkan hanya yang sudah 'Published' dan waktunya pas
        $query = "SELECT judul, isi, link, tanggal_terbit, tanggal_selesai
                FROM pengumuman 
                WHERE status = 'Published' 
                AND tanggal_terbit <= CURDATE()
                AND (tanggal_selesai IS NULL OR tanggal_selesai >= CURDATE())
                ORDER BY tanggal_terbit DESC, id DESC";
    }

    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        throw new Exception('Query Error: ' . mysqli_error($koneksi));
    }

    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $response = ['status' => 'success', 'data' => $data];
} catch (Exception $e) {
    http_response_code(500);
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);
?>
