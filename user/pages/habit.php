<?php
// 1. Ambil data habit dari database
// Koneksi $koneksi sudah tersedia dari file index.php
$query = mysqli_query($koneksi, 'SELECT id, tanggal, judul, jenis, subtest_id FROM habit_harian ORDER BY tanggal DESC');

$habits_by_date = [];
while ($row = mysqli_fetch_assoc($query)) {
    $habits_by_date[$row['tanggal']][] = $row;
}

// TODO: Ambil data progres habit user untuk menampilkan status "Selesai"
// Untuk saat ini, kita asumsikan semua belum selesai.
$user_progress = [];
// Gunakan session sebagai tracker sementara untuk habit yang sudah dibuka oleh user.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_progress = isset($_SESSION['opened_habits']) && is_array($_SESSION['opened_habits']) ? $_SESSION['opened_habits'] : [];

?>
<main class="dashboard-content">
    <h1 class="page-title">Habit Harian</h1>
    <div class="habit-container">

        <?php if (empty($habits_by_date)): ?>
        <p class="text-center text-muted">Belum ada habit harian yang tersedia.</p>
        <?php else: ?>
        <?php foreach ($habits_by_date as $tanggal => $habits): ?>
        <?php
        $date_obj = date_create($tanggal);
        $is_today = date('Y-m-d') == $tanggal;
        ?>
        <div class="habit-day-entry">
            <div class="habit-date-header">
                <p class="date-text"><?= date_format($date_obj, 'd/m/Y') ?></p>
                <?php if ($is_today): ?>
                <span class="tag-today">Hari ini</span>
                <?php endif; ?>
            </div>
            <div class="habit-cards-grid">
                <?php
                // Pisahkan antara soal dan bacaan untuk tanggal ini
                $soal = null;
                $bacaan = null;
                foreach ($habits as $habit) {
                    if ($habit['jenis'] == 'soal') {
                        $soal = $habit;
                    }
                    if ($habit['jenis'] == 'bacaan') {
                        $bacaan = $habit;
                    }
                }
                ?>

                <?php if ($soal): ?>
                <div class="habit-card">
                    <p class="habit-title">Soal</p>
                    <?php if (in_array($soal['id'], $user_progress)): ?>
                        <a href=".?hal=detailsoalhabit&id=<?= $soal['id'] ?>" class="habit-button yellow opened">
                            <span class="habit-check" aria-hidden="true"><i class="bi bi-check2"></i></span>
                            <span class="habit-button-label">Buka</span>
                        </a>
                    <?php else: ?>
                        <a href=".?hal=detailsoalhabit&id=<?= $soal['id'] ?>" class="habit-button yellow"><span class="habit-button-label">Buka</span></a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if ($bacaan): ?>
                <div class="habit-card">
                    <p class="habit-title">Bacaan</p>
                    <?php if (in_array($bacaan['id'], $user_progress)): ?>
                        <a href=".?hal=detailbacaanhabit&id=<?= $bacaan['id'] ?>" class="habit-button yellow opened">
                            <span class="habit-check" aria-hidden="true"><i class="bi bi-check2"></i></span>
                            <span class="habit-button-label">Buka</span>
                        </a>
                    <?php else: ?>
                        <a href=".?hal=detailbacaanhabit&id=<?= $bacaan['id'] ?>" class="habit-button yellow"><span class="habit-button-label">Buka</span></a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
