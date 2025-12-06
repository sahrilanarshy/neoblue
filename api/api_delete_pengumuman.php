<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
include '../config/koneksi.php';
session_start();

try {
    // Pastikan method POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Metode tidak diperbolehkan.']);
        exit;
    }

    // Cek role di session (admin atau guru boleh menghapus)
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'guru'])) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
        exit;
    }

    // Terima peng_id (boleh 'peng_123' atau hanya angka)
    $raw = isset($_POST['peng_id']) ? trim($_POST['peng_id']) : (isset($_POST['id']) ? trim($_POST['id']) : null);
    if (!$raw) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Parameter peng_id tidak ditemukan.']);
        exit;
    }

    // Jika format 'peng_123', ambil angka setelah underscore
    if (preg_match('/^peng_(\d+)$/', $raw, $m)) {
        $peng_id = intval($m[1]);
    } elseif (is_numeric($raw)) {
        $peng_id = intval($raw);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'ID pengumuman tidak valid.']);
        exit;
    }

    // Hapus pengumuman
    $stmt = mysqli_prepare($koneksi, "DELETE FROM pengumuman WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $peng_id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Gagal menghapus pengumuman: ' . mysqli_error($koneksi));
    }

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Pengumuman dihapus.']);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Pengumuman tidak ditemukan.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

?>
