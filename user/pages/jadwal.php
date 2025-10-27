<main class="dashboard-content">
    <h1 class="page-title with-underline">Jadwal Belajar</h1>
    <div class="main-grid-layout">

        <section class="learning-materials">
            <button class="btn btn-outline btn-full-width form-title-button">
                <i class="bi bi-plus-circle-fill"></i> Tambah Jadwal Baru
            </button>
            <div class="form-group">
                <label for="pilih-hari">Hari ini</label>
                <select id="pilih-hari" class="form-select">
                    <option selected>Pilih hari</option>
                    <option value="1">Senin</option>
                    <option value="2">Selasa</option>
                    <option value="3">Rabu</option>
                    <option value="4">Kamis</option>
                    <option value="5">Jumat</option>
                    <option value="6">Sabtu</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pilih-pelajaran">Mata Pelajaran</label>
                <select id="pilih-pelajaran" class="form-select">
                    <option selected>Pilih pelajaran</option>
                    <option value="1">Penalaran Umum</option>
                    <option value="2">Pemahaman Bacaan dan Menulis</option>
                    <option value="3">Penalaran Matematika</option>
                </select>
            </div>
            <div class="time-inputs">
                <div class="form-group">
                    <label for="jam-mulai">Jam Mulai</label>
                    <select id="jam-mulai" class="form-select">
                        <option selected>Jam Mulai</option>
                        <option value="1">19:30</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="jam-selesai">Jam Selesai</label>
                    <select id="jam-selesai" class="form-select">
                        <option selected>Jam Selesai</option>
                        <option value="1">20:30</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-yellow btn-full-width"><i class="bi bi-plus-lg"></i> Tambah
                Jadwal</button>
        </section>
        <section class="learning-materials">
            <div class="content-box">
                <h2>Jadwal Saat Ini</h2>
                <div class="schedule-list">
                    <div class="schedule-item">
                        <div class="schedule-info">
                            <h3>Senin</h3>
                            <p>Penalaran Umum</p>
                            <span class="time-tag"><i class="bi bi-clock"></i> 19:30 - 20:30</span>
                        </div>
                        <button class="delete-btn"><i class="bi bi-trash3"></i></button>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-info">
                            <h3>Rabu</h3>
                            <p>Pemahaman Bacaan dan Menulis</p>
                            <span class="time-tag"><i class="bi bi-clock"></i> 19:30 - 20:30</span>
                        </div>
                        <button class="delete-btn"><i class="bi bi-trash3"></i></button>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-info">
                            <h3>Jumat</h3>
                            <p>Penalaran Matematika</p>
                            <span class="time-tag"><i class="bi bi-clock"></i> 19:30 - 20:30</span>
                        </div>
                        <button class="delete-btn"><i class="bi bi-trash3"></i></button>
                    </div>
                </div>
            </div>
    </div>
    <section class="learning-materials">
        <h2>Ringkasan Mingguan</h2>
        <div class="summary-grid">
            <div class="summary-day">
                <h4>Senin</h4>
                <div class="summary-card">
                    <strong>PU</strong>
                    <span>19:30 - 20:30</span>
                </div>
            </div>
            <div class="summary-day">
                <h4>Selasa</h4>
                <p class="no-schedule">Tidak ada jadwal</p>
            </div>
            <div class="summary-day">
                <h4>Rabu</h4>
                <div class="summary-card">
                    <strong>PBM</strong>
                    <span>19:30 - 20:30</span>
                </div>
            </div>
            <div class="summary-day">
                <h4>Kamis</h4>
                <p class="no-schedule">Tidak ada jadwal</p>
            </div>
            <div class="summary-day">
                <h4>Jumat</h4>
                <div class="summary-card">
                    <strong>PM</strong>
                    <span>19:30 - 20:30</span>
                </div>
            </div>
            <div class="summary-day">
                <h4>Sabtu</h4>
                <p class="no-schedule">Tidak ada jadwal</p>
            </div>
        </div>
    </section>
</main>
