<?php
include '../config/koneksi.php';
$id_tryout = isset($_GET['id_tryout']) ? intval($_GET['id_tryout']) : 0;
$subtest_id = isset($_GET['subtest_id']) ? intval($_GET['subtest_id']) : 0;
$tryout_data = null;
$subtest_data = null;
$soal_data = [];
$waktu_pengerjaan = 0;
$konteks_soal = '';

if ($id_tryout > 0 && $subtest_id > 0) {
    // Ambil nama tryout
    $stmt_tryout = mysqli_prepare($koneksi, "SELECT nama_tryout FROM tryout WHERE id = ?");
    mysqli_stmt_bind_param($stmt_tryout, "i", $id_tryout);
    mysqli_stmt_execute($stmt_tryout);
    $result_tryout = mysqli_stmt_get_result($stmt_tryout);
    $tryout_data = mysqli_fetch_assoc($result_tryout);

    // Ambil nama subtest
    $stmt_subtest = mysqli_prepare($koneksi, "SELECT nama_subtest FROM subtest WHERE id = ?");
    mysqli_stmt_bind_param($stmt_subtest, "i", $subtest_id);
    mysqli_stmt_execute($stmt_subtest);
    $result_subtest = mysqli_stmt_get_result($stmt_subtest);
    $subtest_data = mysqli_fetch_assoc($result_subtest);

    // Ambil semua soal untuk tryout dan subtest ini
    $stmt_soal = mysqli_prepare($koneksi, "SELECT * FROM soal_tryout WHERE tryout_id = ? AND subtest_id = ? ORDER BY id ASC");
    mysqli_stmt_bind_param($stmt_soal, "ii", $id_tryout, $subtest_id);
    mysqli_stmt_execute($stmt_soal);
    $result_soal = mysqli_stmt_get_result($stmt_soal);
    while ($row = mysqli_fetch_assoc($result_soal)) {
        $soal_data[] = $row;
    }
    if (!empty($soal_data)) {
        $konteks_soal = $soal_data[0]['konteks_soal'];
    }

    // Ambil waktu pengerjaan
    $stmt_waktu = mysqli_prepare($koneksi, "SELECT waktu_pengerjaan FROM tryout_subtest WHERE tryout_id = ? AND subtest_id = ?");
    mysqli_stmt_bind_param($stmt_waktu, "ii", $id_tryout, $subtest_id);
    mysqli_stmt_execute($stmt_waktu);
    if ($waktu_row = mysqli_stmt_get_result($stmt_waktu)->fetch_assoc()) {
        $waktu_pengerjaan = $waktu_row['waktu_pengerjaan'];
    }
}

if (!$tryout_data || !$subtest_data) {
    echo "<script>alert('Data tryout atau subtest tidak ditemukan.'); window.location.href='.?hal=tryout';</script>";
    exit;
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Soal Tryout</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=soaltryout&id_tryout=<?= $id_tryout; ?>">Daftar Soal</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Edit Soal</a></li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Soal</h4>
                </div>
                <div class="card-body">
                    <form action=".?hal=proses_soal_tryout&aksi=edit_grup" method="POST">

                        <input type="hidden" name="id_tryout" value="<?= $id_tryout; ?>">
                        <input type="hidden" name="subtest_id" value="<?= $subtest_id; ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Nama Tryout</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($tryout_data['nama_tryout']); ?>" readonly />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Subtest</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($subtest_data['nama_subtest']); ?>" readonly />
                                </div>
                                <div class="form-group mt-3">
                                    <label for="waktu_pengerjaan">Waktu Pengerjaan (Menit)</label>
                                    <input type="number" class="form-control" id="waktu_pengerjaan" name="waktu_pengerjaan"
                                        placeholder="Contoh: 20" value="<?= $waktu_pengerjaan; ?>" required>
                                    <small class="form-text text-muted">Durasi pengerjaan untuk subtest ini.</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="konteks_soal" class="form-label">Konteks Soal / Teks Bacaan (Opsional)</label>
                            <textarea class="form-control" id="konteks_soal" name="konteks_soal" rows="4" placeholder="Masukkan teks bacaan di sini jika soal memerlukan konteks (Contoh: Gunakan teks ini untuk soal no 1-3)"><?= htmlspecialchars($konteks_soal); ?></textarea>
                        </div>

                        <hr class="my-4">

                        <div id="questions-container">
                            <?php if (!empty($soal_data)): ?>
                                <?php foreach ($soal_data as $index => $soal): ?>
                            <div class="question-block">
                                <h5 class="mb-3">Soal #<?= $index + 1; ?></h5>
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger remove-question-btn">Hapus Soal
                                    Ini</button>
                                <div class="form-group mb-3">
                                    <label for="pertanyaan_1" class="form-label">Teks Pertanyaan</label>
                                    <textarea class="form-control" name="pertanyaan[]" rows="3" placeholder="Masukkan teks pertanyaan..." required><?= htmlspecialchars($soal['pertanyaan']); ?></textarea>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" name="pilihan_a[]" placeholder="Pilihan A" value="<?= htmlspecialchars($soal['pilihan_a']); ?>" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" name="pilihan_b[]" placeholder="Pilihan B" value="<?= htmlspecialchars($soal['pilihan_b']); ?>" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" name="pilihan_c[]" placeholder="Pilihan C" value="<?= htmlspecialchars($soal['pilihan_c']); ?>" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" name="pilihan_d[]" placeholder="Pilihan D" value="<?= htmlspecialchars($soal['pilihan_d']); ?>" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" name="pilihan_e[]" placeholder="Pilihan E" value="<?= htmlspecialchars($soal['pilihan_e']); ?>" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <select class="form-select" name="kunci_jawaban[]" required>
                                            <option value="" selected disabled>-- Kunci Jawaban --</option>
                                            <option value="A" <?= $soal['kunci_jawaban'] == 'A' ? 'selected' : ''; ?>>A</option>
                                            <option value="B" <?= $soal['kunci_jawaban'] == 'B' ? 'selected' : ''; ?>>B</option>
                                            <option value="C" <?= $soal['kunci_jawaban'] == 'C' ? 'selected' : ''; ?>>C</option>
                                            <option value="D" <?= $soal['kunci_jawaban'] == 'D' ? 'selected' : ''; ?>>D</option>
                                            <option value="E" <?= $soal['kunci_jawaban'] == 'E' ? 'selected' : ''; ?>>E</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label class="form-label">Pembahasan (Opsional)</label>
                                    <textarea class="form-control" name="pembahasan[]" rows="2" placeholder="Jelaskan mengapa jawaban tersebut benar..."><?= htmlspecialchars($soal['pembahasan']); ?></textarea>
                                </div>
                            </div>
                                <?php endforeach; ?>
                            <?php else: // Jika tidak ada soal, tampilkan satu blok kosong ?>
                                <div class="question-block">
                                    <h5 class="mb-3">Soal #1</h5>
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
                                        <label class="form-label">Pembahasan (Opsional)</label>
                                        <textarea class="form-control" name="pembahasan[]" rows="2" placeholder="Jelaskan mengapa jawaban tersebut benar..."></textarea>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <button type="button" id="add-question-btn" class="btn btn-outline-primary mt-3">
                            <i class="fa fa-plus"></i> Tambah Soal
                        </button>

                        <hr class="my-4">

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href=".?hal=soaltryout&id_tryout=<?= $id_tryout; ?>" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .question-block {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .remove-question-btn {
        position: absolute;
        top: 10px;
        right: 10px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const questionsContainer = document.getElementById('questions-container');
        const addQuestionBtn = document.getElementById('add-question-btn');

        // Fungsi untuk memperbarui nomor soal
        function updateQuestionNumbers() {
            const allBlocks = questionsContainer.querySelectorAll('.question-block');
            allBlocks.forEach((block, index) => {
                block.querySelector('h5').textContent = `Soal #${index + 1}`;
            });
            return allBlocks.length;
        }

        // Event listener untuk tombol 'Tambah Soal' (menambahkan blok baru)
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
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label">Pembahasan (Opsional)</label>
                        <textarea class="form-control" name="pembahasan[]" rows="2" placeholder="Jelaskan mengapa jawaban tersebut benar..."></textarea>
                    </div>
                </div>`;
            questionsContainer.insertAdjacentHTML('beforeend', newBlockHTML);
            updateQuestionNumbers(); // Perbarui nomor soal setelah penambahan
        });

        // Event listener untuk tombol 'Hapus Soal Ini' (menggunakan event delegation)
        questionsContainer.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-question-btn')) {
                const allBlocks = questionsContainer.querySelectorAll('.question-block');
                // Mencegah menghapus soal terakhir
                if (allBlocks.length > 1) {
                    e.target.closest('.question-block').remove(); // Hapus blok soal
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