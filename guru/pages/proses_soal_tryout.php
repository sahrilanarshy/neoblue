<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['admin', 'guru'])) {
    echo "<script>alert('Akses ditolak!'); window.location.href='../login.php';</script>"; // Perbaiki redirect
    exit();
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
$id_tryout = isset($_REQUEST['id_tryout']) ? intval($_REQUEST['id_tryout']) : 0;
$redirect_url = "index.php?hal=soaltryout&id_tryout=" . $id_tryout;

if ($aksi == 'tambah') {
    $subtest_id = intval($_POST['subtest_id']);
    $konteks_soal = $_POST['konteks_soal'];
    $waktu_pengerjaan = intval($_POST['waktu_pengerjaan']);

    mysqli_begin_transaction($koneksi);
    try {
        // 1. Simpan/Update waktu pengerjaan di tabel tryout_subtest
        $stmt_waktu = mysqli_prepare($koneksi, "INSERT INTO tryout_subtest (tryout_id, subtest_id, waktu_pengerjaan) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE waktu_pengerjaan = VALUES(waktu_pengerjaan)");
        mysqli_stmt_bind_param($stmt_waktu, "iii", $id_tryout, $subtest_id, $waktu_pengerjaan);
        if (!mysqli_stmt_execute($stmt_waktu)) {
            throw new Exception("Gagal menyimpan waktu pengerjaan: " . mysqli_error($koneksi));
        }

        // 2. Loop melalui setiap soal yang dikirimkan
        $stmt_soal = mysqli_prepare($koneksi, "INSERT INTO soal_tryout (tryout_id, subtest_id, konteks_soal, pertanyaan, pilihan_a, pilihan_b, pilihan_c, pilihan_d, pilihan_e, kunci_jawaban, pembahasan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($_POST['pertanyaan'] as $key => $pertanyaan_text) {
            $pertanyaan = $pertanyaan_text;
            $pilihan_a = $_POST['pilihan_a'][$key];
            $pilihan_b = $_POST['pilihan_b'][$key];
            $pilihan_c = $_POST['pilihan_c'][$key];
            $pilihan_d = $_POST['pilihan_d'][$key];
            $pilihan_e = $_POST['pilihan_e'][$key];
            $kunci_jawaban = $_POST['kunci_jawaban'][$key];
            $pembahasan = $_POST['pembahasan'][$key];

            mysqli_stmt_bind_param($stmt_soal, "iisssssssss", $id_tryout, $subtest_id, $konteks_soal, $pertanyaan, $pilihan_a, $pilihan_b, $pilihan_c, $pilihan_d, $pilihan_e, $kunci_jawaban, $pembahasan);
            if (!mysqli_stmt_execute($stmt_soal)) {
                throw new Exception("Gagal menambahkan soal: " . mysqli_error($koneksi));
            }
        }

        mysqli_commit($koneksi);
        $_SESSION['sukses'] = "Soal tryout berhasil ditambahkan.";
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        $_SESSION['gagal'] = $e->getMessage();
    }
} elseif ($aksi == 'edit_grup') {
    $id_tryout = intval($_POST['id_tryout']);
    $subtest_id = intval($_POST['subtest_id']);
    $konteks_soal = $_POST['konteks_soal'];
    $waktu_pengerjaan = intval($_POST['waktu_pengerjaan']);

    mysqli_begin_transaction($koneksi);
    try {
        // 1. Simpan/Update waktu pengerjaan di tabel tryout_subtest
        $stmt_waktu = mysqli_prepare($koneksi, "INSERT INTO tryout_subtest (tryout_id, subtest_id, waktu_pengerjaan) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE waktu_pengerjaan = VALUES(waktu_pengerjaan)");
        mysqli_stmt_bind_param($stmt_waktu, "iii", $id_tryout, $subtest_id, $waktu_pengerjaan);
        if (!mysqli_stmt_execute($stmt_waktu)) {
            throw new Exception("Gagal menyimpan waktu pengerjaan: " . mysqli_error($koneksi));
        }

        // 2. Hapus semua soal yang ada untuk tryout_id dan subtest_id ini
        $stmt_delete = mysqli_prepare($koneksi, "DELETE FROM soal_tryout WHERE tryout_id = ? AND subtest_id = ?");
        mysqli_stmt_bind_param($stmt_delete, "ii", $id_tryout, $subtest_id);
        if (!mysqli_stmt_execute($stmt_delete)) {
            throw new Exception("Gagal menghapus soal lama: " . mysqli_error($koneksi));
        }

        // 3. Masukkan kembali semua soal yang disubmit dari form
        if (isset($_POST['pertanyaan']) && is_array($_POST['pertanyaan'])) {
            $stmt_insert = mysqli_prepare($koneksi, "INSERT INTO soal_tryout (tryout_id, subtest_id, konteks_soal, pertanyaan, pilihan_a, pilihan_b, pilihan_c, pilihan_d, pilihan_e, kunci_jawaban, pembahasan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            foreach ($_POST['pertanyaan'] as $key => $pertanyaan_text) {
                $pertanyaan = $pertanyaan_text;
                $pilihan_a = $_POST['pilihan_a'][$key];
                $pilihan_b = $_POST['pilihan_b'][$key];
                $pilihan_c = $_POST['pilihan_c'][$key];
                $pilihan_d = $_POST['pilihan_d'][$key];
                $pilihan_e = $_POST['pilihan_e'][$key];
                $kunci_jawaban = $_POST['kunci_jawaban'][$key];
                $pembahasan = $_POST['pembahasan'][$key];

                mysqli_stmt_bind_param($stmt_insert, "iisssssssss", $id_tryout, $subtest_id, $konteks_soal, $pertanyaan, $pilihan_a, $pilihan_b, $pilihan_c, $pilihan_d, $pilihan_e, $kunci_jawaban, $pembahasan);
                if (!mysqli_stmt_execute($stmt_insert)) {
                    throw new Exception("Gagal menambahkan soal baru: " . mysqli_error($koneksi));
                }
            }
        }

        mysqli_commit($koneksi);
        $_SESSION['sukses'] = "Soal tryout berhasil diperbarui.";
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        $_SESSION['gagal'] = "Gagal memperbarui soal: " . $e->getMessage();
    }

} elseif ($aksi == 'hapus') {
    $id_soal = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id_soal > 0) {
        $stmt = mysqli_prepare($koneksi, "DELETE FROM soal_tryout WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id_soal);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['sukses'] = "Soal berhasil dihapus.";
        } else {
            $_SESSION['gagal'] = "Gagal menghapus soal: " . mysqli_error($koneksi);
        }
    } else {
        $_SESSION['gagal'] = "ID Soal tidak valid.";
    }
}
elseif ($aksi == 'hapus_grup') {
    $id_tryout = isset($_GET['id_tryout']) ? intval($_GET['id_tryout']) : 0;
    $subtest_id = isset($_GET['subtest_id']) ? intval($_GET['subtest_id']) : 0;

    if ($id_tryout > 0 && $subtest_id > 0) {
        mysqli_begin_transaction($koneksi);
        try {
            // Hapus soalnya
            $stmt_soal = mysqli_prepare($koneksi, "DELETE FROM soal_tryout WHERE tryout_id = ? AND subtest_id = ?");
            mysqli_stmt_bind_param($stmt_soal, "ii", $id_tryout, $subtest_id);
            mysqli_stmt_execute($stmt_soal);

            // Hapus juga datanya dari tryout_subtest
            $stmt_waktu = mysqli_prepare($koneksi, "DELETE FROM tryout_subtest WHERE tryout_id = ? AND subtest_id = ?");
            mysqli_stmt_bind_param($stmt_waktu, "ii", $id_tryout, $subtest_id);
            mysqli_stmt_execute($stmt_waktu);

            mysqli_commit($koneksi);
            $_SESSION['sukses'] = "Grup soal dan pengaturan waktunya berhasil dihapus.";
        } catch (Exception $e) {
            mysqli_rollback($koneksi);
            $_SESSION['gagal'] = "Gagal menghapus grup soal: " . $e->getMessage();
        }
    } else {
        $_SESSION['gagal'] = "ID Tryout atau Subtest tidak valid.";
    }
}

// Gunakan JavaScript untuk redirect karena header sudah dikirim oleh index.php
echo "<script>window.location.href = '{$redirect_url}';</script>";
exit;
?>