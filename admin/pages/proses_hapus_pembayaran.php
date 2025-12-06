<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$redirect_url = './?hal=riwayat';

// Terima via POST dari modal universal
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id > 0) {
    // Ambil data pembayaran untuk mendapatkan path bukti jika perlu dihapus
    $stmt = mysqli_prepare($koneksi, "SELECT bukti_pembayaran FROM pembayaran WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    $bukti = $row['bukti_pembayaran'] ?? null;

    // Hapus record pembayaran
    $stmt2 = mysqli_prepare($koneksi, "DELETE FROM pembayaran WHERE id = ?");
    mysqli_stmt_bind_param($stmt2, 'i', $id);
    if (mysqli_stmt_execute($stmt2)) {
        if (mysqli_stmt_affected_rows($stmt2) > 0) {
            // Hapus file bukti jika ada
            if (!empty($bukti) && file_exists('../' . $bukti)) {
                @unlink('../' . $bukti);
            }
            $_SESSION['sukses'] = 'Riwayat pembayaran berhasil dihapus.';
        } else {
            $_SESSION['gagal'] = 'Data pembayaran tidak ditemukan atau sudah dihapus.';
        }
    } else {
        $_SESSION['gagal'] = 'Gagal menghapus data: ' . mysqli_error($koneksi);
    }
} else {
    $_SESSION['gagal'] = 'ID tidak valid.';
}

// Redirect kembali ke riwayat
header('Location: ' . $redirect_url);
exit();
?>