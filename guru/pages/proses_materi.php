<?php
// session_start() tidak diperlukan karena file ini di-include dari index.php yang sudah memiliki session_start()

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Cek apakah user sudah login dan rolenya guru/admin
if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['admin', 'guru'])) {
    echo "<script>alert('Akses ditolak!'); window.location.href='index.php';</script>";
    exit();
}

include '../config/koneksi.php';

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
$redirect_url = "index.php?hal=materi";

if ($aksi == 'tambah') {
    $judul = $_POST['judul'];
    $subtest_id = intval($_POST['subtest_id']);
    $tanggal = $_POST['tanggal'];
    $tipe = $_POST['tipe'];
    $deskripsi = $_POST['deskripsi'];
    $link = $_POST['link']; // Ambil data link video

    $stmt = mysqli_prepare($koneksi, "INSERT INTO materi (judul, subtest_id, tanggal, tipe, link, deskripsi) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sissss", $judul, $subtest_id, $tanggal, $tipe, $link, $deskripsi);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $_SESSION['sukses'] = 'Data materi berhasil ditambahkan.';
    } else {
        $_SESSION['gagal'] = 'Gagal menambahkan data. Error: ' . mysqli_error($koneksi);
        $redirect_url = $_SERVER['HTTP_REFERER'];
    }
} elseif ($aksi == 'edit') {
    $id = intval($_POST['id']);
    $judul = $_POST['judul'];
    $subtest_id = intval($_POST['subtest_id']);
    $tanggal = $_POST['tanggal'];
    $tipe = $_POST['tipe'];
    $deskripsi = $_POST['deskripsi'];
    $link = $_POST['link']; // Ambil data link video

    $stmt = mysqli_prepare($koneksi, "UPDATE materi SET judul=?, subtest_id=?, tanggal=?, tipe=?, link=?, deskripsi=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "sissssi", $judul, $subtest_id, $tanggal, $tipe, $link, $deskripsi, $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $_SESSION['sukses'] = 'Data materi berhasil diperbarui.';
    } else {
        $_SESSION['gagal'] = 'Gagal memperbarui data. Error: ' . mysqli_error($koneksi);
        $redirect_url = $_SERVER['HTTP_REFERER'];
    }
} elseif ($aksi == 'hapus') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id > 0) {
        $stmt = mysqli_prepare($koneksi, "DELETE FROM materi WHERE id=?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $_SESSION['sukses'] = 'Data materi berhasil dihapus.';
        } else {
            $_SESSION['gagal'] = 'Gagal menghapus data. Error: ' . mysqli_error($koneksi);
        }
    } else {
        $_SESSION['gagal'] = 'ID materi tidak valid.';
    }
}

// Gunakan JavaScript untuk redirect karena header sudah dikirim oleh index.php
echo "<script>window.location.href = '{$redirect_url}';</script>";
exit;
?>
