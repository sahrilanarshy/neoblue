<?php
// reset-password.php
session_start();
require_once 'config/koneksi.php';
require_once 'helpers/mail_helper.php';

// Cek apakah user sudah verifikasi OTP dan emailnya ada di session
if (!isset($_SESSION['otp_verified']) || !isset($_SESSION['reset_email'])) {
    // Jika tidak, redirect ke halaman lupa password
    $_SESSION['reset_error'] = "Sesi tidak valid. Silakan ulangi proses lupa password.";
    header("Location: forgot-password.php");
    exit;
}

$error = '';
$success = '';
$show_form = true;
$email = $_SESSION['reset_email'];

// Proses form reset password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass1 = $_POST['password'];
    $pass2 = $_POST['password_confirm'];

    if ($pass1 !== $pass2) {
        $error = 'Konfirmasi password tidak cocok.';
    } elseif (strlen($pass1) < 8) {
        $error = 'Password minimal 8 karakter.';
    } else {
        // Hash password baru
        $hash = password_hash($pass1, PASSWORD_DEFAULT);
        
        // Update password di database dan hapus OTP
        $stmt = mysqli_prepare($koneksi, "UPDATE users SET password = ?, otp = NULL, otp_expires_at = NULL WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 'ss', $hash, $email);

        if (mysqli_stmt_execute($stmt)) {
            $success = 'Password berhasil diubah. Anda akan dialihkan ke halaman login...';
            $show_form = false;

            // Kirim email notifikasi bahwa password telah diubah
            kirimEmail($email, 'Password Akun NeoBlue Anda Telah Diubah', '<h3>Notifikasi Keamanan</h3><p>Password untuk akun NeoBlue yang terhubung dengan email ini telah berhasil diubah. Jika Anda tidak merasa melakukan perubahan ini, harap segera amankan akun Anda atau hubungi support kami.</p>');

            // Hapus session setelah selesai
            unset($_SESSION['otp_verified']);
            unset($_SESSION['reset_email']);
            
            // Redirect ke login setelah beberapa detik
            header("refresh:3;url=login.php");
        } else {
            $error = 'Gagal mengubah password. Silakan coba lagi.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - NeoBlue</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="assets/landingpage/img/logo/logo_neoblue.png" rel="icon">
    <link rel="stylesheet" href="assets/landingpage/css/style.css">
</head>

<body>
    <div class="login-wrapper">
        <div class="header-section">
            <img src="assets/landingpage/img/logo/logo neoblue.png" alt="NeoBlue Logo" class="logo-img">
            <h2>Buat Password Baru</h2>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($show_form): ?>
        <form method="POST" onsubmit="return showLoading()">
            <div class="form-group">
                <label for="password">Password Baru</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Minimal 8 karakter" required minlength="8">
                    <i class="fas fa-lock icon"></i>
                    <button type="button" class="toggle-password" onclick="togglePass('password', 'icon1')">
                        <i class="fas fa-eye" id="icon1"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirm">Konfirmasi Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password_confirm" name="password_confirm" class="form-control"
                        placeholder="Ulangi password baru" required minlength="8">
                    <i class="fas fa-lock icon"></i>
                    <button type="button" class="toggle-password" onclick="togglePass('password_confirm', 'icon2')">
                        <i class="fas fa-eye" id="icon2"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                <span id="btnText">Simpan Password</span>
                <span class="spinner" id="btnSpinner" style="display:none;"></span>
            </button>
        </form>
        <?php else: ?>
        <?php if (!$success): ?>
        <div style="text-align: center;">
            <p style="color:#666; margin-bottom:20px;">Link reset tidak valid atau kedaluwarsa.</p>
            <a href="forgot-password.php" class="btn-login" style="text-decoration:none;">Minta Link Baru</a>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        function togglePass(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function showLoading() {
            const p1 = document.getElementById('password').value;
            const p2 = document.getElementById('password_confirm').value;
            if (p1 !== p2) {
                alert("Password tidak cocok!");
                return false;
            }
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const spinner = document.getElementById('btnSpinner');
            btn.disabled = true;
            text.textContent = 'Menyimpan...';
            spinner.style.display = 'inline-block';
            return true;
        }
    </script>
</body>

</html>
