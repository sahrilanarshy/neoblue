<?php
// register.php

// 1. Inisialisasi variabel untuk pesan dan "sticky form"
$error = "";
$success = "";
$nama = "";
$email = "";
$no_telp = ""; // Sesuaikan nama variabel ini

// 2. Cek apakah form disubmit (method POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Sertakan koneksi HANYA saat diperlukan
    include 'config/koneksi.php';

    // 4. Ambil data dari form dan masukkan ke variabel
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $telepon = $_POST['no_telp']; // PERBAIKAN: Sesuaikan dengan 'name' di HTML
    $password = $_POST['password'];

    // 5. Validasi (Sederhana)
    if (empty($nama) || empty($email) || empty($password) || empty($telepon)) {
        $error = "Semua field wajib diisi!";
    } else {
        // 6. Cek apakah email sudah terdaftar
        $stmt = $koneksi->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email sudah terdaftar. Silakan gunakan email lain atau login.";
            $stmt->close();
        } else {
            $stmt->close(); // Tutup statement pengecekan

            // 7. HASH PASSWORD
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // 8. Insert ke database
            $stmt_insert = $koneksi->prepare("INSERT INTO users (nama, email, telepon, password) VALUES (?, ?, ?, ?)");
            // PERBAIKAN: Pastikan urutan dan tipe datanya benar (nama, email, telepon, password)
            $stmt_insert->bind_param("ssss", $nama, $email, $telepon, $hashed_password);

            if ($stmt_insert->execute()) {
                // Registrasi berhasil, JANGAN 'echo' apapun.
                // Langsung arahkan (redirect) ke halaman login.
                header("Location: login.php?status=registrasi_sukses"); // 'status' opsional
                exit(); // Penting untuk menghentikan eksekusi script setelah redirect
            } else {
                $error = "Registrasi gagal: " . $stmt_insert->error;
            }
            $stmt_insert->close();
        }
    }
    // Tutup koneksi setelah semua operasi POST selesai
    $koneksi->close();
}
// Jika BUKAN method POST, script PHP di atas akan dilewati dan langsung menampilkan HTML di bawah.
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - NeoBlue</title> <link rel="stylesheet" href="assets/landingpage/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="assets/landingpage/img/logo/profile_neoblue.png" rel="icon">
    <link href="assets/landingpage/img/logo/profile_neoblue.png" type="image/png" rel="apple-touch-icon"> </head>

<body>
    <div class="form-container">
        <div class="form-box">
            <img src="assets/landingpage/img/logo/logo neoblue.png" alt="Habitutor Logo" width="100" height="100">
            <div class="logo">
                <span class="logo-text">Registrasi - NeoBlue</span>
            </div>
            <h2>Langkah Awal Menuju PTN Impian</h2>
            <p class="subtitle">Dimulai dari Sekarang</p>

            <?php if (!empty($error)): ?>
                <div class="error-message" style="color:red; margin-bottom:10px;"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="success-message" style="color:green; margin-bottom:10px;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="post" action="" autocomplete="off">
                <div class="input-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>" required>
                </div>
                <div class="input-group">
                    <label for="register-email">Email</label>
                    <input type="email" id="register-email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                </div>
                <div class="input-group">
                    <label for="telepon">Telepon</label>
                    <input type="tel" id="telepon" name="no_telp" value="<?= htmlspecialchars($no_telp) ?>" required pattern="[0-9]+" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                </div>
                <div class="input-group">
                    <label for="register-password">Password</label>
                    <input type="password" id="register-password" name="password" required>
                </div>
                <button type="submit" class="btn">Daftar</button>
                <p class="bottom-text">Sudah punya akun? <a href="login.php">Masuk Sekarang</a></p>
            </form>
        </div>
    </div>
</body>

</html>