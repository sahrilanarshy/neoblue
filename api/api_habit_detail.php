<?php
require_once __DIR__ . '/api_common.php';
// Mobile-friendly endpoint: ambil detail habit berdasarkan id
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Tangani preflight
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

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Parameter id (habit id) diperlukan.']);
    exit;
}

try {
    // Ambil data habit utama
    $stmt = $koneksi->prepare("SELECT hh.id, hh.tanggal, hh.judul, hh.jenis, hh.isi_bacaan, s.nama_subtest FROM habit_harian hh LEFT JOIN subtest s ON hh.subtest_id = s.id WHERE hh.id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $habit = $res ? $res->fetch_assoc() : null;

    if (!$habit) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Habit tidak ditemukan.']);
        exit;
    }

    $responseData = [
        'id' => (string)$habit['id'],
        'tanggal' => $habit['tanggal'],
        'judul' => $habit['judul'],
        'tipe' => $habit['jenis'],
        'subtest' => $habit['nama_subtest'] ?? null
    ];

    if ($habit['jenis'] === 'bacaan') {
        // kembalikan isi bacaan
        $responseData['isi'] = $habit['isi_bacaan'] ?? '';
    } else if ($habit['jenis'] === 'soal') {
        // ambil semua soal terkait
        $stmt2 = $koneksi->prepare("SELECT id, pertanyaan, pilihan_a, pilihan_b, pilihan_c, pilihan_d, pilihan_e, kunci_jawaban, pembahasan FROM habit_soal WHERE habit_id = ? ORDER BY id ASC");
        $stmt2->bind_param('i', $id);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        $soal_list = [];
        if ($res2) {
            while ($row = $res2->fetch_assoc()) {
                $soal_list[] = [
                    'id' => (string)$row['id'],
                    'pertanyaan' => $row['pertanyaan'],
                    'pilihan_a' => $row['pilihan_a'],
                    'pilihan_b' => $row['pilihan_b'],
                    'pilihan_c' => $row['pilihan_c'],
                    'pilihan_d' => $row['pilihan_d'],
                    'pilihan_e' => $row['pilihan_e'],
                    'kunci_jawaban' => $row['kunci_jawaban'],
                    'pembahasan' => $row['pembahasan']
                ];
            }
        }
        $responseData['soal'] = $soal_list;
    }

    echo json_encode(['status' => 'success', 'message' => 'Detail habit berhasil diambil.', 'data' => $responseData]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}

$koneksi->close();
?>
