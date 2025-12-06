

<?php
// --- LOGIKA TANGGAL INDONESIA ---
$hari = array("Sunday" => "Minggu", "Monday" => "Senin", "Tuesday" => "Selasa", "Wednesday" => "Rabu", "Thursday" => "Kamis", "Friday" => "Jumat", "Saturday" => "Sabtu");
$bulan = array("01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April", "05" => "Mei", "06" => "Juni", "07" => "Juli", "08" => "Agustus", "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember");

$tgl_sekarang = $hari[date("l")] . ", " . date("d") . " " . $bulan[date("m")] . " " . date("Y");

// --- LOGIKA DATA (Tetap Sama) ---
$query_materi = mysqli_query($koneksi, "SELECT COUNT(*) AS total_materi FROM materi");
$total_materi = mysqli_fetch_assoc($query_materi)['total_materi'];

$query_habit = mysqli_query($koneksi, "SELECT COUNT(*) AS total_habit FROM habit_harian");
$total_habit = mysqli_fetch_assoc($query_habit)['total_habit'];

$query_tryout = mysqli_query($koneksi, "SELECT COUNT(*) AS total_tryout FROM tryout");
$total_tryout = mysqli_fetch_assoc($query_tryout)['total_tryout'];

$query_short = mysqli_query($koneksi, "SELECT COUNT(*) AS total_short FROM shorts");
$total_short = mysqli_fetch_assoc($query_short)['total_short'];
?>

<div class="page-inner">
    <div class="d-flex align-items-center justify-content-between flex-wrap pt-2 pb-4 mb-3">
        <div>
            <?php $admin_name = htmlspecialchars($_SESSION['nama'] ?? 'Admin'); ?>
            <h2 class="fw-bold text-dark mb-1">Halo, <?= $admin_name; ?>!</h2>
            <p class="text-muted mb-0">Berikut adalah ringkasan data pembelajaran hari ini.</p>
        </div>
        <div class="mt-2 mt-md-0">
            <div class="date-badge">
                <i class="fas fa-calendar-alt text-primary"></i>
                <?= $tgl_sekarang; ?>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-sm-6 col-md-3">
            <div class="stats-card">
                <div class="card-body">
                    <div>
                        <h5 class="stats-title">Total Materi</h5>
                        <span class="stats-number"><?= $total_materi; ?></span>
                    </div>
                    <div class="icon-shape bg-gradient-blue">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
                <a href=".?hal=materi" class="stats-link">
                    Lihat Detail <i class="fas fa-arrow-right float-end mt-1"></i>
                </a>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="stats-card">
                <div class="card-body">
                    <div>
                        <h5 class="stats-title">Total Habit</h5>
                        <span class="stats-number"><?= $total_habit; ?></span>
                    </div>
                    <div class="icon-shape bg-gradient-cyan">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <a href=".?hal=habit" class="stats-link">
                    Lihat Detail <i class="fas fa-arrow-right float-end mt-1"></i>
                </a>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="stats-card">
                <div class="card-body">
                    <div>
                        <h5 class="stats-title">Total Tryout</h5>
                        <span class="stats-number"><?= $total_tryout; ?></span>
                    </div>
                    <div class="icon-shape bg-gradient-green">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                </div>
                <a href=".?hal=tryout" class="stats-link">
                    Lihat Detail <i class="fas fa-arrow-right float-end mt-1"></i>
                </a>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="stats-card">
                <div class="card-body">
                    <div>
                        <h5 class="stats-title">Total Short</h5>
                        <span class="stats-number"><?= $total_short; ?></span>
                    </div>
                    <div class="icon-shape bg-gradient-purple">
                        <i class="fas fa-video"></i>
                    </div>
                </div>
                <a href=".?hal=short" class="stats-link">
                    Lihat Detail <i class="fas fa-arrow-right float-end mt-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>