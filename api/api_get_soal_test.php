<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
include '../config/koneksi.php';

$id_tryout = 0;
$subtest_id = 0;

// Coba ambil dari GET
if (isset($_GET['id_tryout']) && isset($_GET['subtest_id'])) {
    $id_tryout = intval($_GET['id_tryout']);
    $subtest_id = intval($_GET['subtest_id']);
}
// Jika tidak ada di GET dan methodnya POST, coba ambil dari POST body
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek jika datanya form-data
    if (isset($_POST['id_tryout']) && isset($_POST['subtest_id'])) {
        $id_tryout = intval($_POST['id_tryout']);
        $subtest_id = intval($_POST['subtest_id']);
    } else {
        // Jika bukan form-data, mungkin JSON
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['id_tryout']) && isset($data['subtest_id'])) {
            $id_tryout = intval($data['id_tryout']);
            $subtest_id = intval($data['subtest_id']);
        }
    }
}

if ($id_tryout <= 0 || $subtest_id <= 0) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'ID Tryout atau Subtest tidak valid.']);
    exit;
}

// Cek apakah tryout sudah berakhir (server-side guard)
$stmt_check_tryout = mysqli_prepare($koneksi, "SELECT tanggal_selesai FROM tryout WHERE id = ?");
mysqli_stmt_bind_param($stmt_check_tryout, "i", $id_tryout);
mysqli_stmt_execute($stmt_check_tryout);
$res_check_tryout = mysqli_stmt_get_result($stmt_check_tryout);
$tryout_row = mysqli_fetch_assoc($res_check_tryout);
if ($tryout_row && !empty($tryout_row['tanggal_selesai'])) {
    $tanggal_selesai = $tryout_row['tanggal_selesai'];
    $today = date('Y-m-d');
    if ($tanggal_selesai < $today) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Tryout telah berakhir pada ' . $tanggal_selesai]);
        exit;
    }
}

// Ambil detail subtest (nama dan waktu)
$stmt_subtest = mysqli_prepare($koneksi, "
    SELECT s.nama_subtest, ts.waktu_pengerjaan 
    FROM subtest s
    LEFT JOIN tryout_subtest ts ON s.id = ts.subtest_id AND ts.tryout_id = ?
    WHERE s.id = ?
");
mysqli_stmt_bind_param($stmt_subtest, "ii", $id_tryout, $subtest_id);
mysqli_stmt_execute($stmt_subtest);
$result_subtest = mysqli_stmt_get_result($stmt_subtest);
$subtest_details = mysqli_fetch_assoc($result_subtest);

if (!$subtest_details) {
    http_response_code(404); // Not Found
    echo json_encode(['status' => 'error', 'message' => 'Detail subtest tidak ditemukan.']);
    exit;
}

$stmt_soal = mysqli_prepare($koneksi, "
    SELECT id, konteks_soal, pertanyaan, pilihan_a, pilihan_b, pilihan_c, pilihan_d, pilihan_e, kunci_jawaban
    FROM soal_tryout 
    WHERE tryout_id = ? AND subtest_id = ? 
    ORDER BY id ASC
");
mysqli_stmt_bind_param($stmt_soal, "ii", $id_tryout, $subtest_id);
mysqli_stmt_execute($stmt_soal);
$result_soal = mysqli_stmt_get_result($stmt_soal);
$questions = mysqli_fetch_all($result_soal, MYSQLI_ASSOC);

// Debug logging: record incoming params and how many questions found
error_log("[api_get_soal_test] id_tryout=" . $id_tryout . " subtest_id=" . $subtest_id . " questions_found=" . count($questions));

$response = [
    'status' => 'success',
    'subtest_name' => $subtest_details['nama_subtest'],
    'waktu_pengerjaan' => (int) $subtest_details['waktu_pengerjaan'], // dalam menit
    'questions' => $questions
];

echo json_encode($response);
exit;

?>