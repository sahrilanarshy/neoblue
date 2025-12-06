<?php
require_once __DIR__ . '/api_common.php';
// api_materi_tryout.php - Final Clean Version
// Menggunakan Output Buffering untuk mencegah error JSON malformed
// Menggunakan Single Query LEFT JOIN untuk mencegah error 500

ob_start(); // Mulai buffering output

error_reporting(E_ALL);
ini_set('display_errors', 0); // Jangan tampilkan error PHP ke output

// Sertakan file koneksi/bootstrap
include_once __DIR__ . '/bootstrap.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// BERSIHKAN SEMUA OUTPUT SEBELUMNYA (spasi, warning, echo liar dari bootstrap.php)
if (ob_get_length()) ob_clean();

// Fungsi helper untuk mengirim respon JSON yang bersih
function send_clean_json($http_code, $data) {
    // Bersihkan buffer sekali lagi untuk memastikan
    if (ob_get_length()) ob_clean();
    
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($http_code);
    echo json_encode($data);
    exit;
}

try {
    // 1. Ambil & Validasi Parameter
    // Terima `tryout_id` atau alias `id` untuk kompatibilitas dengan halaman web
    $tryout_id = 0;
    if (isset($_GET['tryout_id'])) {
        $tryout_id = intval($_GET['tryout_id']);
    } elseif (isset($_GET['id'])) {
        $tryout_id = intval($_GET['id']);
    }
    
    // Ambil User ID (Prioritas Session, Fallback ke GET untuk Android)
    $user_id = 0;
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } elseif (isset($_GET['user_id'])) {
        $user_id = intval($_GET['user_id']);
    }

    if ($tryout_id <= 0) {
        // Return JSON error but keep HTTP 200 so mobile can parse the body
        send_clean_json(200, ['status' => 'error', 'message' => 'ID Tryout tidak valid.']);
    }

    // Pastikan variabel koneksi tersedia
    if (!isset($koneksi)) {
        throw new Exception("Koneksi database ($koneksi) tidak ditemukan.");
    }

    // 2. Ambil metadata tryout (nama, tanggal, tipe) untuk dikirim ke mobile
    $tryout_meta = null;
    $stmt_tryout = $koneksi->prepare("SELECT id, nama_tryout, tanggal_mulai, tanggal_selesai, tipe FROM tryout WHERE id = ? LIMIT 1");
    if ($stmt_tryout) {
        $stmt_tryout->bind_param('i', $tryout_id);
        $stmt_tryout->execute();
        $res_tryout = $stmt_tryout->get_result();
        if ($row_tryout = $res_tryout->fetch_assoc()) {
            $tryout_meta = [
                'id' => (int)$row_tryout['id'],
                'nama_tryout' => $row_tryout['nama_tryout'],
                'tanggal_mulai' => $row_tryout['tanggal_mulai'],
                'tanggal_selesai' => $row_tryout['tanggal_selesai'],
                'tipe' => $row_tryout['tipe']
            ];
        }
        $stmt_tryout->close();
    }

    // 3. Ambil data subtest dengan COUNT soal dan waktu pengerjaan (tryout_subtest)
    $sql = "
        SELECT
            s.id AS subtest_id,
            s.nama_subtest,
            COALESCE(ts.waktu_pengerjaan, t.waktu_pengerjaan) AS waktu_pengerjaan,
            COUNT(st.id) AS jumlah_soal,
            IF(uts.id IS NOT NULL, 1, 0) AS status,
            uts.score AS score,
            uts.correct_count AS correct_count,
            uts.incorrect_count AS incorrect_count,
            uts.unanswered_count AS unanswered_count,
            uts.total_questions AS total_questions,
            uts.id AS session_id
        FROM subtest s
        JOIN soal_tryout st ON st.subtest_id = s.id AND st.tryout_id = ?
        LEFT JOIN tryout_subtest ts ON ts.tryout_id = ? AND ts.subtest_id = s.id
        LEFT JOIN tryout t ON t.id = ?
        LEFT JOIN user_tryout_sessions uts ON s.id = uts.subtest_id AND uts.user_id = ? AND uts.tryout_id = ?
        GROUP BY s.id, s.nama_subtest, COALESCE(ts.waktu_pengerjaan, t.waktu_pengerjaan), uts.id, uts.score
        ORDER BY s.id ASC
    ";
    $stmt = $koneksi->prepare($sql);
    if (!$stmt) {
        throw new Exception("DB Prepare Error: " . $koneksi->error);
    }

    // Bind parameter order: st.tryout_id, ts.tryout_id, tryout.id (for fallback), uts.user_id, uts.tryout_id
    $stmt->bind_param('iiiii', $tryout_id, $tryout_id, $tryout_id, $user_id, $tryout_id);

    if (!$stmt->execute()) {
        throw new Exception("DB Execute Error: " . $stmt->error);
    }

    $res = $stmt->get_result();

    $data = [];
    while ($row = $res->fetch_assoc()) {
        $data[] = [
            'subtest_id' => (int)$row['subtest_id'],
            'nama_subtest' => $row['nama_subtest'],
            'jumlah_soal' => (int)$row['jumlah_soal'],
            'waktu_pengerjaan' => isset($row['waktu_pengerjaan']) ? (int)$row['waktu_pengerjaan'] : null,
            'status' => (int)$row['status'],
            'score' => $row['score'] !== null ? (float)$row['score'] : null,
            'score_display' => $row['score'] !== null ? number_format((float)$row['score'], 2) : null,
            'correct_count' => isset($row['correct_count']) ? (int)$row['correct_count'] : null,
            'incorrect_count' => isset($row['incorrect_count']) ? (int)$row['incorrect_count'] : null,
            'unanswered_count' => isset($row['unanswered_count']) ? (int)$row['unanswered_count'] : null,
            'total_questions' => isset($row['total_questions']) ? (int)$row['total_questions'] : (int)$row['jumlah_soal'],
            'session_id' => $row['session_id'] ? (int)$row['session_id'] : null
        ];
    }
    $stmt->close();

    // Kirim Respon Sukses termasuk metadata tryout
    $response = ['status' => 'success', 'tryout' => $tryout_meta, 'data' => $data];

    // Jika mode debug diaktifkan, sertakan informasi tambahan untuk diagnosis
    if (isset($_GET['debug']) && $_GET['debug']) {
        $response['debug'] = [
            'requested_tryout_id' => $tryout_id,
            'user_id' => $user_id,
            'rows' => count($data),
            'tryout_meta_found' => $tryout_meta !== null,
            'query_preview' => substr(preg_replace('/\s+/', ' ', $sql), 0, 500)
        ];
    }

    send_clean_json(200, $response);

} catch (Exception $e) {
    // Tangkap semua error dan kirim sebagai JSON
    send_clean_json(500, ['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()]);
}
?>