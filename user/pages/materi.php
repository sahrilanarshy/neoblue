<?php
// 1. Ambil data pengguna dari sesi
$nama_user = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Pengguna';
$tipe_user = isset($_SESSION['tipe_user']) ? $_SESSION['tipe_user'] : 'Gratis';

// 2. Tentukan status premium dan gambar banner
$is_premium = (strtolower($tipe_user) == 'premium');
$banner_image = $is_premium ? 'premium.png' : 'free.png';
$banner_alt = $is_premium ? 'Selamat menikmati fitur Premium!' : 'Upgrade Premium Sekarang!';

?>
<main class="dashboard-content">
    <?php if ($is_premium): ?>
        <section class="premium-banner">
            <img src="../assets/user/img/premium.png" alt="<?= htmlspecialchars($banner_alt); ?>">
        </section>
    <?php else: ?>
        <section class="premium-banner">
            <a href=".?hal=premium">
                <img src="../assets/user/img/free.png" alt="<?= htmlspecialchars($banner_alt); ?>">
            </a>
        </section>
    <?php endif; ?>
    <section class="welcome-header">
        <div class="welcome-text">
            <h1>Selamat Datang, <?= htmlspecialchars($nama_user); ?>!</h1>
            <div class="user-status">
                <span class="status-badge"><?= $is_premium ? 'Premium' : 'Gratis'; ?></span>
            </div>
        </div>
        <?php if (!$is_premium): ?>
            <div class="action-button">
                <a href=".?hal=premium" class="btn-upgrade">Upgrade Premium</a>
            </div>
        <?php endif; ?>
    </section>

    <section class="main-menu">
        <a href=".?hal=materi" class="menu-card">
            <i class="bi bi-file-earmark-text"></i>
            <h2>Materi Belajar</h2>
            <p>Akses semua materi pembelajaran</p>
        </a>

        <a href=".?hal=jadwal" class="menu-card">
            <i class="bi bi-calendar-event"></i>
            <h2>Jadwal Belajar</h2>
            <p>Atur jadwal belajar harianmu</p>
        </a>

        <a href=".?hal=habit" class="menu-card">
            <i class="bi bi-calendar-check"></i>
            <h2>Habit Harian</h2>
            <p>Bangun kebiasaan belajar positif</p>
        </a>

        <a href=".?hal=tryout" class="menu-card">
            <i class="bi bi-clipboard"></i>
            <h2>Tryout</h2>
            <p>Simulasi Ujian UTBK</p>
        </a>
    </section>



<?php
// Ambil data subtest dari database
// Variabel $koneksi sudah tersedia dari index.php
$query_subtest = mysqli_query($koneksi, "SELECT id, nama_subtest, singkatan FROM subtest ORDER BY id ASC");
$subtests = mysqli_fetch_all($query_subtest, MYSQLI_ASSOC);
?>
    <section class="learning-materials">
        <h2>Materi Pembelajaran</h2>
        <div class="materials-grid">
            <?php if (empty($subtests)): ?>
                <p class="text-center text-muted">Belum ada materi yang tersedia.</p>
            <?php else: ?>
                <?php foreach ($subtests as $subtest): ?>
                    <div class="material-card">
                        <h3><?= htmlspecialchars($subtest['singkatan']); ?></h3>
                        <p><?= htmlspecialchars($subtest['nama_subtest']); ?></p>
                        <a href=".?hal=materisubtest&id=<?= $subtest['id']; ?>" class="btn-start-learning">Mulai Belajar</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>
