<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Habit Harian</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=habit">Daftar Habit</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Tambah Habit</a></li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Soal Harian</h4>
                </div>
                <div class="card-body">
                    <form action="proses_simpan_paket_soal.php" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal" class="form-label">Tanggal Habit</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subtest">Pilih Subtest</label>
                                    <select class="form-select form-control" id="subtest" name="subtest" required>
                                        <option value="" disabled selected>-- Pilih Subtest --</option>
                                        <option value="1">Penalaran Umum</option>
                                        <option value="2">Pemahaman Bacaan dan Menulis</option>
                                        <option value="3">Pengetahuan dan Pemahaman Umum</option>
                                        <option value="4">Literasi Bahasa Inggris</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="judul_bacaan" class="form-label">Judul Soal</label>
                            <input type="text" class="form-control" id="judul_bacaan" name="judul_bacaan"
                                placeholder="Contoh: Peran Energi Geotermal dalam Pembangunan Berkelanjutan" required />
                        </div>

                        <hr class="my-4">

                        <div id="questions-container">
                            <div class="question-block">
                                <h5 class="mb-3">Soal #1</h5>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-question-btn">Hapus
                                    Soal
                                    Ini</button>
                                <div class="form-group mb-3">
                                    <label for="pertanyaan_1" class="form-label">Teks Pertanyaan</label>
                                    <textarea class="form-control" name="pertanyaan[]" rows="3"
                                        placeholder="Masukkan teks pertanyaan, bisa termasuk cuplikan teks..." required></textarea>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" name="pilihan_a[]"
                                            placeholder="Pilihan A" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" name="pilihan_b[]"
                                            placeholder="Pilihan B" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" name="pilihan_c[]"
                                            placeholder="Pilihan C" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" name="pilihan_d[]"
                                            placeholder="Pilihan D" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" name="pilihan_e[]"
                                            placeholder="Pilihan E" required>
                                    </div>
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
                                    <label class="form-label">Pembahasan</label>
                                    <textarea class="form-control" name="pembahasan[]" rows="2"
                                        placeholder="Jelaskan mengapa jawaban tersebut benar..."></textarea>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="add-question-btn" class="btn btn-outline-primary mt-3">
                            <i class="fa fa-plus"></i> Tambah Soal
                        </button>

                        <hr class="my-4">

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href=".?hal=habit" class="btn btn-secondary">Batal</a>
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
        let questionCounter = 1;

        // Fungsi untuk memperbarui nomor soal
        function updateQuestionNumbers() {
            const allBlocks = questionsContainer.querySelectorAll('.question-block');
            allBlocks.forEach((block, index) => {
                block.querySelector('h5').textContent = `Soal #${index + 1}`;
            });
        }

        // Event listener untuk tombol 'Tambah Soal'
        addQuestionBtn.addEventListener('click', function() {
            questionCounter++;
            const firstBlock = questionsContainer.querySelector('.question-block');
            const newBlock = firstBlock.cloneNode(true); // Duplikasi blok pertama

            // Kosongkan nilai input di blok baru
            newBlock.querySelectorAll('input, textarea, select').forEach(input => {
                if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                } else {
                    input.value = '';
                }
            });

            questionsContainer.appendChild(newBlock); // Tambahkan blok baru ke kontainer
            updateQuestionNumbers();
        });

        // Event listener untuk tombol 'Hapus Soal Ini' (menggunakan event delegation)
        questionsContainer.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-question-btn')) {
                const allBlocks = questionsContainer.querySelectorAll('.question-block');
                // Mencegah menghapus soal terakhir
                if (allBlocks.length > 1) {
                    e.target.closest('.question-block').remove();
                    updateQuestionNumbers();
                } else {
                    alert('Minimal harus ada satu soal dalam satu paket.');
                }
            }
        });
    });
</script>
