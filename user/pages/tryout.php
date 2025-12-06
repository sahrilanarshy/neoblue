<?php
// Mulai sesi jika belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil data dari database
$query = mysqli_query($koneksi, "SELECT id, nama_tryout, tanggal_mulai, tanggal_selesai, tipe FROM tryout ORDER BY tanggal_mulai DESC");
$all_tryouts = mysqli_fetch_all($query, MYSQLI_ASSOC);

// Dapatkan data pengguna
$is_premium = (isset($_SESSION['tipe_user']) && strtolower($_SESSION['tipe_user']) == 'premium');
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$today = date('Y-m-d');

// Ambil riwayat pengerjaan subtest oleh user untuk menentukan status tombol
$completed_subtests = [];
if ($user_id > 0) {
    $stmt_history = mysqli_prepare($koneksi, "SELECT tryout_id, subtest_id, id as session_id FROM user_tryout_sessions WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt_history, "i", $user_id);
    mysqli_stmt_execute($stmt_history);
    $result_history = mysqli_stmt_get_result($stmt_history);
    while ($row = mysqli_fetch_assoc($result_history)) {
        $completed_subtests[$row['tryout_id']][] = [
            'subtest_id' => $row['subtest_id'],
            'session_id' => $row['session_id']
        ];
    }
}

// Fungsi untuk membuat tombol aksi
function getActionButton($tryout, $is_premium, $today, $completed_subtests) {
    $is_upcoming = $today < $tryout['tanggal_mulai'];
    $is_active = $today >= $tryout['tanggal_mulai'] && $today <= $tryout['tanggal_selesai'];
    $is_finished = $today > $tryout['tanggal_selesai'];
    $is_locked = ($tryout['tipe'] == 'Premium' && !$is_premium);

    if ($is_locked) {
        return '<a href=".?hal=premium" class="btn-tryout premium">Upgrade Premium</a>';
    }

    // Jika tryout sedang aktif, prioritaskan tombol untuk mengerjakan sekarang
    if ($is_active) {
        return '<a href=".?hal=detailtryout&id=' . $tryout['id'] . '" class="btn-tryout aktif">Kerjakan Sekarang</a>';
    }

    // Cek apakah user sudah pernah mengerjakan tryout ini (minimal satu subtest)
    if (isset($completed_subtests[$tryout['id']])) {
        // Arahkan ke detail tryout, di sana akan ada tombol untuk melihat hasil per subtest
        return '<a href=".?hal=detailtryout&id=' . $tryout['id'] . '" class="btn-tryout selesai lihat-hasil">Lihat Hasil</a>';
    }

    if ($is_upcoming) {
        return '<a href="#" class="btn-tryout coming">Segera Dimulai</a>';
    }

    if ($is_finished) {
        return '<a href="#" class="btn-tryout selesai">Telah Berakhir</a>';
    }

    // Default button jika tidak ada kondisi yang cocok
    return '<a href="#" class="btn-tryout coming">Info</a>';
}

// Cari tryout terbaru yang bisa dikerjakan
$latest_tryout = null;
foreach ($all_tryouts as $tryout) {
    $is_locked = ($tryout['tipe'] == 'Premium' && !$is_premium);
    $is_active = ($today >= $tryout['tanggal_mulai'] && $today <= $tryout['tanggal_selesai']);
    if (!$is_locked && $is_active) {
        $latest_tryout = $tryout;
        break; // Ambil yang pertama ditemukan (yang terbaru karena sudah diurut DESC)
    }
}
?>
<style>
/* Yellow style for 'Lihat Hasil' tryout buttons (match premium upgrade) */
.btn-tryout.lihat-hasil{
    background: #ffc107;
    color: #212529;
    border: 1px solid #ffc107;
}
.btn-tryout.lihat-hasil:hover{ background: #e0a800; color: #212529; }
/* Red style for finished tryouts */
.btn-tryout.selesai{
    background: #F44336;
    color: #ffffff;
    border: 1px solid #F44336;
}
.btn-tryout.selesai:hover{ background: #d32f2f; color: #ffffff; }
</style>
<main class="dashboard-content">
    <section class="tryout-section">
        <?php if ($latest_tryout): ?>
        <div class="tryout-group">
            <h3 class="tryout-heading">Tryout Terbaru</h3>
            <div class="tryout-box">
                <div class="tryout-item">
                    <div class="tryout-left">
                        <div class="tryout-icon">
                            <i class="fa-regular fa-file-lines"></i>
                        </div>
                        <div class="tryout-info">
                            <p class="tryout-title"><?= htmlspecialchars($latest_tryout['nama_tryout']); ?></p>
                            <p class="tryout-date">Berakhir pada <?= date('d/m/Y', strtotime($latest_tryout['tanggal_selesai'])); ?></p>
                        </div>
                    </div>
                    <?= getActionButton($latest_tryout, $is_premium, $today, $completed_subtests); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="tryout-group">
            <h3 class="tryout-heading">Semua Tryout</h3>
            <div class="tryout-box">
                <?php if (empty($all_tryouts)): ?>
                    <p class="text-center text-muted" style="width: 100%;">Belum ada tryout yang tersedia.</p>
                <?php else: ?>
                    <?php
                    // Urutkan kembali berdasarkan tanggal mulai untuk tampilan "Semua Tryout"
                    usort($all_tryouts, function($a, $b) {
                        return strtotime($a['tanggal_mulai']) - strtotime($b['tanggal_mulai']);
                    });
                    ?>
                    <?php foreach ($all_tryouts as $tryout): ?>
                        <div class="tryout-item">
                            <div class="tryout-left">
                                <div class="tryout-icon">
                                    <i class="fa-regular fa-file-lines"></i>
                                </div>
                                <div class="tryout-info">
                                    <p class="tryout-title"><?= htmlspecialchars($tryout['nama_tryout']); ?></p>
                                    <p class="tryout-date">Mulai Tanggal <?= date('d/m/Y', strtotime($tryout['tanggal_mulai'])); ?></p>
                                </div>
                            </div>
                            <?= getActionButton($tryout, $is_premium, $today, $completed_subtests); ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
