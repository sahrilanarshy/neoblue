<?php
require_once __DIR__ . '/api_common.php';
// api/api_register.php

// 1. Sertakan koneksi database
include '../config/koneksi.php';

// Inisialisasi array response
$response = [];

// 2. Cek apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data JSON dari body request
    $data = json_decode(file_get_contents("php://input"));

    // 3. Validasi input
    if (empty($data->nama) || empty($data->email) || empty($data->telepon) || empty($data->password)) {
        http_response_code(400); // Bad Request
        $response = [
            'status' => 'error',
            'message' => 'Semua field wajib diisi.'
        ];
    } elseif (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400); // Bad Request
        $response = [
            'status' => 'error',
            'message' => 'Format email tidak valid.'
        ];
    } else {
        // 4. Cek apakah email sudah terdaftar
        $stmt_check = $koneksi->prepare("SELECT email FROM users WHERE email = ?");
        $stmt_check->bind_param("s", $data->email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            http_response_code(409); // Conflict
            $response = [
                'status' => 'error',
                'message' => 'Email sudah terdaftar. Silakan gunakan email lain.'
            ];
        } else {
            // 5. HASH PASSWORD
            $hashed_password = password_hash($data->password, PASSWORD_DEFAULT);

            // 6. Insert ke database
            $stmt_insert = $koneksi->prepare("INSERT INTO users (nama, email, telepon, password) VALUES (?, ?, ?, ?)");
            $stmt_insert->bind_param("ssss", $data->nama, $data->email, $data->telepon, $hashed_password);

            if ($stmt_insert->execute()) {
                http_response_code(201); // Created
                $response = [
                    'status' => 'success',
                    'message' => 'Registrasi berhasil! Anda akan diarahkan ke halaman login.'
                ];
            } else {
                http_response_code(500); // Internal Server Error
                $response = ['status' => 'error', 'message' => 'Registrasi gagal. Terjadi kesalahan pada server.'];
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
} else {
    http_response_code(405); // Method Not Allowed
    $response = ['status' => 'error', 'message' => 'Metode request tidak diizinkan.'];
}

$koneksi->close();

// 7. Tampilkan response dalam format JSON
echo json_encode($response);
?>