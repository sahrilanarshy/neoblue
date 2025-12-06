<?php
// verify-otp.php
session_start();
require_once 'config/koneksi.php';

// Jika email tidak ada di session, redirect ke halaman lupa password
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot-password.php");
    exit;
}

$error = '';
$email = $_SESSION['reset_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);
    
    // Cek OTP
    $stmt = mysqli_prepare($koneksi, "SELECT id FROM users WHERE email = ? AND otp = ? AND otp_expires_at > NOW()");
    mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // OTP valid, tandai di session dan redirect ke halaman reset password
        $_SESSION['otp_verified'] = true;
        header("Location: reset-password.php");
        exit;
    } else {
        $error = "Kode OTP tidak valid atau sudah kedaluwarsa.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - NeoBlue</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="assets/landingpage/img/logo/logo_neoblue.png" rel="icon">
    <link rel="stylesheet" href="assets/landingpage/css/style.css">
    <style>
        .otp-input-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .otp-input {
            width: 45px;
            height: 50px;
            text-align: center;
            font-size: 1.5rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.2s;
        }
        .otp-input:focus {
            border-color: #4e73df;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="header-section">
            <img src="assets/landingpage/img/logo/logo neoblue.png" alt="NeoBlue Logo" class="logo-img">
            <h2>Verifikasi Kode OTP</h2>
            <p>Kami telah mengirimkan kode OTP ke <b><?= htmlspecialchars($email) ?></b>. Silakan periksa email Anda.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="return showLoading()">
            <div class="form-group">
                <label for="otp">Kode OTP (6 Digit)</label>
                <div class="input-wrapper">
                     <input type="text" id="otp" name="otp" class="form-control" placeholder="Masukkan 6 digit kode" required maxlength="6" pattern="\d{6}">
                     <i class="fas fa-key icon"></i>
                </div>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                <span id="btnText">Verifikasi</span>
                <span class="spinner" id="btnSpinner" style="display:none;"></span>
            </button>
        </form>

        <a href="forgot-password.php" class="back-link">
            <i class="fas fa-arrow-left me-1"></i> Kirim ulang kode
        </a>
    </div>

    <script>
        function showLoading() {
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const spinner = document.getElementById('btnSpinner');
            btn.disabled = true;
            text.textContent = 'Memverifikasi...';
            spinner.style.display = 'inline-block';
            return true;
        }
    </script>
</body>
</html>
