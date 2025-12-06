<?php
// 1. Ambil ID habit dari URL dan validasi
$habit_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($habit_id <= 0) {
    echo "<p class='text-center text-danger'>ID Habit tidak valid.</p>";
    return; // Hentikan eksekusi jika ID tidak valid
}

// 2. Ambil data habit bacaan dari database
// Variabel $koneksi sudah tersedia dari index.php
$stmt = mysqli_prepare($koneksi, "SELECT hh.tanggal, hh.judul, hh.isi_bacaan, s.nama_subtest FROM habit_harian hh JOIN subtest s ON hh.subtest_id = s.id WHERE hh.id = ? AND hh.jenis = 'bacaan'");
mysqli_stmt_bind_param($stmt, "i", $habit_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$habit = mysqli_fetch_assoc($result);

if (!$habit) {
    echo "<p class='text-center text-danger'>Habit bacaan tidak ditemukan.</p>";
    return; // Hentikan eksekusi jika data tidak ditemukan
}

// Catat bahwa user telah membuka habit ini (session-based tracker)
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['opened_habits']) || !is_array($_SESSION['opened_habits'])) $_SESSION['opened_habits'] = [];
if (!in_array($habit_id, $_SESSION['opened_habits'])) {
    $_SESSION['opened_habits'][] = $habit_id;
}

$date_obj = date_create($habit['tanggal']);
?>
<main class="dashboard-content">
    <div class="habit-detail-container">
        <header class="habit-detail-header">
            <h1><?= htmlspecialchars($habit['judul']); ?></h1>
            <p><?= date_format($date_obj, 'l, d F Y'); ?> - <?= htmlspecialchars($habit['nama_subtest']); ?></p>
        </header>

        <article class="reading-content">
            <?= $habit['isi_bacaan']; // Konten dari TinyMCE, tidak perlu htmlspecialchars ?>
        </article>
    </div>
</main>