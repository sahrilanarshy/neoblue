<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

// Cek apakah user sudah login dan rolenya guru/admin
if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['admin', 'guru'])) {
    echo "<script>alert('Akses ditolak!'); window.location.href='../login.php';</script>";
    exit;
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
$redirect_url = "index.php?hal=subtest";

if ($aksi == 'tambah') {
    $nama_subtest = mysqli_real_escape_string($koneksi, $_POST['nama_subtest']);
    $singkatan = $_POST['singkatan'];

    $stmt = mysqli_prepare($koneksi, "INSERT INTO subtest (nama_subtest, singkatan) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $nama_subtest, $singkatan);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $_SESSION['sukses'] = "Subtest baru berhasil ditambahkan.";
    } else {
        $_SESSION['gagal'] = "Gagal menambahkan data: " . mysqli_error($koneksi);
    }

} elseif ($aksi == 'edit') {
    $id = intval($_POST['id']);
    $nama_subtest = $_POST['nama_subtest'];
    $singkatan = $_POST['singkatan'];

    $stmt = mysqli_prepare($koneksi, "UPDATE subtest SET nama_subtest=?, singkatan=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssi", $nama_subtest, $singkatan, $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $_SESSION['sukses'] = "Data subtest berhasil diperbarui.";
    } else {
        $_SESSION['gagal'] = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }

} elseif ($aksi == 'hapus') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id > 0) {
        $stmt = mysqli_prepare($koneksi, "DELETE FROM subtest WHERE id=?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['sukses'] = "Data subtest berhasil dihapus.";
        } else {
            $_SESSION['gagal'] = "Gagal menghapus data atau data tidak ditemukan.";
        }
    } else {
        $_SESSION['gagal'] = "ID Subtest tidak valid.";
    }
}

// Gunakan JavaScript untuk redirect karena header sudah dikirim oleh index.php
echo "<script>window.location.href = '{$redirect_url}';</script>";
exit;
?>