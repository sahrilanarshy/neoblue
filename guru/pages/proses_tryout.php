<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['admin', 'guru'])) {
    echo "<script>alert('Akses ditolak!'); window.location.href='../login.php';</script>";
    exit();
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
$redirect_url = "index.php?hal=tryout";

if ($aksi == 'tambah') {
    $nama_tryout = $_POST['nama_tryout'];
    $tgl_mulai = $_POST['tgl_mulai'];
    $tgl_selesai = $_POST['tgl_selesai'];
    $waktu_pengerjaan = 0;
    $tipe = isset($_POST['is_premium']) && $_POST['is_premium'] == '1' ? 'Premium' : 'Free'; // Menangani switch toggle

    $stmt = mysqli_prepare($koneksi, "INSERT INTO tryout (nama_tryout, tanggal_mulai, tanggal_selesai, tipe, waktu_pengerjaan) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssi", $nama_tryout, $tgl_mulai, $tgl_selesai, $tipe, $waktu_pengerjaan);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $_SESSION['sukses'] = "Tryout baru berhasil ditambahkan.";
    } else {
        $_SESSION['gagal'] = "Gagal menambahkan data: " . mysqli_error($koneksi);
        $redirect_url = $_SERVER['HTTP_REFERER'];
    }

} elseif ($aksi == 'edit') {
    $id = intval($_POST['id']);
    $nama_tryout = $_POST['nama_tryout'];
    $tgl_mulai = $_POST['tgl_mulai'];
    $tgl_selesai = $_POST['tgl_selesai'];
    $waktu_pengerjaan = isset($_POST['waktu_pengerjaan']) ? $_POST['waktu_pengerjaan'] : 0;
    $tipe = isset($_POST['is_premium']) && $_POST['is_premium'] == '1' ? 'Premium' : 'Free'; // Menangani switch toggle

    $stmt = mysqli_prepare($koneksi, "UPDATE tryout SET nama_tryout=?, tanggal_mulai=?, tanggal_selesai=?, tipe=?, waktu_pengerjaan=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssssii", $nama_tryout, $tgl_mulai, $tgl_selesai, $tipe, $waktu_pengerjaan, $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $_SESSION['sukses'] = "Data tryout berhasil diperbarui.";
    } else {
        $_SESSION['gagal'] = "Gagal memperbarui data: " . mysqli_error($koneksi);
        $redirect_url = $_SERVER['HTTP_REFERER'];
    }

} elseif ($aksi == 'hapus') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id > 0) {
        $stmt = mysqli_prepare($koneksi, "DELETE FROM tryout WHERE id=?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['sukses'] = "Data tryout berhasil dihapus.";
        } else {
            $_SESSION['gagal'] = "Gagal menghapus data: " . mysqli_error($koneksi);
        }
    } else {
        $_SESSION['gagal'] = "ID Tryout tidak valid.";
    }
}

// Gunakan JavaScript untuk redirect karena header sudah dikirim oleh index.php
echo "<script>window.location.href = '{$redirect_url}';</script>";
exit;
?>