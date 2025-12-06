<?php
require_once __DIR__ . '/api_common.php';

// 1. Sertakan koneksi database
include __DIR__ . '/../config/koneksi.php';

// 2. Mulai proses utama dengan pengecekan method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(405, 'error', 'Metode request tidak diizinkan.');
}

$raw = file_get_contents('php://input');
$data = json_decode($raw);

if (json_last_error() !== JSON_ERROR_NONE) {
    // Jika body tidak JSON valid, kirim pesan error yang jelas
    json_response(400, 'error', 'Payload JSON tidak valid.');
}

// Validasi input
if (empty($data->email) || empty($data->password)) {
    json_response(400, 'error', 'Email dan Password wajib diisi.');
}

$email = $data->email;
$password = $data->password;

try {
    $stmt = $koneksi->prepare('SELECT * FROM users WHERE email = ?');
    if (!$stmt) {
        throw new Exception('Gagal menyiapkan statement SQL.');
    }
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $userData = [
                'user_id' => $user['id'],
                'nama' => $user['nama'],
                'email' => $user['email'],
                'role' => $user['role'],
                'tipe_user' => $user['tipe_user']
            ];
            json_response(200, 'success', 'Login berhasil.', $userData);
        } else {
            // Password salah
            json_response(401, 'error', 'Login gagal. Email atau Password salah.');
        }
    } else {
        // Email tidak ditemukan
        json_response(401, 'error', 'Login gagal. Email atau Password salah.');
    }
} catch (Exception $e) {
    error_log('Login exception: ' . $e->getMessage());
    json_response(500, 'error', 'Internal server error.');
} finally {
    if (isset($stmt) && $stmt) $stmt->close();
    if (isset($koneksi) && $koneksi) $koneksi->close();
}

?>