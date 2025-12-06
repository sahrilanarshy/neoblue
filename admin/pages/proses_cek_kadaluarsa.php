<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

// Pastikan hanya admin yang bisa menjalankan skrip ini
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['gagal'] = "Akses ditolak.";
    header("Location: ./?hal=beranda");
    exit();
}

// Query untuk mencari user premium yang sudah kadaluarsa
// CURDATE() akan mengambil tanggal hari ini
$query = "UPDATE users 
          SET tipe_user = 'free', masa_aktif = NULL 
          WHERE tipe_user = 'premium' AND masa_aktif < CURDATE()";

if (mysqli_query($koneksi, $query)) {
    $jumlah_terpengaruh = mysqli_affected_rows($koneksi);
    $_SESSION['sukses'] = "$jumlah_terpengaruh langganan yang kedaluwarsa berhasil diperbarui menjadi 'Free'.";
} else {
    $_SESSION['gagal'] = "Terjadi kesalahan saat memperbarui langganan: " . mysqli_error($koneksi);
}

header("Location: ./?hal=beranda");
exit();
?>