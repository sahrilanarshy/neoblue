<?php
// forgot-password.php
session_start();
require_once 'config/koneksi.php';
require_once 'helpers/mail_helper.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    // Cek Email
    $stmt = mysqli_prepare($koneksi, "SELECT id, nama FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Generate OTP
        $otp = random_int(100000, 999999);
        
        // Simpan OTP dan set masa berlaku menggunakan fungsi SQL untuk konsistensi waktu
        $update = mysqli_prepare($koneksi, "UPDATE users SET otp = ?, otp_expires_at = NOW() + INTERVAL 5 MINUTE WHERE id = ?");
        mysqli_stmt_bind_param($update, "si", $otp, $row['id']);
        mysqli_stmt_execute($update);
        
        // Kirim Email
        $pesan = "
            <h3>Reset Password NeoBlue</h3>
            <p>Halo <b>{$row['nama']}</b>,</p>
            <p>Berikut adalah kode OTP untuk mereset password Anda:</p>
            <h1 style='font-size: 3em; letter-spacing: 5px; margin: 20px 0;'><b>{$otp}</b></h1>
            <p><i>Kode OTP ini berlaku selama 5 menit.</i></p>
        ";
        
        if (kirimEmail($email, "Kode OTP Reset Password", $pesan)) {
            // Simpan email di session dan redirect ke halaman verifikasi OTP
            $_SESSION['reset_email'] = $email;
            header("Location: verify-otp.php");
            exit;
        } else {
            $error = "Gagal mengirim email (Cek konfigurasi SMTP).";
        }
    } else {
        // Pesan generik demi keamanan
        $error = "Jika email terdaftar, instruksi reset akan dikirim.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - NeoBlue</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="assets/landingpage/img/logo/logo_neoblue.png" rel="icon">
    <link rel="stylesheet" href="assets/landingpage/css/style.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="header-section">
            <img src="assets/landingpage/img/logo/logo neoblue.png" alt="NeoBlue Logo" class="logo-img">
            <h2>Lupa Password</h2>
            <p>Masukkan email untuk mendapatkan kode OTP.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="return showLoading()">
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email terdaftar" required>
                    <i class="fas fa-envelope icon"></i>
                </div>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                <span id="btnText">Kirim Kode OTP</span>
                <span class="spinner" id="btnSpinner" style="display:none;"></span>
            </button>
        </form>

        <a href="login.php" class="back-link">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
        </a>
    </div>

    <script>
        function showLoading() {
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const spinner = document.getElementById('btnSpinner');
            btn.disabled = true;
            text.textContent = 'Memproses...';
            spinner.style.display = 'inline-block';
            return true;
        }
    </script>
</body>
</html>