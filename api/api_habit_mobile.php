<?php
require_once __DIR__ . '/api_common.php';
// Endpoint mobile untuk mengambil daftar habit (tanpa pemeriksaan session web)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Tangani pre-flight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed. Use GET.']);
    exit;
}

include '../config/koneksi.php';

try {
    // Sesuaikan nama kolom agar kompatibel dengan model Android (id, tanggal, judul, tipe, status)
    // Tabel `habit_harian` tidak memiliki kolom `status`, jadi kembalikan kolom kosong untuk `status`.
    $query = "SELECT hh.id, hh.tanggal, hh.judul, hh.jenis AS tipe, '' AS status
              FROM habit_harian hh
              ORDER BY hh.tanggal DESC, hh.id DESC";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Data habit berhasil diambil (mobile).',
            'data' => $data
        ]);
    } else {
        throw new Exception(mysqli_error($koneksi));
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
    ]);
}

mysqli_close($koneksi);
?>
