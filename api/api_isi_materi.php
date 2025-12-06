<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../config/koneksi.php';

$materi_id_raw = null;
// Terima beberapa variasi nama parameter supaya client yang salah kapitalisasi tetap bekerja
if (isset($_GET['materi_id'])) {
    $materi_id_raw = $_GET['materi_id'];
} elseif (isset($_GET['MATERI_ID'])) {
    $materi_id_raw = $_GET['MATERI_ID'];
} elseif (isset($_GET['id'])) {
    $materi_id_raw = $_GET['id'];
}

$materi_id = intval($materi_id_raw);

if ($materi_id <= 0) {
    http_response_code(400);
    echo json_encode([
        'error' => 'ID Materi tidak valid.',
        'hint' => 'Pastikan request menggunakan query param "materi_id" (mis: ?materi_id=12)'
    ]);
    exit;
}

try {
    // The `materi` table stores video links in the `link` column.
    // Aliasing it as `video_url` keeps the API response compatible with mobile client.
    $stmt = mysqli_prepare($koneksi, "SELECT id, judul, deskripsi, COALESCE(link, '') AS video_url FROM materi WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $materi_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $materi = mysqli_fetch_assoc($result);

    if (!$materi) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Materi tidak ditemukan.', 'data' => null]);
        exit;
    }

    // Sanitize / normalize video URL before returning to mobile
    $video = '';
    if (isset($materi['video_url'])) {
        $video = trim($materi['video_url']);
        // Remove HTML tags (in case iframe or embed HTML was stored)
        $video = strip_tags($video);

        // If stored as an iframe HTML (e.g. <iframe src="...">), try to extract src
        if (preg_match('/src=["\']([^"\']+)["\']/i', $materi['video_url'], $m)) {
            $video = $m[1];
        }

        // If value is only the 11-char YouTube id, convert to full watch URL
        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $video)) {
            $video = 'https://www.youtube.com/watch?v=' . $video;
        }

        // If missing scheme but looks like youtube url starting with www., add https://
        if ($video !== '' && !preg_match('#^https?://#i', $video) && preg_match('#^(www\.|youtube\.|youtu\.be)#i', $video)) {
            $video = 'https://' . $video;
        }

        // Final trim
        $video = trim($video);
    }

    // Replace returned key with normalized value
    $materi['video_url'] = $video;

    // Wrap the materi object into a standard API envelope so mobile's wrapper model can parse it
    $output = [
        'status' => 'success',
        'message' => 'Materi ditemukan.',
        'data' => $materi
    ];

    echo json_encode($output);

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    exit;
}

?>
