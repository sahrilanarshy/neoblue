<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - NeoBlue</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="assets/landingpage/img/logo/logo_neoblue.png" rel="icon">
    <link rel="stylesheet" href="assets/landingpage/css/style.css">
</head>

<body>

    <div class="login-wrapper">
        <div class="header-section">
            <img src="assets/landingpage/img/logo/logo neoblue.png" alt="NeoBlue Logo" class="logo-img">
            <h2>Buat Akun Baru</h2>
            <p>Sudah punya akun? <a href="login.php">Masuk sekarang</a></p>
        </div>

        <div class="alert alert-error" style="display:none;" id="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span id="error-text"></span>
        </div>

        <form id="register-form" autocomplete="off">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <div class="input-wrapper">
                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                    <i class="fas fa-user icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email" required>
                    <i class="fas fa-envelope icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="telepon">Nomor Telepon</label>
                <div class="input-wrapper">
                    <input type="tel" id="telepon" name="telepon" class="form-control" placeholder="Masukkan nomor telepon" required 
                           pattern="[0-9]+" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                    <i class="fas fa-phone icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Buat password yang aman" required>
                    <i class="fas fa-lock icon"></i>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                <span id="btnText">Daftar Sekarang</span>
                <span class="spinner" id="btnSpinner" style="display:none;"></span>
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 25px;">
            <p style="font-size: 12px; color: #aaa;">&copy; <?= date('Y'); ?> NeoBlue Platform. All rights reserved.</p>
        </div>
    </div>

    <script>
        // 1. Fitur Toggle Password
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // 2. Logic Registrasi
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('register-form');
            const errorDiv = document.getElementById('alert-error');
            const errorText = document.getElementById('error-text');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            form.addEventListener('submit', async function(event) {
                event.preventDefault();

                // Reset Error
                errorDiv.style.display = 'none';

                // Ambil Value
                const nama = document.getElementById('nama').value.trim();
                const email = document.getElementById('email').value.trim();
                const telepon = document.getElementById('telepon').value.trim();
                const password = document.getElementById('password').value;

                // Validasi Sederhana
                if (!nama || !email || !telepon || !password) {
                    errorText.textContent = 'Semua kolom wajib diisi.';
                    errorDiv.style.display = 'flex';
                    return;
                }

                // Loading State
                submitBtn.disabled = true;
                btnText.textContent = 'Memproses...';
                btnSpinner.style.display = 'inline-block';

                try {
                    const response = await fetch('api/api_register.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            nama: nama,
                            email: email,
                            telepon: telepon,
                            password: password
                        })
                    });

                    // Validasi Content Type
                    const contentType = response.headers.get("content-type");
                    if (!contentType || !contentType.includes("application/json")) {
                        throw new Error("Terjadi kesalahan pada server (Respon bukan JSON).");
                    }

                    const result = await response.json();

                    if (result.status === 'success') {
                        // Sukses
                        btnText.textContent = 'Berhasil! Mengalihkan...';
                        submitBtn.style.background = '#10b981'; // Ubah warna jadi hijau (Inline style, akan menimpa CSS)
                        
                        // Redirect ke Login setelah 0.8 detik
                        setTimeout(() => {
                            window.location.href = 'login.php?status=registrasi_sukses';
                        }, 800);
                    } else {
                        // Gagal (Email duplikat, dll)
                        throw new Error(result.message || 'Gagal mendaftar.');
                    }
                } catch (error) {
                    // Tampilkan Error UI
                    errorDiv.style.display = 'flex';
                    errorText.textContent = error.message;
                    
                    // Reset Tombol
                    submitBtn.disabled = false;
                    btnText.textContent = 'Daftar Sekarang';
                    btnSpinner.style.display = 'none';
                    submitBtn.style.background = ''; // Reset warna default
                }
            });
        });
    </script>
</body>
</html>