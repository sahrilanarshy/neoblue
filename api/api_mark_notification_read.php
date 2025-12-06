<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
include '../config/koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Terima POST (form) atau GET sebagai fallback. Jika tidak diberikan, gunakan session user_id.
$user_id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
$notif_id = isset($_REQUEST['notif_id']) ? intval($_REQUEST['notif_id']) : 0;
if ($user_id <= 0 && isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']);
}

if ($user_id <= 0 || $notif_id <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Parameter tidak lengkap.']);
    exit;
}

$stmt = mysqli_prepare($koneksi, "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, 'ii', $notif_id, $user_id);
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate notifikasi.']);
}
?>