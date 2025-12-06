<?php
// session_start() tidak diperlukan karena sudah dipanggil di index.php
include '../config/koneksi.php';

if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['admin', 'guru'])) {
    echo "<script>alert('Akses ditolak!'); window.location.href='../login.php';</script>";
    exit;
}

$redirect_url = "index.php?hal=pengumuman";

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

if ($aksi == 'tambah') {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi_pengumuman']);
    $link = mysqli_real_escape_string($koneksi, $_POST['link']);
    $tgl_terbit = mysqli_real_escape_string($koneksi, $_POST['tgl_terbit']);
    $tgl_selesai = !empty($_POST['tgl_selesai']) ? "'" . mysqli_real_escape_string($koneksi, $_POST['tgl_selesai']) . "'" : "NULL";
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    $query = "INSERT INTO pengumuman (judul, isi, link, tanggal_terbit, tanggal_selesai, status) VALUES ('$judul', '$isi', '$link', '$tgl_terbit', $tgl_selesai, '$status')";
    
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['sukses'] = "Pengumuman berhasil ditambahkan.";
    } else {
        $_SESSION['gagal'] = "Gagal menambahkan pengumuman: " . mysqli_error($koneksi); // Tambahkan detail error
    }

} elseif ($aksi == 'edit') {
    $id = intval($_POST['id']);
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi_pengumuman']);
    $link = mysqli_real_escape_string($koneksi, $_POST['link']);
    $tgl_terbit = mysqli_real_escape_string($koneksi, $_POST['tgl_terbit']);
    $tgl_selesai = !empty($_POST['tgl_selesai']) ? "'" . mysqli_real_escape_string($koneksi, $_POST['tgl_selesai']) . "'" : "NULL";
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    $query = "UPDATE pengumuman SET judul='$judul', isi='$isi', link='$link', tanggal_terbit='$tgl_terbit', tanggal_selesai=$tgl_selesai, status='$status' WHERE id=$id";
    
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['sukses'] = "Pengumuman berhasil diperbarui.";
    } else {
        $_SESSION['gagal'] = "Gagal memperbarui pengumuman: " . mysqli_error($koneksi); // Tambahkan detail error
    }

} elseif ($aksi == 'hapus') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id > 0) {
        $query = "DELETE FROM pengumuman WHERE id=$id";
        if (mysqli_query($koneksi, $query)) {
            if(mysqli_affected_rows($koneksi) > 0) {
                $_SESSION['sukses'] = "Pengumuman berhasil dihapus.";
            } else {
                $_SESSION['gagal'] = "Pengumuman tidak ditemukan.";
            } // Tambahkan detail error
        } else {
            $_SESSION['gagal'] = "Gagal menghapus pengumuman: " . mysqli_error($koneksi);
        }
    } else {
        $_SESSION['gagal'] = "ID Pengumuman tidak valid.";
    }
} else {
    $_SESSION['gagal'] = "Aksi tidak dikenal.";
}

// Gunakan JavaScript untuk redirect karena header sudah dikirim oleh index.php
echo "<script>window.location.href = '{$redirect_url}';</script>";
exit; // Pastikan tidak ada output lain setelah ini

?>