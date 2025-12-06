<?php
include '../config/koneksi.php';
$habit_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$habit_data = null;
$soal_data = [];

if ($habit_id > 0) {
    // Ambil data utama habit
    $stmt_habit = mysqli_prepare($koneksi, "SELECT h.*, s.nama_subtest FROM habit_harian h JOIN subtest s ON h.subtest_id = s.id WHERE h.id = ? AND h.jenis = 'soal'");
    mysqli_stmt_bind_param($stmt_habit, "i", $habit_id);
    mysqli_stmt_execute($stmt_habit);
    $result_habit = mysqli_stmt_get_result($stmt_habit);
    $habit_data = mysqli_fetch_assoc($result_habit);

    // Ambil semua soal detail untuk habit ini
    $stmt_soal = mysqli_prepare($koneksi, "SELECT * FROM habit_soal WHERE habit_id = ? ORDER BY id ASC");
    mysqli_stmt_bind_param($stmt_soal, "i", $habit_id);
    mysqli_stmt_execute($stmt_soal);
    $result_soal = mysqli_stmt_get_result($stmt_soal);
    while ($row = mysqli_fetch_assoc($result_soal)) {
        $soal_data[] = $row;
    }
}

if (!$habit_data) {
    echo "<script>alert('Data habit soal tidak ditemukan.'); window.location.href='.?hal=habit';</script>";
    exit;
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Habit Harian</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=habit">Daftar Habit</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Edit Habit Soal</a></li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Soal Harian</h4>
                </div>
                <div class="card-body">
                    <form action=".?hal=proses_habit&aksi=edit_soal" method="POST">
                        <input type="hidden" name="habit_id" value="<?= $habit_id; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal" class="form-label">Tanggal Habit</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= htmlspecialchars($habit_data['tanggal']); ?>" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subtest_id">Pilih Subtest</label>
                                    <select class="form-select form-control" id="subtest_id" name="subtest_id" required>
                                        <option value="" disabled>-- Pilih Subtest --</option>
                                        <?php
                                        $query_subtest = "SELECT * FROM subtest ORDER BY nama_subtest ASC";
                                        $result_subtest = mysqli_query($koneksi, $query_subtest);
                                        while ($row_subtest = mysqli_fetch_assoc($result_subtest)) {
                                            $selected = ($row_subtest['id'] == $habit_data['subtest_id']) ? 'selected' : '';
                                            echo "<option value='{$row_subtest['id']}' $selected>" . htmlspecialchars($row_subtest['nama_subtest']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="judul_soal" class="form-label">Judul Soal</label>
                            <input type="text" class="form-control" id="judul_soal" name="judul_soal" placeholder="Contoh: Latihan Soal Penalaran Umum #1" value="<?= htmlspecialchars($habit_data['judul']); ?>" required />
                        </div>

                        <hr class="my-4">

                        <div id="questions-container">
                            <?php if (!empty($soal_data)): ?>
                                <?php foreach ($soal_data as $index => $soal): ?>
                                <div class="question-block">
                                    <h5 class="mb-3">Soal #<?= $index + 1; ?></h5>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-question-btn">Hapus Soal Ini</button>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Teks Pertanyaan</label>
                                        <textarea class="form-control" name="pertanyaan[]" rows="3" placeholder="Masukkan teks pertanyaan..." required><?= htmlspecialchars($soal['pertanyaan']); ?></textarea>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6 form-group"><input type="text" class="form-control" name="pilihan_a[]" placeholder="Pilihan A" value="<?= htmlspecialchars($soal['pilihan_a']); ?>" required></div>
                                        <div class="col-md-6 form-group"><input type="text" class="form-control" name="pilihan_b[]" placeholder="Pilihan B" value="<?= htmlspecialchars($soal['pilihan_b']); ?>" required></div>
                                        <div class="col-md-6 form-group"><input type="text" class="form-control" name="pilihan_c[]" placeholder="Pilihan C" value="<?= htmlspecialchars($soal['pilihan_c']); ?>" required></div>
                                        <div class="col-md-6 form-group"><input type="text" class="form-control" name="pilihan_d[]" placeholder="Pilihan D" value="<?= htmlspecialchars($soal['pilihan_d']); ?>" required></div>
                                        <div class="col-md-6 form-group"><input type="text" class="form-control" name="pilihan_e[]" placeholder="Pilihan E" value="<?= htmlspecialchars($soal['pilihan_e']); ?>" required></div>
                                        <div class="col-md-6 form-group">
                                            <select class="form-select" name="kunci_jawaban[]" required>
                                                <option value="" disabled>-- Kunci Jawaban --</option>
                                                <option value="A" <?= $soal['kunci_jawaban'] == 'A' ? 'selected' : ''; ?>>A</option>
                                                <option value="B" <?= $soal['kunci_jawaban'] == 'B' ? 'selected' : ''; ?>>B</option>
                                                <option value="C" <?= $soal['kunci_jawaban'] == 'C' ? 'selected' : ''; ?>>C</option>
                                                <option value="D" <?= $soal['kunci_jawaban'] == 'D' ? 'selected' : ''; ?>>D</option>
                                                <option value="E" <?= $soal['kunci_jawaban'] == 'E' ? 'selected' : ''; ?>>E</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label class="form-label">Pembahasan</label>
                                        <textarea class="form-control" name="pembahasan[]" rows="2" placeholder="Jelaskan mengapa jawaban tersebut benar..."><?= htmlspecialchars($soal['pembahasan']); ?></textarea>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <button type="button" id="add-question-btn" class="btn btn-outline-primary mt-3">
                            <i class="fa fa-plus"></i> Tambah Soal
                        </button>

                        <hr class="my-4">

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href=".?hal=habit" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .question-block { border: 1px solid #dee2e6; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1.5rem; position: relative; }
    .remove-question-btn { position: absolute; top: 10px; right: 10px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionsContainer = document.getElementById('questions-container');
    const addQuestionBtn = document.getElementById('add-question-btn');

    function updateQuestionNumbers() {
        const allBlocks = questionsContainer.querySelectorAll('.question-block');
        allBlocks.forEach((block, index) => {
            block.querySelector('h5').textContent = `Soal #${index + 1}`;
        });
    }

    addQuestionBtn.addEventListener('click', function() {
        const newBlockHTML = `
            <div class="question-block">
                <h5 class="mb-3">Soal Baru</h5>
                <button type="button" class="btn btn-sm btn-outline-danger remove-question-btn">Hapus Soal Ini</button>
                <div class="form-group mb-3">
                    <label class="form-label">Teks Pertanyaan</label>
                    <textarea class="form-control" name="pertanyaan[]" rows="3" placeholder="Masukkan teks pertanyaan..." required></textarea>
                </div>
                <div class="row g-3">
                    <div class="col-md-6 form-group"><input type="text" class="form-control" name="pilihan_a[]" placeholder="Pilihan A" required></div>
                    <div class="col-md-6 form-group"><input type="text" class="form-control" name="pilihan_b[]" placeholder="Pilihan B" required></div>
                    <div class="col-md-6 form-group"><input type="text" class="form-control" name="pilihan_c[]" placeholder="Pilihan C" required></div>
                    <div class="col-md-6 form-group"><input type="text" class="form-control" name="pilihan_d[]" placeholder="Pilihan D" required></div>
                    <div class="col-md-6 form-group"><input type="text" class="form-control" name="pilihan_e[]" placeholder="Pilihan E" required></div>
                    <div class="col-md-6 form-group">
                        <select class="form-select" name="kunci_jawaban[]" required>
                            <option value="" selected disabled>-- Kunci Jawaban --</option>
                            <option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option><option value="E">E</option>
                        </select>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label class="form-label">Pembahasan</label>
                    <textarea class="form-control" name="pembahasan[]" rows="2" placeholder="Jelaskan mengapa jawaban tersebut benar..."></textarea>
                </div>
            </div>`;
        questionsContainer.insertAdjacentHTML('beforeend', newBlockHTML);
        updateQuestionNumbers();
    });

    questionsContainer.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-question-btn')) {
            const allBlocks = questionsContainer.querySelectorAll('.question-block');
            if (allBlocks.length > 1) {
                e.target.closest('.question-block').remove();
                updateQuestionNumbers();
            } else {
                alert('Minimal harus ada satu soal dalam satu paket.');
            }
        }
    });

    // Inisialisasi nomor soal saat halaman dimuat
    updateQuestionNumbers();
});
</script>
