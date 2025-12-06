<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();
include 'koneksi.php';
ob_clean();

header('Content-Type: application/json');

try {
    // 1. Cek Method
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Method Not Allowed', 405);
    }

    // 2. Cek Parameter Wajib
    if (!isset($_POST['email'])) {
        throw new Exception('Parameter email wajib diisi', 400);
    }

    $email = $_POST['email'];

    // 3. Validasi Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Format email tidak valid', 400);
    }

    // 4. Cek apakah email ada di database
    $stmt = $koneksi->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt) {
        throw new Exception('Query Prepare Error (select): ' . $koneksi->error, 500);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Untuk keamanan, kita tetap berikan respon sukses meskipun email tidak ada
        // Ini untuk mencegah orang menebak-nebak email yang terdaftar
        echo json_encode(['status' => 'success', 'message' => 'Jika email terdaftar, instruksi reset password akan dikirim.']);
        $stmt->close();
        $koneksi->close();
        exit();
    }

    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    $stmt->close();

    // 5. Buat Token Reset
    $token = bin2hex(random_bytes(32)); 
    $expires = date("Y-m-d H:i:s", time() + 3600); // Token berlaku 1 jam

    // 6. Simpan Token ke Database (asumsi ada tabel password_resets)
    // Anda perlu membuat tabel ini: CREATE TABLE password_resets (email VARCHAR(255), token VARCHAR(255), created_at TIMESTAMP);
    $stmt_insert = $koneksi->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expires_at = ?");
    if (!$stmt_insert) {
        throw new Exception('Query Prepare Error (insert): ' . $koneksi->error, 500);
    }
    $stmt_insert->bind_param("sssss", $email, $token, $expires, $token, $expires);

    if ($stmt_insert->execute()) {
        // 7. Kirim Email (Contoh, logika pengiriman email tidak diimplementasikan di sini)
        // mail($email, "Reset Password", "Token Anda: $token");

        // Untuk sekarang, kita kembalikan token di response agar mudah di-debug
        echo json_encode([
            'status' => 'success', 
            'message' => 'Jika email terdaftar, instruksi reset password akan dikirim.',
            'debug_token' => $token // Hapus ini di produksi
        ]);
    } else {
        throw new Exception('Gagal menyimpan token reset: ' . $stmt_insert->error, 500);
    }
    $stmt_insert->close();

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$koneksi->close();
ob_end_flush();
?>