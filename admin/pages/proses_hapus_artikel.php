<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);

    if ($id > 0) {
        // Ambil path gambar sebelum menghapus data dari DB
        $result = mysqli_query($koneksi, "SELECT gambar FROM artikel WHERE id = '$id'");
        if ($data = mysqli_fetch_assoc($result)) {
            $gambar_path = $data['gambar'];

            // Hapus data artikel dari tabel
            $query = "DELETE FROM artikel WHERE id = '$id'";
            if (mysqli_query($koneksi, $query)) {
                // Jika query berhasil, hapus file gambar dari server
                if (!empty($gambar_path) && file_exists('../' . $gambar_path)) {
                    unlink('../' . $gambar_path);
                }
                $_SESSION['sukses'] = "Artikel berhasil dihapus.";
            } else {
                $_SESSION['gagal'] = "Gagal menghapus artikel: " . mysqli_error($koneksi);
            }
        }
    }
    header("Location: ./?hal=artikel");
    exit();
}
?>