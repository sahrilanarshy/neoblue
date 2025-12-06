<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_tryout = isset($_GET['id_tryout']) ? intval($_GET['id_tryout']) : 0;
$subtest_id = isset($_GET['subtest_id']) ? intval($_GET['subtest_id']) : 0;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if ($id_tryout <= 0 || $subtest_id <= 0 || $user_id <= 0) {
    echo "<main class='dashboard-content'><p class='text-center text-danger'>Parameter tidak lengkap.</p></main>";
    return;
}

// SERVER-SIDE CHECK: Pastikan user belum pernah mengerjakan subtest ini
$stmt_check = mysqli_prepare($koneksi, "SELECT id FROM user_tryout_sessions WHERE user_id = ? AND tryout_id = ? AND subtest_id = ?");
mysqli_stmt_bind_param($stmt_check, "iii", $user_id, $id_tryout, $subtest_id);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);
if (mysqli_num_rows($result_check) > 0) {
    $session = mysqli_fetch_assoc($result_check);
    $session_id = $session['id'];
    // Jika sudah ada, redirect ke halaman hasil
    echo "<script>
            alert('Anda sudah pernah mengerjakan subtest ini. Anda akan diarahkan ke halaman hasil.');
            window.location.href = '.?hal=hasiltryout&session_id={$session_id}';
          </script>";
    exit;
}

?>
<main class="dashboard-content">
    <section>
        <div class="container">
            <div class="card" style="background-color:white; border:none; border-radius:10px;">
                <div class="card-body">
                    <!-- Header: Nama Subtest dan Timer -->
                    <div class="d-flex justify-content-between align-items-center" style="margin-bottom:8px;">
                        <h5 id="subtestName" class="fw-bold" style="font-size:14px; margin-bottom:0;">Memuat...</h5>
                        <div class="d-flex align-items-center" style="gap:8px;">
                            <div id="timer" class="timer-box">00:00</div>
                            <button id="finishBtn" type="button" class="timer-box-btn" style="display:none;">Selesai Ngerjakan</button>
                        </div>
                    </div>

                    <!-- Navigasi Nomor Soal -->
                    <div class="question-numbers" id="questionNumbers"></div>

                    <!-- Kontainer Soal dan Jawaban -->
                    <div id="questionDisplay" class="mt-3">
                        <div id="questionContext"></div>
                        <div id="questionText"></div>
                        <div id="answerOptions"></div>
                    </div>

                    <!-- Navigasi Sebelumnya/Selanjutnya -->
                    <div class="d-flex justify-content-between mt-3">
                        <div class="navigation d-flex" style="justify-content:flex-start;">
                            <button id="prevBtn" class="btn btn-outline-primary"><i class="bi bi-caret-left-fill"></i> Sebelumnya</button>
                        </div>
                        <div class="navigation d-flex" style="justify-content:flex-end;">
                            <button id="nextBtn" class="btn btn-primary">Selanjutnya <i class="bi bi-caret-right-fill"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const id_tryout = <?= $id_tryout; ?>;
        const subtest_id = <?= $subtest_id; ?>;

        const subtestNameEl = document.getElementById('subtestName');
        const timerEl = document.getElementById('timer');
        const questionNumbersEl = document.getElementById('questionNumbers');
        const questionContextEl = document.getElementById('questionContext');
        const questionTextEl = document.getElementById('questionText');
        const answerOptionsEl = document.getElementById('answerOptions');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const finishBtn = document.getElementById('finishBtn');

        let questions = [];
        let userAnswers = {};
        let currentQuestionIndex = 0;
        let timerInterval;

        // Fungsi untuk memuat data dari API
        async function loadTestData() {
            try {
                const response = await fetch(`../api/api_get_soal_test.php?id_tryout=${id_tryout}&subtest_id=${subtest_id}`);
                if (!response.ok) {
                    throw new Error('Gagal memuat data soal.');
                }
                const data = await response.json();
                if (data.status === 'success') {
                    questions = data.questions;
                    subtestNameEl.textContent = data.subtest_name;
                    initializeTest(data.waktu_pengerjaan);
                } else {
                    throw new Error(data.message || 'Data soal tidak ditemukan.');
                }
            } catch (error) {
                document.querySelector('.card-body').innerHTML = `<p class="text-center text-danger">${error.message}</p>`;
            }
        }

        // Fungsi untuk inisialisasi tes
        function initializeTest(waktuMenit) {
            if (questions.length === 0) {
                document.querySelector('.card-body').innerHTML = `<p class="text-center text-muted">Tidak ada soal untuk subtest ini.</p>`;
                return;
            }
            
            // Inisialisasi jawaban user
            questions.forEach((q, index) => {
                userAnswers[index] = null;
            });

            renderQuestionNumbers();
            displayQuestion(currentQuestionIndex);
            startTimer(waktuMenit * 60);
            checkAllAnswered();
        }

        // Fungsi untuk render navigasi nomor soal
        function renderQuestionNumbers() {
            questionNumbersEl.innerHTML = '';
            questions.forEach((_, index) => {
                const numberEl = document.createElement('div');
                numberEl.classList.add('question-number');
                numberEl.textContent = index + 1;
                if (index === currentQuestionIndex) {
                    numberEl.classList.add('active');
                }
                if (userAnswers[index] !== null) {
                    numberEl.classList.add('answered');
                }
                numberEl.addEventListener('click', () => {
                    displayQuestion(index);
                });
                questionNumbersEl.appendChild(numberEl);
            });
        }

        // Fungsi untuk menampilkan soal
        function displayQuestion(index) {
            currentQuestionIndex = index;
            const question = questions[index];

            questionContextEl.innerHTML = question.konteks_soal ? `<div class="question-context-box">${question.konteks_soal}</div>` : '';
            questionTextEl.innerHTML = `<b>${question.pertanyaan}</b>`;

            answerOptionsEl.innerHTML = '';
            const options = ['a', 'b', 'c', 'd', 'e'];
            options.forEach(opt => {
                const optionText = question[`pilihan_${opt}`];
                if (optionText) {
                    const optionEl = document.createElement('div');
                    optionEl.classList.add('answer-option');
                    optionEl.dataset.value = opt;
                    optionEl.innerHTML = `<span class="opt-letter">${opt.toUpperCase()}.</span><div class="opt-text">${optionText}</div>`;
                    
                    if (userAnswers[index] === opt) {
                        optionEl.classList.add('selected');
                    }

                    optionEl.addEventListener('click', () => selectAnswer(index, opt));
                    answerOptionsEl.appendChild(optionEl);
                }
            });

            updateNavButtons();
            renderQuestionNumbers();
        }

        // Fungsi untuk memilih jawaban
        function selectAnswer(questionIndex, selectedOption) {
            userAnswers[questionIndex] = selectedOption;
            // Re-render untuk update tampilan
            displayQuestion(questionIndex);
            checkAllAnswered();
        }

        // Fungsi untuk update tombol navigasi
        function updateNavButtons() {
            prevBtn.disabled = currentQuestionIndex === 0;
            nextBtn.disabled = currentQuestionIndex === questions.length - 1;
        }

        // Fungsi untuk timer
        function startTimer(duration) {
            let timer = duration;
            timerInterval = setInterval(() => {
                let minutes = parseInt(timer / 60, 10);
                let seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                timerEl.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(timerInterval);
                    finishTest();
                }
            }, 1000);
        }

        // Cek apakah semua soal sudah dijawab
        function checkAllAnswered() {
            const allAnswered = Object.values(userAnswers).length > 0 && Object.values(userAnswers).every(v => v !== null);
            if (allAnswered) {
                finishBtn.style.display = 'inline-block';
            } else {
                finishBtn.style.display = 'none';
            }
        }

        // Fungsi untuk menyelesaikan tes
        function finishTest() {
            clearInterval(timerInterval);
            
            // Kirim jawaban ke server
            const startTime = new Date().toISOString().slice(0, 19).replace('T', ' '); // Waktu mulai tes (bisa disesuaikan jika ingin lebih akurat)
            
            fetch('../api/api_submit_tryout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id_tryout: id_tryout,
                    subtest_id: subtest_id,
                    user_answers: userAnswers,
                    start_time: startTime // Kirim waktu mulai
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Tampilkan modal sukses (mengganti alert)
                    const successModalEl = document.getElementById('submitSuccessModal');
                    if (successModalEl) {
                        // simpan tujuan redirect di attribute
                        successModalEl.dataset.redirect = `.?hal=hasiltryout&session_id=${data.session_id}`;
                        const sm = new bootstrap.Modal(successModalEl);
                        sm.show();
                    } else {
                        // fallback: alert + redirect
                        alert('Tes Selesai! Jawaban Anda telah disimpan.');
                        window.location.href = `.?hal=hasiltryout&session_id=${data.session_id}`;
                    }
                } else {
                    alert('Gagal menyimpan jawaban: ' + (data.message || 'Terjadi kesalahan.'));
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan saat mengirim jawaban: ' + error.message);
            });
        }

        // Expose finishTest to global scope so modal confirm can call it
        window.finishTest = finishTest;

        // Event Listeners
        prevBtn.addEventListener('click', () => {
            if (currentQuestionIndex > 0) {
                displayQuestion(currentQuestionIndex - 1);
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentQuestionIndex < questions.length - 1) {
                displayQuestion(currentQuestionIndex + 1);
            }
        });

        finishBtn.addEventListener('click', () => {
                // Tampilkan modal konfirmasi alih-alih confirm() native
                const modalEl = document.getElementById('finishConfirmModal');
                if (modalEl) {
                    const bm = new bootstrap.Modal(modalEl);
                    bm.show();
                } else {
                    // fallback ke confirm jika modal tidak tersedia
                    if (confirm('Apakah Anda yakin ingin menyelesaikan subtest ini?')) {
                        finishTest();
                    }
                }
        });

        // Mulai memuat data
        loadTestData();
    });
    </script>
        <!-- Modal Konfirmasi Selesaikan Tes -->
        <div class="modal fade" id="finishConfirmModal" tabindex="-1" aria-labelledby="finishConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="finishConfirmModalLabel">Konfirmasi Selesai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin mengakhiri pengerjaan subtest ini? Setelah selesai, Anda tidak dapat kembali mengubah jawaban.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="confirmFinishBtn" class="btn btn-primary">Selesaikan</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
        // Hook tombol konfirmasi modal
        document.addEventListener('DOMContentLoaded', function() {
                const confirmFinishBtn = document.getElementById('confirmFinishBtn');
                if (confirmFinishBtn) {
                        confirmFinishBtn.addEventListener('click', function() {
                                // Sembunyikan modal lalu jalankan finishTest
                                const modalEl = document.getElementById('finishConfirmModal');
                                const bm = bootstrap.Modal.getInstance(modalEl);
                                if (bm) bm.hide();
                                // Pastikan finishTest sudah didefinisikan di scope atas
                                if (typeof finishTest === 'function') finishTest();
                        });
                }
        });
        </script>
        <!-- Modal Sukses Submit -->
        <div class="modal fade" id="submitSuccessModal" tabindex="-1" aria-labelledby="submitSuccessModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="submitSuccessModalLabel">Tes Selesai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tes selesai. Jawaban Anda telah disimpan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" id="goToResultBtn" class="btn btn-primary">Lihat Hasil</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
                const goToResultBtn = document.getElementById('goToResultBtn');
                const submitSuccessModal = document.getElementById('submitSuccessModal');
                if (goToResultBtn && submitSuccessModal) {
                        goToResultBtn.addEventListener('click', function() {
                                const redirect = submitSuccessModal.dataset.redirect || window.location.href;
                                const sm = bootstrap.Modal.getInstance(submitSuccessModal);
                                if (sm) sm.hide();
                                window.location.href = redirect;
                        });
                        // Optional: redirect when modal hidden (if user clicks 'Tutup')
                        submitSuccessModal.addEventListener('hidden.bs.modal', function () {
                                const redirect = submitSuccessModal.dataset.redirect;
                                if (redirect) {
                                        window.location.href = redirect;
                                }
                        });
                }
        });
        </script>
</main>
