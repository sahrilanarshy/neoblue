<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
include '../config/koneksi.php';
session_start();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Metode tidak diperbolehkan.']);
        exit;
    }

    // terima peng_id dan user_id (user_id optional — fallback ke session)
    $raw = isset($_POST['peng_id']) ? trim($_POST['peng_id']) : (isset($_POST['id']) ? trim($_POST['id']) : null);
    if (!$raw) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Parameter peng_id tidak ditemukan.']);
        exit;
    }

    if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
        $user_id = intval($_POST['user_id']);
    } elseif (isset($_SESSION['user_id'])) {
        $user_id = intval($_SESSION['user_id']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'User tidak dikenal.']);
        exit;
    }

    // parse peng_id seperti 'peng_123' atau '123'
    if (preg_match('/^peng_(\d+)$/', $raw, $m)) {
        $peng_id = intval($m[1]);
    } elseif (is_numeric($raw)) {
        $peng_id = intval($raw);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'ID pengumuman tidak valid.']);
        exit;
    }

    // buat tabel jika belum ada (safe)
    $create_hidden_sql = "CREATE TABLE IF NOT EXISTS user_hidden_pengumuman (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        pengumuman_id INT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY ux_user_peng (user_id, pengumuman_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($koneksi, $create_hidden_sql);

    // Pastikan pengumuman ada
    $stmt_check = mysqli_prepare($koneksi, "SELECT id FROM pengumuman WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt_check, 'i', $peng_id);
    mysqli_stmt_execute($stmt_check);
    $res_check = mysqli_stmt_get_result($stmt_check);
    if (mysqli_num_rows($res_check) === 0) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Pengumuman tidak ditemukan.']);
        exit;
    }

    // Insert ignore ke table hidden
    $stmt_ins = mysqli_prepare($koneksi, "INSERT INTO user_hidden_pengumuman (user_id, pengumuman_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE created_at = created_at");
    mysqli_stmt_bind_param($stmt_ins, 'ii', $user_id, $peng_id);
    if (!mysqli_stmt_execute($stmt_ins)) {
        throw new Exception('Gagal menyembunyikan pengumuman: ' . mysqli_error($koneksi));
    }

    echo json_encode(['status' => 'success', 'message' => 'Pengumuman disembunyikan untuk Anda.']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

?>
