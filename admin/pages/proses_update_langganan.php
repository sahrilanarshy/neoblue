<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = intval($_POST['user_id']);
    $tipe_user = mysqli_real_escape_string($koneksi, $_POST['tipe_user']);
    $masa_aktif = !empty($_POST['masa_aktif']) ? "'".mysqli_real_escape_string($koneksi, $_POST['masa_aktif'])."'" : "NULL";

    if ($user_id > 0) {
        // Jika tipe user diubah ke 'free', masa_aktif di-NULL-kan
        if ($tipe_user == 'free') {
            $masa_aktif = "NULL";
        }

        $query = "UPDATE users SET tipe_user = '$tipe_user', masa_aktif = $masa_aktif WHERE id = '$user_id'";

        if (mysqli_query($koneksi, $query)) {
            $_SESSION['sukses'] = "Status langganan pengguna berhasil diperbarui.";
        } else {
            $_SESSION['gagal'] = "Gagal memperbarui status langganan: " . mysqli_error($koneksi);
        }
    }

    $redirect_page = ($tipe_user == 'premium') ? 'userpremium' : 'user';
    header("Location: ./?hal=$redirect_page");
    exit();
}
?>