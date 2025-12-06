<?php
require_once __DIR__ . '/api_common.php';
session_start();

// Set header untuk response JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Izinkan akses dari mana saja, sesuaikan jika perlu
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// 1. Sertakan koneksi database
include '../config/koneksi.php';

// Inisialisasi array response
$response = [];

// 2. Cek apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data JSON dari body request
    $data = json_decode(file_get_contents("php://input"));

    // 3. Validasi input
    if (empty($data->email) || empty($data->password)) {
        http_response_code(400); // Bad Request
        $response = [
            'status' => 'error',
            'message' => 'Email dan Password wajib diisi.'
        ];
    } else {
        $email = $data->email;
        $password = $data->password;

        // 4. Siapkan query untuk mencari user berdasarkan email
        $stmt = $koneksi->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // 5. Cek apakah user ditemukan
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // 6. Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Password cocok, login berhasil

                // 7. Simpan data user ke dalam session
                $_SESSION['status_login'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['tipe_user'] = $user['tipe_user'];

                // 8. Siapkan response sukses
                http_response_code(200); // OK
                $response = [
                    'status' => 'success',
                    'message' => 'Login berhasil.',
                    'data' => [
                        'user_id' => $user['id'],
                        'nama' => $user['nama'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'tipe_user' => $user['tipe_user']
                    ]
                ];
            } else {
                // Password salah
                http_response_code(401); // Unauthorized
                $response = ['status' => 'error', 'message' => 'Login gagal. Email atau Password salah.'];
            }
        } else {
            // Email tidak ditemukan
            http_response_code(401); // Unauthorized
            $response = ['status' => 'error', 'message' => 'Login gagal. Email atau Password salah.'];
        }

        $stmt->close();
    }
} else {
    // Metode request bukan POST
    http_response_code(405); // Method Not Allowed
    $response = ['status' => 'error', 'message' => 'Metode request tidak diizinkan.'];
}

$koneksi->close();

// 9. Tampilkan response dalam format JSON
echo json_encode($response);
?>