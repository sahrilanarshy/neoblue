<?php
// login.php

// Selalu mulai session di awal file, SEBELUM output apapun
session_start(); 

// 1. Inisialisasi variabel untuk pesan error dan sukses
$error = "";
$success = "";

// Cek apakah ada pesan sukses dari halaman registrasi
if (isset($_GET['status']) && $_GET['status'] == 'registrasi_sukses') {
    $success = "Registrasi berhasil! Silakan login.";
}

// 2. Cek apakah form telah disubmit (metode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Sertakan koneksi HANYA saat diperlukan
    include 'config/koneksi.php';

    // Ambil data dari form login
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 4. Validasi (Sederhana)
    if (empty($email) || empty($password)) {
        $error = "Email dan Password wajib diisi!";
    } else {
        // 5. Siapkan query untuk mencari user berdasarkan email
        $stmt = $koneksi->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // 6. Cek apakah user ditemukan
        if ($result->num_rows === 1) {
            // User ditemukan, ambil datanya
            $user = $result->fetch_assoc();

            // 7. Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Password cocok!
                
                // 8. Simpan data user ke dalam session
                $_SESSION['status_login'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['tipe_user'] = $user['tipe_user'];

                // 9. Logika Redirect berdasarkan ROLE
                if ($user['role'] === 'admin') {
                    header("Location: admin/index.php");
                    exit(); // Penting! Hentikan eksekusi script
                } else {
                    header("Location: user/index.php");
                    exit(); // Penting! Hentikan eksekusi script
                }

            } else {
                // Password salah
                // PERBAIKAN: Gunakan pesan error yang lebih umum untuk keamanan
                $error = "Login gagal. Email atau Password salah.";
            }
        } else {
            // Email tidak ditemukan
            // PERBAIKAN: Gunakan pesan error yang lebih umum untuk keamanan
            $error = "Login gagal. Email atau Password salah.";
        }

        $stmt->close();
        $koneksi->close();
    }
    // Jika validasi gagal atau login gagal, script akan lanjut ke HTML di bawah
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NeoBlue</title> <link rel="stylesheet" href="assets/landingpage/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="assets/landingpage/img/logo/profile_neoblue.png" rel="icon">
    <link href="assets/landingpage/img/logo/profile_neoblue.png" type="image/png" rel="apple-touch-icon"> 
</head>

<body>
    <div class="form-container">
        <div class="form-box">
            <img src="assets/landingpage/img/logo/logo neoblue.png" alt="Habitutor Logo" width="100" height="100">
            <div class="logo">
                <span class="logo-text">Login - NeoBlue</span>
            </div>
            <h2>Selamat Datang di NeoBlue</h2>
            <p class="subtitle">Belum punya akun? <a href="register.php">Daftar Sekarang</a></p>
            
            <?php if (!empty($success)): ?>
            <div class="success-message" style="color:green; margin-bottom:10px;"> <?= htmlspecialchars($success) ?> </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
            <div class="error-message" style="color:red; margin-bottom:10px;"> <?= htmlspecialchars($error) ?> </div>
            <?php endif; ?>
            
            <form method="post" action="" autocomplete="off">
                <div class="input-group">
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                <button type="submit" class="btn">Masuk</button>
                <a href="forgot-password.php" class="form-link">Lupa Password?</a>
            </form>
        </div>
    </div>
</body>

</html>