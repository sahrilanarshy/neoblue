<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../config/koneksi.php';

if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['admin', 'guru'])) {
    echo "<script>alert('Akses ditolak!'); window.location.href='../login.php';</script>";
    exit();
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
$redirect_url = "../?hal=riwayat_tryout";

if ($aksi == 'hapus') {
    $session_id = isset($_GET['session_id']) ? intval($_GET['session_id']) : 0;

    if ($session_id > 0) {
        $stmt = mysqli_prepare($koneksi, "DELETE FROM user_tryout_sessions WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $session_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['sukses'] = "Riwayat tryout berhasil dihapus.";
        } else {
            $_SESSION['gagal'] = "Gagal menghapus data: " . mysqli_error($koneksi);
        }
    } else {
        $_SESSION['gagal'] = "ID Riwayat tidak valid.";
    }
}

echo "<script>window.location.href = '{$redirect_url}';</script>";
exit;
?>