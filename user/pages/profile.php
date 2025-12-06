<main class="dashboard-content">
    <div class="profile-grid">
        <div class="profile-column">
            <section class="profile-card" id="subscription-card">
                <div class="profile-card-header">
                    <h2 id="subscription-title">Memuat...</h2>
                    <a href=".?hal=premium" class="btn-premium-small" id="subscription-button">...</a>
                </div>
                <div class="profile-card-body">
                    <p id="subscription-info">Memuat status langganan Anda...</p>
                </div>
            </section>

            <section class="profile-card" id="community-card">
                <div class="profile-card-header">
                    <h2>Komunitas NeoBlue Premium</h2>
                    <a href="#" id="community-join-button" target="_blank" rel="noopener noreferrer" class="btn-community-green">Gabung Komunitas</a>
                </div>
                <div class="profile-card-body">
                    <p>Grup Whatsapp khusus member Premium</p>
                </div>
            </section>

        </div>

        <div class="profile-column">
            <section class="profile-card">
                <h2 class="edit-profile-title">Edit Profil</h2>
                <form id="profileForm" class="edit-profile-form">
                    <div class="profile-picture-area">
                        <div class="profile-picture-wrapper">
                            <img src="../assets/user/img/avatar.png" alt="Foto Profil" id="profile-image-preview" class="profile-image">
                            <div class="profile-picture-initials" id="initials-fallback" style="display: none;">
                                <span id="initials-span">--</span>
                            </div>
                        </div>
                        <label for="foto-upload" class="btn-file-upload">
                            <i class="bi bi-camera-fill"></i> Ganti Foto
                        </label>
                        <input type="file" id="foto-upload" name="foto_profil" class="file-upload-input" hidden accept="image/png, image/jpeg">
                    </div>

                    <div class="form-group">
                        <label for="nama-lengkap">Nama Lengkap</label>
                        <input type="text" id="nama-lengkap" class="form-input" placeholder="Memuat..." required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-input" placeholder="Memuat..." readonly>
                    </div>
                    <div class="form-group">
                        <label for="nomor-hp">Nomor HP</label>
                        <input type="text" id="nomor-hp" class="form-input" placeholder="Memuat..." required>
                    </div>

                    <hr class="my-4">
                    <p class="text-muted small">Isi bagian di bawah ini hanya jika Anda ingin mengubah password.</p>

                    <div class="form-group">
                        <label for="password-sekarang">Password Sekarang</label>
                        <input type="password" id="password-sekarang" class="form-input" placeholder="Kosongkan jika tidak diubah">
                    </div>
                    <div class="form-group">
                        <label for="password-baru">Password Baru</label>
                        <input type="password" id="password-baru" class="form-input" placeholder="Kosongkan jika tidak diubah">
                    </div>
                    <button type="submit" class="btn-save-changes">Simpan Perubahan</button>
                </form>

                <div class="logout-section">
                    <hr>
                    <a href=".?hal=logout" class="btn-logout">Keluar</a>
                </div>
            </section>
        </div>
    </div>

    <!-- Elemen Notifikasi Pop-up -->
    <div id="toast-notification" class="toast-notification">
        <i id="toast-icon" class="bi"></i>
        <span id="toast-message"></span>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elemen Form
    const profileImagePreview = document.getElementById('profile-image-preview');
    const profileForm = document.getElementById('profileForm');
    const initialsSpan = document.getElementById('initials-span');
    const namaInput = document.getElementById('nama-lengkap');
    const emailInput = document.getElementById('email');
    const teleponInput = document.getElementById('nomor-hp');
    const passSekarangInput = document.getElementById('password-sekarang');
    const passBaruInput = document.getElementById('password-baru');
    const saveButton = document.querySelector('.btn-save-changes');
    const fotoUploadInput = document.getElementById('foto-upload');
    const initialsFallback = document.getElementById('initials-fallback');

    // Elemen Kartu Langganan
    const subCard = document.getElementById('subscription-card');
    const subTitle = document.getElementById('subscription-title');
    const subButton = document.getElementById('subscription-button');
    const subInfo = document.getElementById('subscription-info');

    // Elemen Kartu Komunitas
    const communityCard = document.getElementById('community-card');
    const communityJoinButton = document.getElementById('community-join-button');

    // Elemen Toast
    const toast = document.getElementById('toast-notification');
    const toastIcon = document.getElementById('toast-icon');
    const toastMessage = document.getElementById('toast-message');

    // Fungsi untuk mendapatkan inisial dari nama
    function getInitials(name) {
        if (!name) return '--';
        const words = name.split(' ');
        if (words.length > 1) {
            return (words[0][0] + words[1][0]).toUpperCase();
        }
        return name.substring(0, 2).toUpperCase();
    }

    // Fungsi untuk menampilkan toast
    function showToast(message, isSuccess = true) {
        toastMessage.textContent = message;
        toast.style.backgroundColor = isSuccess ? '#22c55e' : '#ef4444';
        toastIcon.className = isSuccess ? 'bi bi-check-circle-fill' : 'bi bi-exclamation-triangle-fill';
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // 1. Ambil dan tampilkan data profil
    async function loadProfile() {
        try {
            const response = await fetch('../api/api_profile.php');
            const result = await response.json();

            if (result.status === 'success') {
                const user = result.data;
                namaInput.value = user.nama;
                emailInput.value = user.email;
                teleponInput.value = user.telepon;
                initialsSpan.textContent = getInitials(user.nama);

                // Update foto profil
                if (user.foto_profil) {
                    profileImagePreview.src = '../' + user.foto_profil + '?t=' + new Date().getTime(); // Cache-busting
                    profileImagePreview.style.display = 'block';
                    initialsFallback.style.display = 'none';
                } else {
                    profileImagePreview.style.display = 'none';
                    initialsFallback.style.display = 'flex';
                }

                // Update kartu langganan
                if (user.tipe_user && user.tipe_user.toLowerCase() === 'premium') {
                    subTitle.textContent = 'Paket Premium';
                    subButton.textContent = 'Aktif';
                    subButton.href = '#';
                    subButton.style.pointerEvents = 'none';
                    subInfo.textContent = 'Anda memiliki akses penuh ke semua fitur.';

                    // Aktifkan tombol komunitas untuk user premium
                    communityJoinButton.classList.remove('disabled');
                    communityJoinButton.href = 'https://chat.whatsapp.com/G5ijCvPnoD26Tc1FNngupF';
                    communityCard.style.opacity = '1';

                } else {
                    subTitle.textContent = 'Paket Gratis';
                    subButton.textContent = 'Langganan Premium';
                    subButton.href = '.?hal=premium';
                    subInfo.textContent = 'Upgrade untuk akses tanpa batas.';

                    // Nonaktifkan tombol komunitas untuk user gratis
                    communityJoinButton.classList.add('disabled');
                    communityJoinButton.removeAttribute('href');
                    communityCard.style.opacity = '0.6';
                }
            } else {
                showToast(result.message, false);
            }
        } catch (error) {
            showToast('Gagal memuat data profil.', false);
        }
    }

    // Handle file input change for preview
    fotoUploadInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profileImagePreview.src = e.target.result;
                profileImagePreview.style.display = 'block';
                initialsFallback.style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });

    // 2. Handle submit form untuk update
    profileForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const originalButtonText = saveButton.textContent;
        saveButton.disabled = true;
        saveButton.textContent = 'Menyimpan...';
        
        const formData = new FormData();
        formData.append('nama', namaInput.value);
        formData.append('telepon', teleponInput.value);
        formData.append('password_sekarang', passSekarangInput.value);
        formData.append('password_baru', passBaruInput.value);
        if (fotoUploadInput.files[0]) {
            formData.append('foto_profil', fotoUploadInput.files[0]);
        }

        try {
            const response = await fetch('../api/api_profile.php', {
                method: 'POST',
                body: formData // Kirim sebagai FormData
            });

            const result = await response.json();

            if (result.status === 'success') {
                showToast(result.message, true);
                // Kosongkan field password setelah berhasil
                passSekarangInput.value = '';
                passBaruInput.value = '';
                // Perbarui inisial jika nama berubah
                initialsSpan.textContent = getInitials(namaInput.value);
                // Reload data untuk memastikan foto di header juga update
                loadProfile(); 
            } else {
                showToast(result.message, false);
            }
        } catch (error) {
            showToast('Terjadi kesalahan koneksi.', false);
        } finally {
            saveButton.disabled = false;
            saveButton.textContent = originalButtonText;
        }
    });

    // Panggil fungsi untuk memuat profil saat halaman dibuka
    // Jika tombol diklik saat masih non-premium, tampilkan toast
    communityJoinButton.addEventListener('click', function(e) {
        if (communityJoinButton.classList.contains('disabled')) {
            e.preventDefault();
            showToast('Fitur ini hanya untuk member Premium. Silakan tingkatkan paket Anda.', false);
        }
    });

    loadProfile();
});
</script>