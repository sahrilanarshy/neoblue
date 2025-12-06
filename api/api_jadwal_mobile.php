<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
include '../config/koneksi.php';

// 1. TANGKAP INPUT DAN METHOD
$method = $_SERVER['REQUEST_METHOD'];
$input = [];
$user_id = 0;

// Ambil JSON body untuk POST dan DELETE
if ($method === 'POST' || $method === 'DELETE') {
    $raw_input = file_get_contents('php://input');
    $input = json_decode($raw_input, true);
}

// 2. TENTUKAN USER ID
if ($method === 'GET') {
    if (isset($_GET['user_id'])) {
        $user_id = intval($_GET['user_id']);
    }
} else {
    // Untuk POST/DELETE ambil dari JSON body
    if (isset($input['user_id'])) {
        $user_id = intval($input['user_id']);
    }
}

// 3. VALIDASI USER ID (Wajib untuk mobile)
if ($user_id <= 0) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized: user_id is required.']);
    exit();
}

try {
    switch ($method) {
        case 'GET':
            // --- LIHAT JADWAL ---
            $query = "SELECT j.id, j.hari, j.jam_mulai, j.jam_selesai, s.nama_subtest, s.singkatan 
                      FROM jadwal_belajar j 
                      JOIN subtest s ON j.subtest_id = s.id 
                      WHERE j.user_id = ? 
                      ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), j.jam_mulai";

            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $user_id);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo json_encode(['status' => 'success', 'data' => $data]);
            } else {
                throw new Exception('Gagal mengambil data.');
            }
            break;

        case 'POST':
            // --- TAMBAH JADWAL ---
            $hari = $input['hari'] ?? '';
            $subtest_id = isset($input['subtest_id']) ? intval($input['subtest_id']) : 0;
            $jam_mulai = $input['jam_mulai'] ?? '';
            $jam_selesai = $input['jam_selesai'] ?? '';

            if (empty($hari) || $subtest_id <= 0 || empty($jam_mulai) || empty($jam_selesai)) {
                http_response_code(400);
                throw new Exception('Data tidak lengkap.');
            }

            // Insert
            $stmt = mysqli_prepare($koneksi, 'INSERT INTO jadwal_belajar (user_id, subtest_id, hari, jam_mulai, jam_selesai) VALUES (?, ?, ?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'iisss', $user_id, $subtest_id, $hari, $jam_mulai, $jam_selesai);

            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil ditambahkan.']);
            } else {
                throw new Exception('Gagal menyimpan jadwal: ' . mysqli_error($koneksi));
            }
            break;

        case 'DELETE':
            // --- HAPUS JADWAL ---
            $id = isset($input['id']) ? intval($input['id']) : 0;
            if ($id <= 0) {
                http_response_code(400);
                throw new Exception('ID jadwal tidak valid.');
            }

            $stmt = mysqli_prepare($koneksi, 'DELETE FROM jadwal_belajar WHERE id = ? AND user_id = ?');
            mysqli_stmt_bind_param($stmt, 'ii', $id, $user_id);

            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    echo json_encode(['status' => 'success', 'message' => 'Jadwal dihapus.']);
                } else {
                    http_response_code(404);
                    throw new Exception('Jadwal tidak ditemukan.');
                }
            } else {
                throw new Exception('Gagal menghapus jadwal.');
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed (Only GET, POST, DELETE).']);
            break;
    }
} catch (Exception $e) {
    if (http_response_code() == 200) {
        http_response_code(500);
    }
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
