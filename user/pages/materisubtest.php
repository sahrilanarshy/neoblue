<?php
// 1. Ambil ID subtest dari URL dan validasi
$subtest_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($subtest_id <= 0) {
    echo "<p class='text-center text-danger'>ID Subtest tidak valid.</p>";
    return; // Hentikan eksekusi jika ID tidak valid
}

// 2. Ambil data subtest untuk judul halaman
$stmt_subtest = mysqli_prepare($koneksi, "SELECT nama_subtest, singkatan FROM subtest WHERE id = ?");
mysqli_stmt_bind_param($stmt_subtest, "i", $subtest_id);
mysqli_stmt_execute($stmt_subtest);
$result_subtest = mysqli_stmt_get_result($stmt_subtest);
$subtest = mysqli_fetch_assoc($result_subtest);

if (!$subtest) {
    echo "<p class='text-center text-danger'>Subtest tidak ditemukan.</p>";
    return;
}

// 3. Ambil semua materi yang terkait dengan subtest ini
$stmt_materi = mysqli_prepare($koneksi, "SELECT id, judul, tipe FROM materi WHERE subtest_id = ? ORDER BY id ASC");
mysqli_stmt_bind_param($stmt_materi, "i", $subtest_id);
mysqli_stmt_execute($stmt_materi);
$result_materi = mysqli_stmt_get_result($stmt_materi);
$materi_list = mysqli_fetch_all($result_materi, MYSQLI_ASSOC);

// 4. Dapatkan status langganan pengguna dari sesi
$is_premium = (isset($_SESSION['tipe_user']) && strtolower($_SESSION['tipe_user']) == 'premium');

?>
<div class="dashboard-content">
    <header class="materi-header">
        <a href=".?hal=materi" class="kembali-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Kembali</span>
        </a>
        <h1><?= htmlspecialchars($subtest['nama_subtest']); ?> (<?= htmlspecialchars($subtest['singkatan']); ?>)</h1>
    </header>

    <main class="daftar-materi-container">
        <h2>Daftar Materi</h2>

        <?php if (empty($materi_list)): ?>
            <p class="text-center text-muted">Belum ada materi untuk subtest ini.</p>
        <?php else: ?>
            <div class="materi-list">
                <?php foreach ($materi_list as $materi): ?>
                    <?php
                    $is_locked = ($materi['tipe'] == 'Premium' && !$is_premium);
                    ?>
                    <div class="materi-item <?= $is_locked ? 'locked' : 'free'; ?>">
                        <div class="materi-info">
                            <div class="icon-wrapper <?= $is_locked ? 'lock' : 'book'; ?>">
                                <?php if ($is_locked): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <p class="materi-title"><?= htmlspecialchars($materi['judul']); ?></p>
                        </div>
                        <div class="materi-actions">
                            <?php if ($is_locked): ?>
                                <span class="badge badge-premium">Premium</span>
                                <a href=".?hal=premium" class="btn-upgrade-item">Upgrade Premium</a>
                            <?php else: ?>
                                <?php if (!$is_premium): ?>
                                    <span class="badge badge-gratis">Gratis</span>
                                <?php endif; ?>
                                <a href=".?hal=detailmateri&id=<?= $materi['id']; ?>" class="btn-mulai">Mulai</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php if (!$is_premium): ?>
        <section class="cta-banner">
            <h2>Ingin Akses Semua Materi?</h2>
            <p>Upgrade ke premium dan dapatkan akses ke semua topik pembelajaran.</p>
            <a href=".?hal=premium" class="btn-upgrade-cta">
                Upgrade ke Premium
            </a>
        </section>
    <?php else: ?>
        <section class="cta-banner premium-active">
            <h2>Anda Adalah Anggota Premium!</h2>
            <p>Nikmati akses tanpa batas ke semua materi dan fitur eksklusif kami.</p>
        </section>
    <?php endif; ?>
</div>
