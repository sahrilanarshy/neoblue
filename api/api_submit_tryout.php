<?php
require_once __DIR__ . '/api_common.php';
// submit tryout — standardize with bootstrap and send_json, preserve session-based auth
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log');

include_once __DIR__ . '/bootstrap.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Pastikan user sudah login (web). Untuk mobile, terima fallback `user_id` dari JSON body jika disediakan.
$user_id = null;
if (isset($_SESSION['status_login']) && $_SESSION['status_login'] === true && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

// Read raw input early to allow mobile fallback
$raw_input = get_json_input();

// Jika tidak ada session, coba fallback ke user_id dari body (untuk mobile clients)
if ($user_id === null) {
    if (isset($raw_input['user_id']) && is_numeric($raw_input['user_id'])) {
        $user_id = intval($raw_input['user_id']);
        // optional: log fallback for debugging
        error_log("[api_submit_tryout] Using fallback user_id from JSON: $user_id");
    } else {
        send_json(401, ['status' => 'error', 'message' => 'Anda harus login untuk menyelesaikan tryout.']);
    }
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') send_json(405, ['status' => 'error', 'message' => 'Metode request tidak diizinkan.']);

$input = $raw_input; // already read above
$id_tryout = isset($input['id_tryout']) ? intval($input['id_tryout']) : 0;
$subtest_id = isset($input['subtest_id']) ? intval($input['subtest_id']) : 0;
$user_answers = $input['user_answers'] ?? [];
$start_time = $input['start_time'] ?? date('Y-m-d H:i:s');
$end_time = date('Y-m-d H:i:s');

if ($id_tryout <= 0 || $subtest_id <= 0) send_json(400, ['status' => 'error', 'message' => 'ID Tryout atau Subtest tidak valid.']);

// Server-side guard: prevent submissions for tryouts that have already ended
$stmt_t = $koneksi->prepare("SELECT tanggal_selesai FROM tryout WHERE id = ?");
$stmt_t->bind_param('i', $id_tryout);
$stmt_t->execute();
$res_t = $stmt_t->get_result();
$row_t = $res_t->fetch_assoc();
if ($row_t && !empty($row_t['tanggal_selesai'])) {
    $tanggal_selesai = $row_t['tanggal_selesai'];
    if ($tanggal_selesai < date('Y-m-d')) {
        send_json(403, ['status' => 'error', 'message' => 'Tryout telah berakhir pada ' . $tanggal_selesai]);
    }
}

$koneksi->begin_transaction();
try {
    $stmt_soal = $koneksi->prepare("SELECT id, kunci_jawaban FROM soal_tryout WHERE tryout_id = ? AND subtest_id = ? ORDER BY id ASC");
    $stmt_soal->bind_param('ii', $id_tryout, $subtest_id);
    $stmt_soal->execute();
    $res_soal = $stmt_soal->get_result();
    $all_questions = $res_soal->fetch_all(MYSQLI_ASSOC);

    $correct_count = 0; $incorrect_count = 0; $unanswered_count = 0;
    $total_questions = count($all_questions);

    // Normalize user_answers for flexible formats:
    // - Case A: numeric-indexed array of answers (current mobile client)
    // - Case B: associative array with question_id => answer
    // - Case C: array of objects [{"question_id":id, "answer":"A"}, ...]
    $answer_map = [];

    if (is_array($user_answers) && !empty($user_answers)) {
        // If first element is an array/object with question_id, map accordingly
        $first = reset($user_answers);
        if (is_array($first) && array_key_exists('question_id', $first)) {
            foreach ($user_answers as $item) {
                if (isset($item['question_id'])) {
                    $answer_map[intval($item['question_id'])] = isset($item['answer']) ? $item['answer'] : null;
                }
            }
        } elseif (is_object($first) && property_exists($first, 'question_id')) {
            foreach ($user_answers as $item) {
                $qid = property_exists($item, 'question_id') ? intval($item->question_id) : null;
                $ans = property_exists($item, 'answer') ? $item->answer : null;
                if ($qid !== null) $answer_map[$qid] = $ans;
            }
        } else {
            // Check if keys look like question IDs (non-sequential or string numeric keys)
            $all_keys_numeric = true;
            $idx = 0;
            foreach ($user_answers as $k => $v) {
                if (!is_int($k) && !ctype_digit((string)$k)) { $all_keys_numeric = false; break; }
                if ((int)$k !== $idx) { $all_keys_numeric = false; break; }
                $idx++;
            }

            if (!$all_keys_numeric) {
                // Treat array as map question_id => answer
                foreach ($user_answers as $k => $v) {
                    $answer_map[intval($k)] = $v;
                }
            } else {
                // Numeric sequential array: fall back to index-based matching
                foreach ($user_answers as $k => $v) {
                    $answer_map[$k] = $v; // keep numeric indices, will handle below
                }
            }
        }
    }

    // Score using question IDs when available; otherwise use index-based matching
    foreach ($all_questions as $index => $question) {
        $qid = intval($question['id']);
        $correct_answer = strtoupper($question['kunci_jawaban']);

        $user_answer = null;
        if (array_key_exists($qid, $answer_map)) {
            $user_answer = $answer_map[$qid];
        } elseif (array_key_exists($index, $answer_map)) {
            $user_answer = $answer_map[$index];
        }

        if ($user_answer === null) $unanswered_count++;
        else {
            $ua = strtoupper($user_answer);
            if ($ua === $correct_answer) $correct_count++;
            else $incorrect_count++;
        }
    }

    $score = ($total_questions > 0) ? (100.0 / $total_questions) * $correct_count : 0;

    $stmt_insert = $koneksi->prepare("INSERT INTO user_tryout_sessions (user_id, tryout_id, subtest_id, start_time, end_time, score, correct_count, incorrect_count, unanswered_count, total_questions, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $status = 'completed';
    $types = 'iiissdiiiis';
    $stmt_insert->bind_param($types, $user_id, $id_tryout, $subtest_id, $start_time, $end_time, $score, $correct_count, $incorrect_count, $unanswered_count, $total_questions, $status);
    if (!$stmt_insert->execute()) throw new Exception('Gagal menyimpan sesi tryout: ' . $stmt_insert->error);
    $session_id = $stmt_insert->insert_id;

    $koneksi->commit();
    send_json(200, ['status' => 'success', 'message' => 'Tryout berhasil diselesaikan.', 'session_id' => $session_id]);

} catch (Exception $e) {
    $koneksi->rollback();
    send_json(500, ['status' => 'error', 'message' => $e->getMessage()]);
}

exit;

?>