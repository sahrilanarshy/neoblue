<main class="dashboard-content">
    <div class="profile-grid">
        <div class="profile-column">
            <section class="profile-card">
                <div class="profile-card-header">
                    <h2>Paket Gratis</h2>
                    <a href="#" class="btn-premium-small">Langganan Premium</a>
                </div>
                <div class="profile-card-body">
                    <p>Langganan Paket Premium 199 rb<br>(Masa aktif hingga 31/05/2026)</p>
                </div>
            </section>

            <section class="profile-card">
                <div class="profile-card-header">
                    <h2>Komunitas Habitutor Premium</h2>
                    <a href="#" class="btn-community-green">Habitutor Community</a>
                </div>
                <div class="profile-card-body">
                    <p>Grup Whatsapp member khusus Premium</p>
                </div>
            </section>

            <section class="profile-card">
                <div class="profile-card-header">
                    <h2>Komunitas Habitutor Reguler</h2>
                    <a href="#" class="btn-community-green-outline">Whatsapp</a>
                </div>
                <div class="profile-card-body">
                    <p>Grup Whatsapp member khusus Premium</p>
                </div>
            </section>
        </div>

        <div class="profile-column">
            <section class="profile-card">
                <h2 class="edit-profile-title">Edit Profil</h2>
                <form class="edit-profile-form">
                    <div class="profile-picture-area">
                        <div class="profile-picture-initials">
                            <span>YA</span>
                        </div>
                        <div class="profile-picture-upload">
                            <input type="file" id="file-upload" class="file-upload-input" hidden/>
                            <label for="file-upload" class="btn-file-upload">Choose File</label>
                            <span>No file chosen</span>
                        </div>
                        <small class="form-hint">Format gambar JPG/JPEG/PNG</small>
                    </div>

                    <div class="form-group">
                        <label for="nama-lengkap">Nama Lengkap</label>
                        <input type="text" id="nama-lengkap" class="form-input" value="Yamani Akbar Ahmadi">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-input" value="ahmadaniakbar21@gmail.com" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nomor-hp">Nomor HP</label>
                        <input type="text" id="nomor-hp" class="form-input" value="085123456789">
                    </div>
                    <div class="form-group">
                        <label for="password-sekarang">Password Sekarang</label>
                        <input type="password" id="password-sekarang" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="password-baru">Password Baru</label>
                        <input type="password" id="password-baru" class="form-input">
                    </div>
                    <button type="submit" class="btn-save-changes">Simpan Perubahan</button>
                </form>

                <!-- 🔹 Tombol Logout -->
                <div class="logout-section">
                    <hr>
                    <a href=".?hal=logout" class="btn-logout">Keluar</a>
                </div>
            </section>
        </div>
    </div>
</main>