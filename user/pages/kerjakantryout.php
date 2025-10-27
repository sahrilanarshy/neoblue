<main class="tryout-container">

    <div class="tryout-question-panel">
        
        <div class="tryout-header">
            <h1 class="page-title" style="margin: 0;">Tryout #15 - Penalaran Umum</h1>
            <div class="timer">
                <i class="bi bi-clock-fill"></i> Sisa Waktu: <span id="time">30:00</span>
            </div>
        </div>

        <div class="question-content">
            <p class="question-number">Soal Nomor 1</p>
            <div class="question-body">
                <p>Semua bunga adalah tanaman. Semua mawar adalah bunga. Kesimpulan yang benar adalah ...</p>
                </div>

            <ul class="answer-options-list">
                <li>
                    <input type="radio" name="option" id="opt-a" hidden>
                    <label for="opt-a">A. Semua tanaman adalah mawar</label>
                </li>
                <li>
                    <input type="radio" name="option" id="opt-b" hidden>
                    <label for="opt-b">B. Semua mawar adalah tanaman</label>
                </li>
                <li>
                    <input type="radio" name="option" id="opt-c" hidden>
                    <label for="opt-c">C. Beberapa bunga bukan tanaman</label>
                </li>
                <li>
                    <input type="radio" name="option" id="opt-d" hidden>
                    <label for="opt-d">D. Semua tanaman bukan bunga</label>
                </li>
                <li>
                    <input type="radio" name="option" id="opt-e" hidden>
                    <label for="opt-e">E. Tidak ada hubungan antara bunga dan mawar</label>
                </li>
            </ul>
        </div>

        <div class="tryout-actions">
            <button class="btn-upgrade-item"><i class="bi bi-chevron-left"></i> Sebelumnya</button>
            <button class="btn-mulai" style="background-color: #f59e0b;"><i class="bi bi-flag-fill"></i> Ragu-ragu</button>
            <button class="btn-mulai">Selanjutnya <i class="bi bi-chevron-right"></i></button>
        </div>
    </div>

    <div class="learning-materials tryout-navigation-panel">
        <h2>Navigasi Soal</h2>
        
        <div class="question-grid">
            <a href="#" class="question-grid-item active">1</a>
            <a href="#" class="question-grid-item answered">2</a>
            <a href="#" class="question-grid-item">3</a>
            <a href="#" class="question-grid-item">4</a>
            <a href="#" class="question-grid-item flagged">5</a>
            <a href="#" class="question-grid-item">6</a>
            <a href="#" class="question-grid-item">7</a>
            <a href="#" class="question-grid-item">8</a>
            <a href="#" class="question-grid-item">9</a>
            <a href="#" class="question-grid-item">10</a>
            <a href="#" class="question-grid-item">11</a>
            <a href="#" class="question-grid-item">12</a>
            <a href="#" class="question-grid-item">13</a>
            <a href="#" class="question-grid-item">14</a>
            <a href="#" class="question-grid-item">15</a>
        </div>

        <div class="legend">
            <p><strong>Keterangan:</strong></p>
            <ul>
                <li><span class="box active"></span> Sedang dikerjakan</li>
                <li><span class="box answered"></span> Sudah dijawab</li>
                <li><span class="box flagged"></span> Ragu-ragu</li>
                <li><span class="box"></span> Belum dijawab</li>
            </ul>
        </div>
        
        <a href=".?hal=hasiltryout" class="btn-selesai"><i class="bi bi-check-circle-fill"></i> Selesai Tryout</a>
    </div>
</main>

<script>
    document.body.classList.add('tryout-active');
    // Hapus class saat meninggalkan halaman (jika diperlukan, tapi biasanya tidak)
</script>