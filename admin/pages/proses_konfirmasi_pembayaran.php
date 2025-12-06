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

$pembayaran_id = intval($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';
$admin_id = $_SESSION['user_id'];

if ($pembayaran_id > 0 && ($action == 'terima' || $action == 'tolak')) {
    mysqli_begin_transaction($koneksi);
    try {
        $status_baru = ($action == 'terima') ? 'Diterima' : 'Ditolak';

        // Ambil data pembayaran untuk mengetahui user tujuan (sebelum update)
        $stmt_get = mysqli_prepare($koneksi, "SELECT user_id, paket_id FROM pembayaran WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt_get, 'i', $pembayaran_id);
        mysqli_stmt_execute($stmt_get);
        $res_get = mysqli_stmt_get_result($stmt_get);
        $pembayaran_row = mysqli_fetch_assoc($res_get);
        $target_user_id = $pembayaran_row['user_id'] ?? null;

        // 1. Update status pembayaran
        $stmt_pembayaran = mysqli_prepare($koneksi, "UPDATE pembayaran SET status_pembayaran = ?, tanggal_konfirmasi = NOW(), admin_id = ? WHERE id = ? AND status_pembayaran = 'Menunggu'");
        mysqli_stmt_bind_param($stmt_pembayaran, "sii", $status_baru, $admin_id, $pembayaran_id);
        if (!mysqli_stmt_execute($stmt_pembayaran)) {
            throw new Exception("Gagal update status pembayaran.");
        }

        // Tambahkan notifikasi untuk user terkait (baik Ditolak maupun Diterima)
        if (!empty($target_user_id)) {
            // Buat tabel notifications jika belum ada
            $create_sql = "CREATE TABLE IF NOT EXISTS notifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                link VARCHAR(255) DEFAULT NULL,
                is_read TINYINT(1) DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX (user_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            mysqli_query($koneksi, $create_sql);
            if ($status_baru === 'Ditolak') {
                $title = 'Pembayaran Ditolak';
                $message = 'Pembayaran Anda untuk pemesanan ID #' . $pembayaran_id . ' telah ditolak. Silakan unggah bukti pembayaran kembali atau hubungi admin.';
                $link = './?hal=konfirmasi';
            } else {
                $title = 'Pembayaran Diterima';
                $message = 'Pembayaran Anda untuk pemesanan ID #' . $pembayaran_id . ' telah diterima. Akun Anda sekarang berstatus Premium.';
                $link = './?hal=profile';
            }

            $stmt_ins = mysqli_prepare($koneksi, "INSERT INTO notifications (user_id, title, message, link) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt_ins, 'isss', $target_user_id, $title, $message, $link);
            mysqli_stmt_execute($stmt_ins);
        }

        mysqli_commit($koneksi);
        $_SESSION['sukses'] = "Pembayaran berhasil di-" . strtolower($status_baru) . ".";
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        $_SESSION['gagal'] = "Terjadi kesalahan: " . $e->getMessage();
    }
}
header("Location: ./?hal=konfirmasi");
exit();
?>