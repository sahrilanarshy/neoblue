<?php
require_once __DIR__ . '/api_common.php';
// Set header untuk response JSON dan CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Tangani pre-flight request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'bootstrap.php';
require_once '../helpers/mail_helper.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';

if (empty($email)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Email tidak boleh kosong.']);
    exit;
}

// Cek Email
$stmt = mysqli_prepare($koneksi, "SELECT id, nama FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    // Generate OTP (6 Digit Angka)
    $token = sprintf("%06d", mt_rand(1, 999999));

    // Simpan OTP ke database menggunakan waktu server MySQL agar sinkron dengan pengecekan NOW()
    // Perhatikan: reset_token_expires diset pakai DATE_ADD(NOW(), INTERVAL 1 HOUR)
    $update = mysqli_prepare($koneksi, "UPDATE users SET reset_token = ?, reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?");
    mysqli_stmt_bind_param($update, "si", $token, $row['id']);
    
    if (mysqli_stmt_execute($update)) {
        // Kirim Email berisi OTP
        $pesan = "
            <div style='font-family: Arial, sans-serif; color: #333;'>
                <h3>Reset Password NeoBlue</h3>
                <p>Halo <b>{$row['nama']}</b>,</p>
                <p>Gunakan kode OTP berikut untuk mereset password akun Anda:</p>
                <div style='background:#f4f6f8; padding: 15px; border-radius: 8px; text-align: center; margin: 20px 0;'>
                    <span style='font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #4e73df;'>{$token}</span>
                </div>
                <p><i>Kode ini berlaku selama 1 jam. Jangan berikan kode ini kepada siapapun.</i></p>
            </div>
        ";
        
        if (kirimEmail($email, "Kode OTP Reset Password", $pesan)) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Kode OTP telah dikirim ke email Anda.']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim email.']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Gagal membuat token reset.']);
    }
} else {
    // Demi keamanan, tetap kembalikan sukses walau email tidak ditemukan
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Jika email terdaftar, kode OTP akan dikirim.']);
}
exit;