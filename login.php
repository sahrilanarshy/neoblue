<?php
session_start();

// 1. Inisialisasi variabel untuk pesan error dan sukses
$error = '';
$success = '';

// Cek apakah ada pesan sukses dari halaman registrasi
if (isset($_GET['status']) && $_GET['status'] == 'registrasi_sukses') {
    $success = 'Registrasi berhasil! Silakan login untuk melanjutkan.';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NeoBlue Platform</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="assets/user/img/profile_neoblue.png" rel="icon">
    <link rel="stylesheet" href="assets/landingpage/css/style.css">


</head>

<body>

    <div class="login-wrapper">
        <div class="header-section">
            <img src="assets/landingpage/img/logo/logo neoblue.png" alt="NeoBlue Logo" class="logo-img">
            <h2>Welcome Back!</h2>
            <p>Belum punya akun? <a href="register.php">Daftar Sekarang</a></p>
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> 
                <span><?= htmlspecialchars($success) ?></span>
            </div>
        <?php endif; ?>
        
        <div class="alert alert-error" style="display:none;" id="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span id="error-text"></span>
        </div>

        <form id="loginForm" autocomplete="off">
            <div class="form-group">
                <label for="login-email">Email Address</label>
                <div class="input-wrapper">
                    <input type="email" id="login-email" name="email" class="form-control" placeholder="Masukan email anda" required>
                    <i class="fas fa-envelope icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="login-password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="login-password" name="password" class="form-control" placeholder="Masukan password anda" required>
                    <i class="fas fa-lock icon"></i>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
                <a href="forgot-password.php" class="forgot-link">Lupa Password?</a>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                <span id="btnText">Masuk Sekarang</span>
                <span class="spinner" id="btnSpinner" style="display:none;"></span>
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 25px;">
            <p style="font-size: 12px; color: #aaa;">&copy; <?= date('Y'); ?> NeoBlue Platform. All rights reserved.</p>
        </div>
    </div>

    <script>
        // 1. Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('login-password');
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

        // 2. Login Logic
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const errorDiv = document.getElementById('alert-error');
            const errorText = document.getElementById('error-text');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            form.addEventListener('submit', async function(event) {
                event.preventDefault();

                // Reset Error
                errorDiv.style.display = 'none';
                
                // Loading State
                submitBtn.disabled = true;
                btnText.textContent = 'Memverifikasi...';
                btnSpinner.style.display = 'inline-block';

                const email = document.getElementById('login-email').value;
                const password = document.getElementById('login-password').value;

                try {
                    // Panggil API
                    const response = await fetch('api/api_login.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ email: email, password: password })
                    });

                    // Cek jika response bukan JSON valid
                    const contentType = response.headers.get("content-type");
                    if (!contentType || !contentType.includes("application/json")) {
                        throw new Error("Respon server tidak valid (bukan JSON).");
                    }

                    const result = await response.json();

                    if (response.ok && result.status === 'success') {
                        // Redirect Logic
                        const role = result.data.role;
                        
                        // Efek sukses sebentar
                        btnText.textContent = 'Berhasil! Mengalihkan...';
                        submitBtn.style.background = '#10b981'; // Green

                        setTimeout(() => {
                            if (role === 'admin') window.location.href = 'admin/index.php';
                            else if (role === 'guru') window.location.href = 'guru/index.php';
                            else window.location.href = 'user/index.php';
                        }, 800);

                    } else {
                        // Tampilkan Error
                        throw new Error(result.message || 'Email atau password salah.');
                    }
                } catch (error) {
                    // Tampilkan Error UI
                    errorDiv.style.display = 'flex';
                    errorText.textContent = error.message;
                    
                    // Reset Tombol
                    submitBtn.disabled = false;
                    btnText.textContent = 'Masuk Sekarang';
                    btnSpinner.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>