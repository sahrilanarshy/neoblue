<?php
require_once __DIR__ . '/api_common.php';
session_start();
header('Content-Type: application/json');

include '../config/koneksi.php';

// --- FUNGSI UNTUK MENGIRIM RESPONSE ---
function send_response($status_code, $status, $message, $data = null) {
    http_response_code($status_code);
    $response = ['status' => $status, 'message' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}

// --- AUTENTIKASI ---
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true || !isset($_SESSION['user_id'])) {
    send_response(401, 'error', 'Akses ditolak. Silakan login terlebih dahulu.');
}

$tipe_user = $_SESSION['tipe_user'] ?? 'Free'; // Ambil tipe user dari session

try {
    // Query untuk mengambil semua materi, beserta nama subtest dan status aksesibilitas
    $query = "
        SELECT 
            m.id, 
            m.judul, 
            m.tipe, 
            s.nama_subtest,
            (CASE 
                WHEN m.tipe = 'premium' AND ? = 'Premium' THEN 1
                WHEN m.tipe = 'free' THEN 1
                ELSE 0 
            END) as is_accessible
        FROM materi m
        JOIN subtest s ON m.subtest_id = s.id
        ORDER BY m.tanggal DESC, m.id DESC
    ";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $tipe_user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $materials = mysqli_fetch_all($result, MYSQLI_ASSOC);

    send_response(200, 'success', 'Data materi berhasil diambil.', $materials);

} catch (Exception $e) {
    send_response(500, 'error', 'Terjadi kesalahan pada server: ' . $e->getMessage());
} finally {
    if (isset($stmt)) mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
}
?>