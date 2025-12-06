<?php
require_once __DIR__ . '/api_common.php';
session_start();
header('Content-Type: application/json');

include '../config/koneksi.php';

// --- FUNGSI UNTUK MENGIRIM RESPONSE ---
function send_response($status_code, $status, $message, $data = null) {
    http_response_code($status_code);
    $response = ['status' => $status, 'message' => $message];
    if ($data) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}

// Ambil semua subtest
$result = $koneksi->query("SELECT id, nama_subtest, singkatan FROM subtest ORDER BY id ASC");
$subtest_list = [];
while ($row = $result->fetch_assoc()) {
    $subtest_list[] = $row;
}
send_response(200, 'success', 'Data semua subtest berhasil diambil.', $subtest_list);

$koneksi->close();
?>