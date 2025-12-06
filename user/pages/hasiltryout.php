<?php
// Asumsi $koneksi sudah tersedia dari file utama (misalnya index.php)
// Pastikan user sudah login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true || !isset($_SESSION['user_id'])) {
    echo "<main class='dashboard-content'><p class='text-center text-danger'>Anda harus login untuk melihat hasil tryout.</p></main>";
    exit;
}

$session_id = isset($_GET['session_id']) ? intval($_GET['session_id']) : 0;
$result_data = null;
$tryout_name = "Tryout";
$subtest_name = "Subtest";

if ($session_id > 0) {
    $stmt = mysqli_prepare($koneksi, "
        SELECT uts.*, t.nama_tryout, s.nama_subtest 
        FROM user_tryout_sessions uts
        JOIN tryout t ON uts.tryout_id = t.id
        JOIN subtest s ON uts.subtest_id = s.id
        WHERE uts.id = ? AND uts.user_id = ?
    ");
    mysqli_stmt_bind_param($stmt, "ii", $session_id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $result_data = mysqli_fetch_assoc($result);

    if ($result_data) {
        $tryout_name = htmlspecialchars($result_data['nama_tryout']);
        $subtest_name = htmlspecialchars($result_data['nama_subtest']);
    } else {
        echo "<main class='dashboard-content'><p class='text-center text-danger'>Hasil tryout tidak ditemukan atau Anda tidak memiliki akses.</p></main>";
        exit;
    }
} else {
    echo "<main class='dashboard-content'><p class='text-center text-danger'>ID Sesi Tryout tidak valid.</p></main>";
    exit;
}
?>
<main class="dashboard-content">
    <div class="hasil-tryout-container">
        
        <div class="hasil-icon">
            <i class="bi bi-check-circle-fill text-success"></i>
        </div>
        
        <h1>Tryout Selesai! (<?= $tryout_name; ?> - <?= $subtest_name; ?>)</h1>
        <p>Berikut adalah hasil dari pengerjaan subtest Anda.</p>
        
        <div class="skor-final">
            <?= number_format($result_data['score'], 0); ?>
        </div>
        <p class="skor-label">Skor Total Anda</p>
        
        <div class="skor-summary-grid">
            <div class="skor-summary-item">
                <h3 class="text-success"><?= $result_data['correct_count']; ?></h3>
                <p><i class="bi bi-check-circle"></i> Benar</p>
            </div>
            <div class="skor-summary-item">
                <h3 class="text-danger"><?= $result_data['incorrect_count']; ?></h3>
                <p><i class="bi bi-x-circle"></i> Salah</p>
            </div>
            <div class="skor-summary-item">
                <h3 class="text-muted"><?= $result_data['unanswered_count']; ?></h3>
                <p><i class="bi bi-circle"></i> Kosong</p>
            </div>
        </div>
        
        <div class="hasil-actions">
            <a href=".?hal=tryout" class="btn-upgrade-item"><i class="bi bi-arrow-left"></i> Kembali ke Daftar Tryout</a>
        </div>
        
    </div>
</main>