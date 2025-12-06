<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_paket = mysqli_real_escape_string($koneksi, $_POST['nama_paket']);
    $harga = floatval($_POST['harga']);
    $is_unggulan = isset($_POST['is_unggulan']) ? 1 : 0;
    $fitur_ids = $_POST['fitur'] ?? [];

    // Mulai transaksi
    mysqli_begin_transaction($koneksi);

    try {
        // 1. Simpan data paket utama
        $query_paket = "INSERT INTO paket (nama_paket, harga, is_unggulan) VALUES ('$nama_paket', '$harga', '$is_unggulan')";
        if (!mysqli_query($koneksi, $query_paket)) {
            throw new Exception("Gagal menyimpan paket: " . mysqli_error($koneksi));
        }
        $paket_id = mysqli_insert_id($koneksi);

        // 2. Simpan relasi fitur ke tabel paket_fitur
        if (!empty($fitur_ids)) {
            foreach ($fitur_ids as $fitur_id) {
                $fitur_id = intval($fitur_id);
                $query_relasi = "INSERT INTO paket_fitur (paket_id, fitur_id) VALUES ('$paket_id', '$fitur_id')";
                if (!mysqli_query($koneksi, $query_relasi)) {
                    throw new Exception("Gagal menyimpan relasi fitur: " . mysqli_error($koneksi));
                }
            }
        }
        mysqli_commit($koneksi);
        $_SESSION['sukses'] = "Paket berhasil ditambahkan.";
        header("Location: ./?hal=paket");
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        $_SESSION['gagal'] = $e->getMessage();
        header("Location: ./?hal=tambahpaket");
    }
    exit();
}
?>