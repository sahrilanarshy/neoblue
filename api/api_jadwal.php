<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
session_start();

include '../config/koneksi.php';

// Dukung dua mode akses:
// - Web (session): menggunakan $_SESSION['user_id'] dan status_login
// - Mobile (GET): Android dapat mengirimkan ?user_id= untuk mengambil jadwal (GET saja)
$method = $_SERVER['REQUEST_METHOD'];

// Ambil user_id dari session jika ada
$session_user_id = null;
if (isset($_SESSION['status_login']) && $_SESSION['status_login'] === true && isset($_SESSION['user_id'])) {
    $session_user_id = intval($_SESSION['user_id']);
}

// Untuk request GET, terima juga parameter user_id dari query (mobile clients)
// Untuk POST/DELETE tetap wajib memakai session (web clients)


try {
    switch ($method) {
        case 'GET':
            // --- AMBIL DATA ---
            // Prioritaskan query param user_id (untuk mobile). Jika tidak ada, pakai session.
            if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
                $user_id = intval($_GET['user_id']);
            } elseif ($session_user_id !== null) {
                $user_id = $session_user_id;
            } else {
                http_response_code(401); // Unauthorized
                echo json_encode(['status' => 'error', 'message' => 'Anda harus login untuk mengakses fitur ini.']);
                exit();
            }

            $query = "SELECT j.id, j.hari, j.jam_mulai, j.jam_selesai, s.nama_subtest, s.singkatan 
                    FROM jadwal_belajar j 
                    JOIN subtest s ON j.subtest_id = s.id 
                    WHERE j.user_id = ? 
                    ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), j.jam_mulai";

            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $user_id);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $schedules = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo json_encode(['status' => 'success', 'data' => $schedules]);
            } else {
                throw new Exception('Gagal mengambil data jadwal.');
            }
            break;

        case 'POST':
            // --- TAMBAH JADWAL ---
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            // Cek validitas JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                throw new Exception('Format JSON tidak valid.');
            }

            $hari = $data['hari'] ?? '';
            $subtest_id = isset($data['subtest_id']) ? intval($data['subtest_id']) : 0;
            $jam_mulai = $data['jam_mulai'] ?? '';
            $jam_selesai = $data['jam_selesai'] ?? '';

            // Validasi Input
            $allowed_days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            if (empty($hari) || !in_array($hari, $allowed_days) || $subtest_id <= 0 || empty($jam_mulai) || empty($jam_selesai)) {
                http_response_code(400);
                throw new Exception('Data tidak lengkap atau format hari salah.');
            }

            if (strtotime($jam_mulai) >= strtotime($jam_selesai)) {
                http_response_code(400);
                throw new Exception('Jam mulai harus lebih awal dari jam selesai.');
            }

            // Cek Tumpang Tindih (Conflict)
            // Untuk aksi penambahan jadwal, wajib menggunakan session (web).
            if ($session_user_id === null) {
                http_response_code(401);
                throw new Exception('Anda harus login untuk menambahkan jadwal.');
            }
            $user_id = $session_user_id;

            $stmt_check = mysqli_prepare(
                $koneksi,
                "SELECT id FROM jadwal_belajar 
                 WHERE user_id = ? AND hari = ? AND 
                 ((jam_mulai < ? AND jam_selesai > ?) OR (jam_mulai >= ? AND jam_mulai < ?))"
            );
            mysqli_stmt_bind_param($stmt_check, 'isssss', $user_id, $hari, $jam_selesai, $jam_mulai, $jam_mulai, $jam_selesai);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                http_response_code(409); // Conflict
                throw new Exception('Jadwal bertabrakan dengan jadwal yang sudah ada.');
            }

            // Insert Data
            $stmt_insert = mysqli_prepare($koneksi, 'INSERT INTO jadwal_belajar (user_id, subtest_id, hari, jam_mulai, jam_selesai) VALUES (?, ?, ?, ?, ?)');
            mysqli_stmt_bind_param($stmt_insert, 'iisss', $user_id, $subtest_id, $hari, $jam_mulai, $jam_selesai);

            if (mysqli_stmt_execute($stmt_insert)) {
                $new_id = mysqli_insert_id($koneksi);

                // Ambil data detail untuk dikembalikan ke frontend
                $stmt_new = mysqli_prepare(
                    $koneksi,
                    "SELECT j.id, j.hari, j.jam_mulai, j.jam_selesai, s.nama_subtest, s.singkatan 
                     FROM jadwal_belajar j 
                     JOIN subtest s ON j.subtest_id = s.id 
                     WHERE j.id = ?"
                );
                mysqli_stmt_bind_param($stmt_new, 'i', $new_id);
                mysqli_stmt_execute($stmt_new);
                $new_schedule = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_new));

                http_response_code(201); // Created
                echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil ditambahkan.', 'data' => $new_schedule]);
            } else {
                throw new Exception('Gagal menyimpan jadwal ke database.');
            }
            break;

        case 'DELETE':
            // --- HAPUS JADWAL ---
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                throw new Exception('Format JSON tidak valid.');
            }

            $id = isset($data['id']) ? intval($data['id']) : 0;

            if ($id <= 0) {
                http_response_code(400);
                throw new Exception('ID jadwal tidak valid.');
            }

            // Hapus dengan validasi kepemilikan (user_id)
            // Hapus hanya diizinkan untuk user yang login via session (web)
            if ($session_user_id === null) {
                http_response_code(401);
                throw new Exception('Anda harus login untuk menghapus jadwal.');
            }
            $user_id = $session_user_id;

            $stmt_delete = mysqli_prepare($koneksi, 'DELETE FROM jadwal_belajar WHERE id = ? AND user_id = ?');
            mysqli_stmt_bind_param($stmt_delete, 'ii', $id, $user_id);

            if (mysqli_stmt_execute($stmt_delete)) {
                if (mysqli_stmt_affected_rows($stmt_delete) > 0) {
                    echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil dihapus.']);
                } else {
                    http_response_code(404);
                    throw new Exception('Jadwal tidak ditemukan atau Anda tidak memiliki akses.');
                }
            } else {
                throw new Exception('Gagal menghapus jadwal.');
            }
            break;

        default:
            http_response_code(405); // Method Not Allowed
            throw new Exception('Metode request tidak diizinkan.');
    }
} catch (Exception $e) {
    // Tangkap semua error di sini agar format JSON tetap terjaga
    // Jika http_response_code belum di-set selain 200, set ke 500
    if (http_response_code() == 200) {
        http_response_code(500);
    }
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

mysqli_close($koneksi);
?>
