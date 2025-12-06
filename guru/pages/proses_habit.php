<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['admin', 'guru'])) {
    echo "<script>alert('Akses ditolak!'); window.location.href='../login.php';</script>";
    exit;
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
$redirect_url = ".?hal=habit";

if ($aksi == 'tambah_bacaan') {
    // Menggunakan prepared statements untuk keamanan
    $tanggal = $_POST['tanggal'];
    $subtest_id = intval($_POST['subtest_id']);
    $judul = $_POST['judul_bacaan'];
    $isi_bacaan = $_POST['isi_bacaan'];

    $stmt = mysqli_prepare($koneksi, "INSERT INTO habit_harian (tanggal, subtest_id, judul, jenis, isi_bacaan) VALUES (?, ?, ?, 'bacaan', ?)");
    mysqli_stmt_bind_param($stmt, "siss", $tanggal, $subtest_id, $judul, $isi_bacaan);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['sukses'] = "Habit bacaan berhasil ditambahkan.";
    } else {
        $_SESSION['gagal'] = "Gagal menambahkan habit: " . mysqli_error($koneksi);
    }

} elseif ($aksi == 'edit_bacaan') {
    $id = intval($_POST['id']);
    $tanggal = $_POST['tanggal'];
    $subtest_id = intval($_POST['subtest_id']);
    $judul = $_POST['judul_bacaan'];
    $isi_bacaan = $_POST['isi_bacaan'];

    $stmt = mysqli_prepare($koneksi, "UPDATE habit_harian SET tanggal=?, subtest_id=?, judul=?, isi_bacaan=? WHERE id=? AND jenis='bacaan'");
    mysqli_stmt_bind_param($stmt, "sissi", $tanggal, $subtest_id, $judul, $isi_bacaan, $id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['sukses'] = "Habit bacaan berhasil diperbarui.";
    } else {
        $_SESSION['gagal'] = "Gagal memperbarui habit: " . mysqli_error($koneksi);
    }

} elseif ($aksi == 'tambah_soal') {
    mysqli_begin_transaction($koneksi);
    try {
        $tanggal = $_POST['tanggal'];
        $subtest_id = intval($_POST['subtest_id']);
        $judul = $_POST['judul_soal'];

        // 1. Insert ke tabel utama (habit_harian)
        $stmt_habit = mysqli_prepare($koneksi, "INSERT INTO habit_harian (tanggal, subtest_id, judul, jenis) VALUES (?, ?, ?, 'soal')");
        mysqli_stmt_bind_param($stmt_habit, "sis", $tanggal, $subtest_id, $judul);
        if (!mysqli_stmt_execute($stmt_habit)) {
            throw new Exception("Gagal menyimpan data habit utama: " . mysqli_error($koneksi));
        }
        $habit_id = mysqli_insert_id($koneksi);

        // 2. Loop dan insert ke tabel detail (habit_soal)
        $stmt_soal = mysqli_prepare($koneksi, "INSERT INTO habit_soal (habit_id, pertanyaan, pilihan_a, pilihan_b, pilihan_c, pilihan_d, pilihan_e, kunci_jawaban, pembahasan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($_POST['pertanyaan'] as $key => $pertanyaan_text) {
            $pertanyaan = $pertanyaan_text;
            $pilihan_a = $_POST['pilihan_a'][$key];
            $pilihan_b = $_POST['pilihan_b'][$key];
            $pilihan_c = $_POST['pilihan_c'][$key];
            $pilihan_d = $_POST['pilihan_d'][$key];
            $pilihan_e = $_POST['pilihan_e'][$key];
            $kunci_jawaban = $_POST['kunci_jawaban'][$key];
            $pembahasan = $_POST['pembahasan'][$key];

            mysqli_stmt_bind_param($stmt_soal, "issssssss", $habit_id, $pertanyaan, $pilihan_a, $pilihan_b, $pilihan_c, $pilihan_d, $pilihan_e, $kunci_jawaban, $pembahasan);
            if (!mysqli_stmt_execute($stmt_soal)) {
                throw new Exception("Gagal menyimpan detail soal: " . mysqli_error($koneksi));
            }
        }

        mysqli_commit($koneksi);
        $_SESSION['sukses'] = "Habit soal berhasil ditambahkan.";
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        $_SESSION['gagal'] = $e->getMessage();
    }

} elseif ($aksi == 'edit_soal') {
    mysqli_begin_transaction($koneksi);
    try {
        $habit_id = intval($_POST['habit_id']);
        $tanggal = $_POST['tanggal'];
        $subtest_id = intval($_POST['subtest_id']);
        $judul = $_POST['judul_soal'];

        // 1. Update tabel utama (habit_harian)
        $stmt_habit = mysqli_prepare($koneksi, "UPDATE habit_harian SET tanggal=?, subtest_id=?, judul=? WHERE id=? AND jenis='soal'");
        mysqli_stmt_bind_param($stmt_habit, "sisi", $tanggal, $subtest_id, $judul, $habit_id);
        if (!mysqli_stmt_execute($stmt_habit)) {
            throw new Exception("Gagal memperbarui data habit utama: " . mysqli_error($koneksi));
        }

        // 2. Hapus semua soal lama yang terkait
        $stmt_delete = mysqli_prepare($koneksi, "DELETE FROM habit_soal WHERE habit_id = ?");
        mysqli_stmt_bind_param($stmt_delete, "i", $habit_id);
        if (!mysqli_stmt_execute($stmt_delete)) {
            throw new Exception("Gagal menghapus detail soal lama: " . mysqli_error($koneksi));
        }

        // 3. Insert kembali soal-soal yang baru dari form
        $stmt_soal = mysqli_prepare($koneksi, "INSERT INTO habit_soal (habit_id, pertanyaan, pilihan_a, pilihan_b, pilihan_c, pilihan_d, pilihan_e, kunci_jawaban, pembahasan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($_POST['pertanyaan'] as $key => $pertanyaan_text) {
            mysqli_stmt_bind_param($stmt_soal, "issssssss", $habit_id, $pertanyaan_text, $_POST['pilihan_a'][$key], $_POST['pilihan_b'][$key], $_POST['pilihan_c'][$key], $_POST['pilihan_d'][$key], $_POST['pilihan_e'][$key], $_POST['kunci_jawaban'][$key], $_POST['pembahasan'][$key]);
            if (!mysqli_stmt_execute($stmt_soal)) {
                throw new Exception("Gagal menyimpan detail soal baru: " . mysqli_error($koneksi));
            }
        }
        mysqli_commit($koneksi);
        $_SESSION['sukses'] = "Habit soal berhasil diperbarui.";
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        $_SESSION['gagal'] = $e->getMessage();
    }
} elseif ($aksi == 'hapus') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id > 0) {
        // Karena ada foreign key dengan ON DELETE CASCADE,
        // menghapus dari habit_harian akan otomatis menghapus data di habit_soal juga.
        $stmt = mysqli_prepare($koneksi, "DELETE FROM habit_harian WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            if(mysqli_affected_rows($koneksi) > 0) {
                $_SESSION['sukses'] = "Habit berhasil dihapus.";
            } else {
                $_SESSION['gagal'] = "Habit tidak ditemukan.";
            }
        } else {
            $_SESSION['gagal'] = "Gagal menghapus habit: " . mysqli_error($koneksi);
        }
    } else {
        $_SESSION['gagal'] = "ID Habit tidak valid.";
    }
} else {
    $_SESSION['gagal'] = "Aksi tidak dikenal.";
}

// Gunakan JavaScript untuk redirect karena header sudah dikirim oleh index.php
echo "<script>window.location.href = '{$redirect_url}';</script>";
exit;

?>