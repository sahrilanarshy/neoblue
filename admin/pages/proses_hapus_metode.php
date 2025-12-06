<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);

    if ($id > 0) {
        $query = "DELETE FROM metode_pembayaran WHERE id = '$id'";
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['sukses'] = "Metode pembayaran berhasil dihapus.";
        } else {
            $_SESSION['gagal'] = "Gagal menghapus metode pembayaran: " . mysqli_error($koneksi);
        }
    }
    header("Location: ./?hal=metodepembayaran");
    exit();
}
?>