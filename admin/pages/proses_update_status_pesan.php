<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

$id = $_GET['id'] ?? 0;
$status = $_GET['status'] ?? '';

if ($id > 0 && $status == 'Sudah Dibalas') {
    $query = "UPDATE kontak SET status = 'Sudah Dibalas' WHERE id = '$id'";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['sukses'] = "Status pesan berhasil diperbarui.";
    } else {
        $_SESSION['gagal'] = "Gagal memperbarui status pesan: " . mysqli_error($koneksi);
    }
} else {
    $_SESSION['gagal'] = "Permintaan tidak valid.";
}

header("Location: ./?hal=kontak");
exit();
?>