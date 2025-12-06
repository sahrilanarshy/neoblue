<?php
if (isset($_SESSION['sukses'])) {
    $pesan_sukses = $_SESSION['sukses'];
    unset($_SESSION['sukses']);
} elseif (isset($_SESSION['gagal'])) {
    $pesan_gagal = $_SESSION['gagal'];
    unset($_SESSION['gagal']);
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Profile</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Profile</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card profile-card-left">
                <div class="card-body">
                    <img src="../assets/admin/img/logo/icon profile.png" alt="Avatar" class="profile-avatar" id="profile-avatar-img">
                    <h5 class="card-title" id="profile-card-name">Memuat...</h5>
                    <p class="text-muted" id="profile-card-role">Memuat...</p>
                </div>
                <div class="card-body profile-info-container">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="profile-card-email" value="Memuat..." readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No Telp</label>
                        <input type="text" class="form-control" id="profile-card-telepon" value="Memuat..." readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" value="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form id="profileUpdateForm" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <h5 class="mb-3">Edit Profile</h5>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="nama" placeholder="Memuat..." required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Memuat..." readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="noTelp" class="form-label">No Telp</label>
                                <input type="text" class="form-control" id="noTelp" name="telepon" placeholder="Memuat..." required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="foto_upload" class="form-label">Upload Foto</label>
                                <input type="file" class="form-control" id="foto_upload" name="foto_profil"
                                    accept="image/png, image/jpeg" />
                                <small class="form-text text-muted">Pilih file gambar dari komputer Anda (format
                                    .jpg, .png).</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="password" class="form-label">Password Baru (opsional)</label>
                                <input type="password" class="form-control" id="password_baru" name="password_baru"
                                    placeholder="Isi jika ingin mengubah password">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn btn-primary" id="updateButton">Update Profile</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'sukses_gagal_modal.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elemen-elemen di kartu kiri
    const profileAvatar = document.getElementById('profile-avatar-img');
    const profileCardName = document.getElementById('profile-card-name');
    const profileCardRole = document.getElementById('profile-card-role');
    const profileCardEmail = document.getElementById('profile-card-email');
    const profileCardTelepon = document.getElementById('profile-card-telepon');

    // Elemen-elemen di form kanan
    const profileForm = document.getElementById('profileUpdateForm');
    const fullNameInput = document.getElementById('fullName');
    const emailInput = document.getElementById('email');
    const noTelpInput = document.getElementById('noTelp');
    const fotoUploadInput = document.getElementById('foto_upload');
    const passwordBaruInput = document.getElementById('password_baru');
    const updateButton = document.getElementById('updateButton');

    const defaultAvatar = '../assets/admin/img/logo/icon profile.png';

    // Fungsi untuk memuat data profil
    async function loadProfile() {
        try {
            const response = await fetch('../api/api_profile.php');
            if (!response.ok) throw new Error('Gagal mengambil data profil.');
            
            const result = await response.json();
            if (result.status === 'success') {
                const user = result.data;

                // Update kartu kiri
                profileAvatar.src = user.foto_profil ? '../' + user.foto_profil + '?t=' + new Date().getTime() : defaultAvatar;
                profileCardName.textContent = user.nama;
                profileCardRole.textContent = user.role.charAt(0).toUpperCase() + user.role.slice(1);
                profileCardEmail.value = user.email;
                profileCardTelepon.value = user.telepon;

                // Update form kanan
                fullNameInput.value = user.nama;
                emailInput.value = user.email;
                noTelpInput.value = user.telepon;

            } else {
                showModal('gagalModal', result.message || 'Gagal memuat data.');
            }
        } catch (error) {
            showModal('gagalModal', error.message);
        }
    }

    // Fungsi untuk menangani submit form
    profileForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const originalButtonText = updateButton.innerHTML;
        updateButton.disabled = true;
        updateButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;

        const formData = new FormData(profileForm);
        // API `api_profile.php` tidak memerlukan password_sekarang untuk role guru/admin
        // jadi kita bisa langsung mengirim password_baru
        formData.append('password_sekarang', ''); // Kirim kosong

        try {
            const response = await fetch('../api/api_profile.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.status === 'success') {
                showModal('suksesModal', result.message);
                passwordBaruInput.value = ''; // Kosongkan field password
                fotoUploadInput.value = ''; // Kosongkan input file
                await loadProfile(); // Muat ulang data profil untuk menampilkan perubahan
            } else {
                showModal('gagalModal', result.message || 'Terjadi kesalahan.');
            }

        } catch (error) {
            showModal('gagalModal', 'Tidak dapat terhubung ke server.');
        } finally {
            updateButton.disabled = false;
            updateButton.innerHTML = originalButtonText;
        }
    });

    // Fungsi untuk menampilkan modal
    function showModal(modalId, message) {
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            const messageElementId = modalId === 'suksesModal' ? 'pesanSuksesModal' : 'pesanGagalModal';
            document.getElementById(messageElementId).innerText = message;
            new bootstrap.Modal(modalElement).show();
        }
    }

    // Panggil fungsi untuk memuat profil saat halaman dibuka
    loadProfile();

    // Tampilkan modal dari session jika ada
    <?php if (isset($pesan_sukses)): ?>
        showModal('suksesModal', '<?= addslashes($pesan_sukses); ?>');
    <?php elseif (isset($pesan_gagal)): ?>
        showModal('gagalModal', '<?= addslashes($pesan_gagal); ?>');
    <?php endif; ?>
});
</script>
