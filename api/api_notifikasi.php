<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
include '../config/koneksi.php';
session_start();

try {
    // Terima user_id dari GET/POST agar klien mobile dapat memanggil tanpa bergantung ke PHP session.
    // Prioritaskan request parameter jika diberikan, fallback ke session jika ada.
    $user_id = null;
    if (isset($_REQUEST['user_id']) && is_numeric($_REQUEST['user_id'])) {
        $user_id = intval($_REQUEST['user_id']);
    } elseif (isset($_SESSION['user_id'])) {
        $user_id = intval($_SESSION['user_id']);
    }

    // Buat tabel notifications jika belum ada (safe to run)
    $create_sql = "CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        link VARCHAR(255) DEFAULT NULL,
        is_read TINYINT(1) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($koneksi, $create_sql);

    // Buat tabel user_hidden_pengumuman jika belum ada (untuk menyimpan pengumuman yang disembunyikan per-user)
    $create_hidden_sql = "CREATE TABLE IF NOT EXISTS user_hidden_pengumuman (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        pengumuman_id INT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY ux_user_peng (user_id, pengumuman_id),
        INDEX (user_id),
        INDEX (pengumuman_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($koneksi, $create_hidden_sql);

    // Buat tabel user_read_pengumuman jika belum ada (untuk menyimpan pengumuman yang sudah dibaca per-user)
    $create_read_sql = "CREATE TABLE IF NOT EXISTS user_read_pengumuman (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        pengumuman_id INT NOT NULL,
        read_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY ux_user_read (user_id, pengumuman_id),
        INDEX (user_id),
        INDEX (pengumuman_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($koneksi, $create_read_sql);

    // Ambil notifikasi untuk user ini (jika ada user_id), terbaru dulu
    $user_notifs = [];
    if ($user_id !== null) {
        $stmt = mysqli_prepare($koneksi, "SELECT id, title, message, link, is_read, created_at, 0 AS is_global FROM notifications WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $user_notifs = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    // Ambil pengumuman publik (Published) yang berlaku untuk semua user
    $today = date('Y-m-d');
    $peng_query = "SELECT id, judul AS title, isi AS message, link, created_at, tanggal_terbit, tanggal_selesai FROM pengumuman WHERE status = 'Published' AND tanggal_terbit <= ? AND (tanggal_selesai IS NULL OR tanggal_selesai >= ?)";
    $stmt2 = mysqli_prepare($koneksi, $peng_query);
    mysqli_stmt_bind_param($stmt2, 'ss', $today, $today);
    mysqli_stmt_execute($stmt2);
    $res2 = mysqli_stmt_get_result($stmt2);
    $pengumuman = mysqli_fetch_all($res2, MYSQLI_ASSOC);

    // Jika ada user_id, ambil daftar pengumuman yang disembunyikan oleh user ini
    $hidden_ids = [];
    if ($user_id !== null) {
        $stmt_hide = mysqli_prepare($koneksi, "SELECT pengumuman_id FROM user_hidden_pengumuman WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt_hide, 'i', $user_id);
        mysqli_stmt_execute($stmt_hide);
        $res_hide = mysqli_stmt_get_result($stmt_hide);
        while ($h = mysqli_fetch_assoc($res_hide)) {
            $hidden_ids[] = intval($h['pengumuman_id']);
        }
    }

    // Jika ada user_id, ambil daftar pengumuman yang sudah dibaca oleh user ini
    $read_ids = [];
    if ($user_id !== null) {
        $stmt_read = mysqli_prepare($koneksi, "SELECT pengumuman_id FROM user_read_pengumuman WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt_read, 'i', $user_id);
        mysqli_stmt_execute($stmt_read);
        $res_read = mysqli_stmt_get_result($stmt_read);
        while ($r = mysqli_fetch_assoc($res_read)) {
            $read_ids[] = intval($r['pengumuman_id']);
        }
    }

    // Normalisasi pengumuman menjadi format notifikasi dan tandai sebagai global
    $global_notifs = [];
    foreach ($pengumuman as $p) {
        // Jika pengumuman disembunyikan oleh user yang meminta, lewati
        if (!empty($hidden_ids) && in_array(intval($p['id']), $hidden_ids)) continue;
        $isReadFlag = 0;
        if (!empty($read_ids) && in_array(intval($p['id']), $read_ids)) {
            $isReadFlag = 1;
        }
        $global_notifs[] = [
            'id' => 'peng_' . $p['id'],
            'title' => $p['title'],
            'message' => $p['message'],
            'link' => $p['link'],
            'is_read' => $isReadFlag,
            'created_at' => $p['created_at'],
            'is_global' => 1
        ];
    }

    // Gabungkan dan urutkan berdasarkan created_at (desc)
    $all = array_merge($user_notifs, $global_notifs);
    usort($all, function($a, $b) {
        $ta = strtotime($a['created_at']);
        $tb = strtotime($b['created_at']);
        return $tb <=> $ta;
    });

    echo json_encode(['status' => 'success', 'data' => $all]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>