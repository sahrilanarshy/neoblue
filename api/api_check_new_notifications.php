<?php
require_once __DIR__ . '/api_common.php';
session_start();
header('Content-Type: application/json');
include '../config/koneksi.php';

$response = ['new_notification' => false];

// Ambil waktu terakhir user melihat notifikasi dari session.
// Jika tidak ada, kita anggap saja waktu yang sangat lampau.
$last_view_time = isset($_SESSION['last_notification_view']) ? $_SESSION['last_notification_view'] : '1970-01-01 00:00:00';

try {
    // Query untuk mengecek apakah ada pengumuman yang 'Published'
    // dan tanggal pembuatannya lebih baru dari waktu terakhir user melihat notifikasi.
    $stmt = mysqli_prepare($koneksi, "SELECT 1 FROM pengumuman WHERE status = 'Published' AND created_at > ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $last_view_time);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $response['new_notification'] = true;
    }

    // Cek juga notifikasi personal di tabel notifications (is_read = 0)
    if (isset($_SESSION['user_id'])) {
        $user_id = intval($_SESSION['user_id']);
        $stmt2 = mysqli_prepare($koneksi, "SELECT 1 FROM notifications WHERE user_id = ? AND is_read = 0 LIMIT 1");
        mysqli_stmt_bind_param($stmt2, 'i', $user_id);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_store_result($stmt2);
        if (mysqli_stmt_num_rows($stmt2) > 0) {
            $response['new_notification'] = true;
        }
    }

} catch (Exception $e) {
    // Jika ada error, anggap tidak ada notifikasi baru agar tidak mengganggu.
}

echo json_encode($response);
?>