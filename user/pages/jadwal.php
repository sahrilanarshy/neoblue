<?php
// Ambil data subtest untuk dropdown
$query_subtest = mysqli_query($koneksi, "SELECT id, nama_subtest, singkatan FROM subtest ORDER BY nama_subtest ASC");
$subtests = mysqli_fetch_all($query_subtest, MYSQLI_ASSOC);

// Siapkan opsi jam
$jam_options = '';
for ($h = 0; $h < 24; $h++) {
    for ($m = 0; $m < 60; $m += 30) {
        $time = sprintf('%02d:%02d', $h, $m);
        $jam_options .= "<option value='{$time}:00'>{$time}</option>";
    }
}
?>
<main class="dashboard-content">
    <h1 class="page-title with-underline">Jadwal Belajar</h1>
    <div class="main-grid-layout">

        <section class="learning-materials">
            <form id="formTambahJadwal">
                <h2 class="form-title" style="font-size: 20px; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="bi bi-plus-circle-fill" style="color: #3b82f6;"></i> Tambah Jadwal Baru
                </h2>
                <div class="form-group">
                    <label for="pilih-hari">Hari</label>
                    <select id="pilih-hari" class="form-select" required>
                        <option value="" disabled selected>Pilih hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pilih-pelajaran">Mata Pelajaran</label>
                    <select id="pilih-pelajaran" class="form-select" required>
                        <option value="" disabled selected>Pilih pelajaran</option>
                        <?php foreach ($subtests as $subtest): ?>
                            <option value="<?= $subtest['id']; ?>"><?= htmlspecialchars($subtest['nama_subtest']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="time-inputs">
                    <div class="form-group">
                        <label for="jam-mulai">Jam Mulai</label>
                        <select id="jam-mulai" class="form-select" required>
                            <option value="" disabled selected>Jam Mulai</option>
                            <?= $jam_options; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jam-selesai">Jam Selesai</label>
                        <select id="jam-selesai" class="form-select" required>
                            <option value="" disabled selected>Jam Selesai</option>
                            <?= $jam_options; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-yellow btn-full-width"><i class="bi bi-plus-lg"></i> Tambah Jadwal</button>
            </form>
        </section>
        <section class="learning-materials">
            <div class="content-box">
                <h2>Jadwal Saat Ini</h2>
                <div id="schedule-list" class="schedule-list">
                    <p id="loading-schedule" class="text-center text-muted">Memuat jadwal...</p>
                </div>
            </div>
        </section>
    </div>
    <section class="learning-materials">
        <h2>Ringkasan Mingguan</h2>
        <div id="summary-grid" class="summary-grid">
            <!-- Ringkasan mingguan akan dimuat di sini -->
        </div>
    </section>

    <!-- Elemen Notifikasi Pop-up -->
    <div id="toast-notification" class="toast-notification">
        <i class="bi bi-check-circle-fill"></i>
        <span id="toast-message"></span>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const scheduleList = document.getElementById('schedule-list');
    const summaryGrid = document.getElementById('summary-grid');
    const form = document.getElementById('formTambahJadwal');
    const loadingMessage = document.getElementById('loading-schedule');
    const toast = document.getElementById('toast-notification');
    const toastMessage = document.getElementById('toast-message');

    const daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    // Fungsi untuk merender daftar jadwal
    function renderScheduleList(schedules) {
        scheduleList.innerHTML = ''; // Kosongkan daftar
        if (schedules.length === 0) {
            scheduleList.innerHTML = '<p class="text-center text-muted">Belum ada jadwal yang ditambahkan.</p>';
        } else {
            schedules.forEach(schedule => {
                const startTime = schedule.jam_mulai.substring(0, 5);
                const endTime = schedule.jam_selesai.substring(0, 5);
                const itemHTML = `
                    <div class="schedule-item" data-id="${schedule.id}">
                        <div class="schedule-info">
                            <h3>${schedule.hari}</h3>
                            <p>${schedule.nama_subtest}</p>
                            <span class="time-tag"><i class="bi bi-clock"></i> ${startTime} - ${endTime}</span>
                        </div>
                        <button class="delete-btn" onclick="deleteSchedule(${schedule.id})"><i class="bi bi-trash3"></i></button>
                    </div>`;
                scheduleList.insertAdjacentHTML('beforeend', itemHTML);
            });
        }
    }

    // Fungsi untuk merender ringkasan mingguan
    function renderSummaryGrid(schedules) {
        summaryGrid.innerHTML = ''; // Kosongkan ringkasan
        const schedulesByDay = {};
        schedules.forEach(s => {
            if (!schedulesByDay[s.hari]) schedulesByDay[s.hari] = [];
            schedulesByDay[s.hari].push(s);
        });

        daysOrder.forEach(day => {
            let dayContent = `<div class="summary-day"><h4>${day}</h4>`;
            if (schedulesByDay[day]) {
                schedulesByDay[day].forEach(s => {
                    const startTime = s.jam_mulai.substring(0, 5);
                    const endTime = s.jam_selesai.substring(0, 5);
                    const label = (s.singkatan && s.singkatan.trim() !== '') ? s.singkatan : s.nama_subtest;
                    dayContent += `
                        <div class="summary-card">
                            <strong>${label}</strong>
                            <span>${startTime} - ${endTime}</span>
                        </div>`;
                });
            } else {
                dayContent += '<p class="no-schedule">Tidak ada jadwal</p>';
            }
            dayContent += '</div>';
            summaryGrid.insertAdjacentHTML('beforeend', dayContent);
        });
    }

    // Fungsi untuk memuat jadwal dari API
    async function loadSchedules() {
        try {
            const response = await fetch('../api/api_jadwal.php', { credentials: 'same-origin' });
            const result = await response.json();
            if (loadingMessage) loadingMessage.style.display = 'none';

            if (result.status === 'success') {
                renderScheduleList(result.data);
                renderSummaryGrid(result.data);
            } else {
                scheduleList.innerHTML = `<p class="text-center text-danger">${result.message}</p>`;
            }
        } catch (error) {
            if (loadingMessage) loadingMessage.style.display = 'none';
            scheduleList.innerHTML = `<p class="text-center text-danger">Gagal memuat data. Periksa koneksi Anda.</p>`;
        }
    }

    // Fungsi untuk menampilkan notifikasi pop-up
    function showToast(message) {
        toastMessage.textContent = message;
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000); // Notifikasi akan hilang setelah 3 detik
    }


    // Fungsi untuk menghapus jadwal
    window.deleteSchedule = async function(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) return;

        try {
            const response = await fetch('../api/api_jadwal.php', {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            });
            const result = await response.json();
            if (result.status === 'success') {
                showToast(result.message);
                loadSchedules(); // Muat ulang data
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Gagal menghapus jadwal. Periksa koneksi Anda.');
        }
    }

    // Event listener untuk form submit
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Menyimpan...';

        const scheduleData = {
            hari: document.getElementById('pilih-hari').value,
            subtest_id: document.getElementById('pilih-pelajaran').value,
            jam_mulai: document.getElementById('jam-mulai').value,
            jam_selesai: document.getElementById('jam-selesai').value
        };

        try {
            const response = await fetch('../api/api_jadwal.php', {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(scheduleData)
            });
            const result = await response.json();

            if (response.ok && result.status === 'success') {
                showToast(result.message);
                form.reset();
                loadSchedules(); // Muat ulang data
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Gagal menambahkan jadwal. Periksa koneksi Anda.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-plus-lg"></i> Tambah Jadwal';
        }
    });

    // Muat jadwal saat halaman pertama kali dibuka
    loadSchedules();
});
</script>
