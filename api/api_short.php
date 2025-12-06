<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
include '../config/koneksi.php';

$response = [
    'status' => 'error',
    'message' => 'Terjadi kesalahan yang tidak diketahui.',
    'data' => []
];

try {
    $query = "SELECT id, judul, tanggal_upload, tipe, video_path FROM shorts ORDER BY tanggal_upload DESC, id DESC";
    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        throw new Exception("Query Error: " . mysqli_error($koneksi));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    $response['status'] = 'success';
    $response['message'] = 'Data berhasil diambil.';
    $response['data'] = $data;
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>