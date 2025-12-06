<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);

    if ($id > 0) {
        // Hapus dulu relasi di paket_fitur untuk menghindari error foreign key
        mysqli_query($koneksi, "DELETE FROM paket_fitur WHERE fitur_id = '$id'");

        // Hapus fitur dari tabel utama
        $query = "DELETE FROM fitur WHERE id = '$id'";
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['sukses'] = "Fitur berhasil dihapus.";
        } else {
            $_SESSION['gagal'] = "Gagal menghapus fitur: " . mysqli_error($koneksi);
        }
    }
    header("Location: ./?hal=fitur");
    exit();
}
?>