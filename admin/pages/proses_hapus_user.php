<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);

    if ($id > 0) {
        // Hapus user dari tabel utama
        $query = "DELETE FROM users WHERE id = '$id' AND role = 'siswa'";
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['sukses'] = "Siswa berhasil dihapus.";
        } else {
            $_SESSION['gagal'] = "Gagal menghapus siswa: " . mysqli_error($koneksi);
        }
    }
    header("Location: ./?hal=user");
    exit();
}
?>