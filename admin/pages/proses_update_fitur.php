<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nama_fitur = mysqli_real_escape_string($koneksi, $_POST['nama_fitur']);
    $urutan = intval($_POST['urutan']);

    if ($id > 0) {
        $query = "UPDATE fitur SET nama_fitur = '$nama_fitur', urutan = '$urutan' WHERE id = '$id'";

        if (mysqli_query($koneksi, $query)) {
            $_SESSION['sukses'] = "Fitur berhasil diperbarui.";
        } else {
            $_SESSION['gagal'] = "Gagal memperbarui fitur: " . mysqli_error($koneksi);
        }
    }
    header("Location: ./?hal=fitur");
    exit();
}
?>