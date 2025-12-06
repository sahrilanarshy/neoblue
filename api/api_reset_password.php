<?php
require_once __DIR__ . '/api_common.php';
require_once 'bootstrap.php';
// PENAMBAHAN BARIS YANG HILANG: Include mail helper
require_once '../helpers/mail_helper.php';

// -> METHOD B: Ambil data dari JSON POST
$data = json_decode(file_get_contents('php://input'), true);

$token = $data['token'] ?? '';
$password = $data['password'] ?? '';
$password_confirm = $data['password_confirm'] ?? '';

// 1. Validasi Input Dasar
if (!$token || !$password || !$password_confirm) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Token dan password tidak boleh kosong.']);
    exit;
}

if ($password !== $password_confirm) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Konfirmasi password tidak cocok.']);
    exit;
}

if (strlen($password) < 8) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Password minimal 8 karakter.']);
    exit;
}

// 2. Validasi Token
$stmt = mysqli_prepare($koneksi, 'SELECT id, email FROM users WHERE reset_token = ? AND reset_token_expires > NOW()');
mysqli_stmt_bind_param($stmt, 's', $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    // Token valid, lanjutkan reset password
    $user_id = $row['id'];
    $user_email = $row['email'];
    
    // 3. Hash Password & Update Database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $update_stmt = mysqli_prepare($koneksi, 'UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?');
    mysqli_stmt_bind_param($update_stmt, 'si', $hashed_password, $user_id);

    if (mysqli_stmt_execute($update_stmt)) {
        // Berhasil, kirim notifikasi keamanan ke email pengguna.
        kirimEmail(
            $user_email, 
            'Password Akun NeoBlue Berhasil Diubah', 
            '<h3>Pemberitahuan Keamanan</h3><p>Halo,</p><p>Password untuk akun NeoBlue yang terhubung dengan email ini telah berhasil diubah. Jika Anda tidak merasa melakukan perubahan ini, harap segera amankan akun Anda atau hubungi support kami.</p><p>Terima kasih.</p>'
        );
        
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Password berhasil diubah.']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui password di database.']);
    }
} else {
    // Token tidak valid atau kedaluwarsa
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid atau sudah kedaluwarsa.']);
}

exit;