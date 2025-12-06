<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);

    if ($id > 0) {
        // Ambil path foto sebelum menghapus data
        $result = mysqli_query($koneksi, "SELECT foto_profil FROM users WHERE id = '$id' AND role = 'guru'");
        if ($data = mysqli_fetch_assoc($result)) {
            $foto_path = $data['foto_profil'];

            // Hapus user dari tabel utama
            $query = "DELETE FROM users WHERE id = '$id'";
            if (mysqli_query($koneksi, $query)) {
                // Jika query berhasil, hapus file foto
                if (!empty($foto_path) && file_exists('../' . $foto_path)) {
                    unlink('../' . $foto_path);
                }
                $_SESSION['sukses'] = "Guru berhasil dihapus.";
            } else {
                $_SESSION['gagal'] = "Gagal menghapus guru: " . mysqli_error($koneksi);
            }
        }
    }
    header("Location: ./?hal=guru");
    exit();
}
?>