<?php
// Mulai session jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include koneksi database
require_once 'config/koneksi.php';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    if ($email) {
        $stmt = mysqli_prepare($koneksi, 'SELECT id FROM users WHERE email = ?');
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Di sini Anda bisa mengirim email reset password
            $success = 'Tautan reset password telah dikirim ke email Anda (simulasi).';
        } else {
            $error = 'Email tidak ditemukan.';
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = 'Email wajib diisi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neoblue</title>
    <link rel="stylesheet" href="assets/landingpage/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="assets/landingpage/img/logo/profile_neoblue.png" rel="icon">
    <link href="assets/landingpage/img/logo/profile_neoblue.png" type="image/pngrel=" apple-touch-icon>
</head>

<body>
    <div class="form-container">
        <div class="form-box">
            <h2>Lupa Password</h2>
            <p class="subtitle">Masukkan email untuk menerima tautan reset password.</p>
            <?php if ($error): ?>
                <div class="error-message" style="color:red; margin-bottom:10px;"> <?= htmlspecialchars($error) ?> </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success-message" style="color:green; margin-bottom:10px;"> <?= htmlspecialchars($success) ?> </div>
            <?php endif; ?>
            <form method="post" autocomplete="off">
                <div class="input-group">
                    <label for="forgot-email">Email</label>
                    <input type="email" id="forgot-email" name="email" required>
                </div>
                <button type="submit" class="btn">Kirim Tautan Reset</button>
                <a href="login.php" class="form-link">Kembali ke Login</a>
            </form>
        </div>
    </div>
</body>

</html>