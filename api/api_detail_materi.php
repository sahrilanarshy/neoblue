<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
require_once '../config/koneksi.php';

// 1. Ambil ID subtest dari request
$subtest_id = isset($_GET['subtest_id']) ? intval($_GET['subtest_id']) : 0;

if ($subtest_id <= 0) {
    echo json_encode(['error' => 'ID Subtest tidak valid.']);
    exit;
}

// 2. Ambil data subtest
$stmt_subtest = mysqli_prepare($koneksi, "SELECT nama_subtest, singkatan FROM subtest WHERE id = ?");
mysqli_stmt_bind_param($stmt_subtest, "i", $subtest_id);
mysqli_stmt_execute($stmt_subtest);
$result_subtest = mysqli_stmt_get_result($stmt_subtest);
$subtest = mysqli_fetch_assoc($result_subtest);

if (!$subtest) {
    echo json_encode(['error' => 'Subtest tidak ditemukan.']);
    exit;
}

// 3. Ambil semua materi yang terkait dengan subtest ini
$stmt_materi = mysqli_prepare($koneksi, "SELECT id, judul, tipe FROM materi WHERE subtest_id = ? ORDER BY id ASC");
mysqli_stmt_bind_param($stmt_materi, "i", $subtest_id);
mysqli_stmt_execute($stmt_materi);
$result_materi = mysqli_stmt_get_result($stmt_materi);
$materi_list = mysqli_fetch_all($result_materi, MYSQLI_ASSOC);

// 4. Siapkan data untuk output JSON
$output = [
    'subtest' => $subtest,
    'materi' => $materi_list
];

echo json_encode($output);

mysqli_close($koneksi);
?>