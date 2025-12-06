<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paket_id = intval($_POST['id']);
    $nama_paket = mysqli_real_escape_string($koneksi, $_POST['nama_paket']);
    $harga = floatval($_POST['harga']);
    $is_unggulan = isset($_POST['is_unggulan']) ? 1 : 0;
    $fitur_ids = $_POST['fitur'] ?? [];

    if ($paket_id > 0) {
        // Mulai transaksi
        mysqli_begin_transaction($koneksi);

        try {
            // 1. Update data paket utama
            $query_paket = "UPDATE paket SET nama_paket = '$nama_paket', harga = '$harga', is_unggulan = '$is_unggulan' WHERE id = '$paket_id'";
            if (!mysqli_query($koneksi, $query_paket)) {
                throw new Exception("Gagal memperbarui paket: " . mysqli_error($koneksi));
            }

            // 2. Hapus relasi fitur yang lama
            if (!mysqli_query($koneksi, "DELETE FROM paket_fitur WHERE paket_id = '$paket_id'")) {
                throw new Exception("Gagal menghapus relasi fitur lama: " . mysqli_error($koneksi));
            }

            // 3. Simpan relasi fitur yang baru
            if (!empty($fitur_ids)) {
                foreach ($fitur_ids as $fitur_id) {
                    $fitur_id = intval($fitur_id);
                    $query_relasi = "INSERT INTO paket_fitur (paket_id, fitur_id) VALUES ('$paket_id', '$fitur_id')";
                    if (!mysqli_query($koneksi, $query_relasi)) {
                        throw new Exception("Gagal menyimpan relasi fitur baru: " . mysqli_error($koneksi));
                    }
                }
            }
            mysqli_commit($koneksi);
            $_SESSION['sukses'] = "Paket berhasil diperbarui.";
        } catch (Exception $e) {
            mysqli_rollback($koneksi);
            $_SESSION['gagal'] = $e->getMessage();
        }
    }
    header("Location: ./?hal=paket");
    exit();
}
?>