<?php
// 1. Ambil ID habit dari URL dan validasi
$habit_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($habit_id <= 0) {
    echo "<p class='text-center text-danger'>ID Habit tidak valid.</p>";
    return;
}

// 2. Ambil data habit utama
$stmt_habit = mysqli_prepare($koneksi, "SELECT hh.tanggal, hh.judul, s.nama_subtest FROM habit_harian hh JOIN subtest s ON hh.subtest_id = s.id WHERE hh.id = ? AND hh.jenis = 'soal'");
mysqli_stmt_bind_param($stmt_habit, "i", $habit_id);
mysqli_stmt_execute($stmt_habit);
$result_habit = mysqli_stmt_get_result($stmt_habit);
$habit = mysqli_fetch_assoc($result_habit);

if (!$habit) {
    echo "<p class='text-center text-danger'>Habit soal tidak ditemukan.</p>";
    return;
}

// Catat bahwa user telah membuka habit ini (session-based tracker)
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['opened_habits']) || !is_array($_SESSION['opened_habits'])) $_SESSION['opened_habits'] = [];
if (!in_array($habit_id, $_SESSION['opened_habits'])) {
    $_SESSION['opened_habits'][] = $habit_id;
}

// 3. Ambil semua soal yang terkait dengan habit ini
$stmt_soal = mysqli_prepare($koneksi, "SELECT * FROM habit_soal WHERE habit_id = ? ORDER BY id ASC");
mysqli_stmt_bind_param($stmt_soal, "i", $habit_id);
mysqli_stmt_execute($stmt_soal);
$result_soal = mysqli_stmt_get_result($stmt_soal);
$soal_list = mysqli_fetch_all($result_soal, MYSQLI_ASSOC);

$date_obj = date_create($habit['tanggal']);
?>
<main class="dashboard-content">
    <div class="habit-detail-container">
        <header class="habit-detail-header">
            <h1><?= htmlspecialchars($habit['judul']); ?></h1>
            <p><?= date_format($date_obj, 'l, d F Y'); ?> - <?= htmlspecialchars($habit['nama_subtest']); ?></p>
        </header>

        <div class="question-list">
            <?php if (empty($soal_list)): ?>
                <p class="text-center text-muted">Belum ada soal untuk habit ini.</p>
            <?php else: ?>
                <?php foreach ($soal_list as $index => $soal): ?>
                    <div class="question-item">
                        <p class="question-text">
                            <?= $index + 1; ?>. <?= nl2br(htmlspecialchars($soal['pertanyaan'])); ?>
                        </p>
                        <ul class="answer-options">
                            <li>A. <?= htmlspecialchars($soal['pilihan_a']); ?></li>
                            <li>B. <?= htmlspecialchars($soal['pilihan_b']); ?></li>
                            <li>C. <?= htmlspecialchars($soal['pilihan_c']); ?></li>
                            <li>D. <?= htmlspecialchars($soal['pilihan_d']); ?></li>
                            <li>E. <?= htmlspecialchars($soal['pilihan_e']); ?></li>
                        </ul>
                        <a href="#" class="show-answer-link" data-answer="Jawaban: <?= $soal['kunci_jawaban']; ?>" data-pembahasan="<?= htmlspecialchars($soal['pembahasan']); ?>">Tampilkan Jawaban</a>
                        <div class="answer-explanation" style="display:none; margin-top: 10px; padding: 10px; background-color: #f0f8ff; border-radius: 5px;"></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const answerLinks = document.querySelectorAll('.show-answer-link');
    answerLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const explanationDiv = this.nextElementSibling;
            const jawaban = this.getAttribute('data-answer');
            const pembahasan = this.getAttribute('data-pembahasan');
            
            if (explanationDiv.style.display === 'none') {
                let content = `<strong>${jawaban}</strong>`;
                if (pembahasan) {
                    content += `<br><br><strong>Pembahasan:</strong><br>${pembahasan.replace(/\n/g, '<br>')}`;
                }
                explanationDiv.innerHTML = content;
                explanationDiv.style.display = 'block';
                this.textContent = 'Sembunyikan Jawaban';
            } else {
                explanationDiv.style.display = 'none';
                this.textContent = 'Tampilkan Jawaban';
            }
        });
    });
});
</script>