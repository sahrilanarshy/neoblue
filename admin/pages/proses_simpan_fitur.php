<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_fitur = mysqli_real_escape_string($koneksi, $_POST['nama_fitur']);
    $urutan = intval($_POST['urutan']);

    $query = "INSERT INTO fitur (nama_fitur, urutan) VALUES ('$nama_fitur', '$urutan')";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['sukses'] = "Fitur berhasil ditambahkan.";
        header("Location: ./?hal=fitur");
    } else {
        $_SESSION['gagal'] = "Gagal menambahkan fitur: " . mysqli_error($koneksi);
        header("Location: ./?hal=tambahfitur");
    }
    exit();
}
?>