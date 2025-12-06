<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);

    if ($id > 0) {
        // Hapus dulu relasi di paket_fitur
        mysqli_query($koneksi, "DELETE FROM paket_fitur WHERE paket_id = '$id'");

        // Hapus paket dari tabel utama
        $query = "DELETE FROM paket WHERE id = '$id'";
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['sukses'] = "Paket berhasil dihapus.";
        } else {
            $_SESSION['gagal'] = "Gagal menghapus paket: " . mysqli_error($koneksi);
        }
    }
    header("Location: ./?hal=paket");
    exit();
}
?>