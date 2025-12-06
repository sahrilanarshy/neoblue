<?php
require_once __DIR__ . '/api_common.php';
// Mobile-friendly endpoint untuk update profil berdasarkan user_id (POST, form-urlencoded)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Logging singkat untuk debugging method/request (tercatat di uploads/upload_errors.log)
$remote = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$host = $_SERVER['HTTP_HOST'] ?? 'unknown';
$hasFiles = !empty($_FILES) ? json_encode(array_keys($_FILES)) : 'none';
$logEntry = '[' . date('Y-m-d H:i:s') . "] api_update_profil REQUEST_METHOD=" . ($_SERVER['REQUEST_METHOD'] ?? '') . " URI=" . ($_SERVER['REQUEST_URI'] ?? '') . " REMOTE_ADDR=" . $remote . " HOST=" . $host . " FILES=" . $hasFiles . "\n";
if (function_exists('getallheaders')) {
    $logEntry .= 'Headers: ' . json_encode(getallheaders()) . "\n";
}
$raw = @file_get_contents('php://input');
if ($raw) {
    $logEntry .= 'RawLen: ' . strlen($raw) . "\n";
}
@file_put_contents(__DIR__ . '/../uploads/upload_errors.log', $logEntry, FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed. Use POST.']);
    exit;
}

include '../config/koneksi.php';

// Jika request mengandung file foto (multipart upload) -> handle upload foto saja
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    if ($user_id <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Parameter user_id diperlukan untuk upload foto.']);
        exit;
    }

    $target_dir = "uploads/profile/";
    if (!file_exists(__DIR__ . '/../' . $target_dir)) {
        mkdir(__DIR__ . '/../' . $target_dir, 0777, true);
    }

    $file_name = time() . '_' . basename($_FILES["foto"]["name"]);
    $target_file = __DIR__ . '/../' . $target_dir . $file_name;

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        // Update DB: set foto_profil
        $foto_path = $target_dir . $file_name;
        $stmt_up = $koneksi->prepare("UPDATE users SET foto_profil = ? WHERE id = ?");
        $stmt_up->bind_param('si', $foto_path, $user_id);
        if ($stmt_up->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Foto profil berhasil diupload.', 'foto' => $foto_path]);
            exit;
        } else {
            // jika gagal update DB, hapus file yang sudah dipindah
            if (file_exists($target_file)) unlink($target_file);
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan informasi foto di server.']);
            exit;
        }
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Gagal memindahkan file upload ke folder tujuan.']);
        exit;
    }
}

$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$nama = $_POST['nama'] ?? '';
$telepon = $_POST['telepon'] ?? '';
$password_lama = $_POST['password_lama'] ?? '';
$password_baru = $_POST['password_baru'] ?? '';

// Log POST data (non-sensitive) for debugging updateProfile requests
try {
    $postLog = '[' . date('Y-m-d H:i:s') . '] api_update_profil POST keys=' . json_encode(array_keys($_POST)) . '\n';
    // include lengths for values, mask password fields
    foreach ($_POST as $k => $v) {
        if (stripos($k, 'password') !== false) {
            $postLog .= "$k=***hidden*** ";
        } else {
            $postLog .= "$k=" . (is_string($v) ? strlen($v) : 0) . "bytes ";
        }
    }
    $postLog .= "\n";
    @file_put_contents(__DIR__ . '/../uploads/upload_errors.log', $postLog, FILE_APPEND);
} catch (Exception $e) {}

if ($user_id <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Parameter user_id diperlukan.']);
    exit;
}

if (empty($nama) || empty($telepon)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Nama dan telepon tidak boleh kosong.']);
    exit;
}

try {
    // Prepare update fields
    $update_fields = [];
    $params = [];
    $types = '';

    $update_fields[] = 'nama = ?'; $params[] = $nama; $types .= 's';
    $update_fields[] = 'telepon = ?'; $params[] = $telepon; $types .= 's';

    // Handle password change if requested
    if (!empty($password_lama) || !empty($password_baru)) {
        if (empty($password_lama) || empty($password_baru)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Isi password lama dan baru jika ingin mengganti.']);
            exit;
        }

        // Verify current password
        $stmt_pass = $koneksi->prepare("SELECT password FROM users WHERE id = ?");
        $stmt_pass->bind_param('i', $user_id);
        $stmt_pass->execute();
        $res_pass = $stmt_pass->get_result();
        $user_pass = $res_pass ? $res_pass->fetch_assoc() : null;

        if ($user_pass && password_verify($password_lama, $user_pass['password'])) {
            $hashed = password_hash($password_baru, PASSWORD_DEFAULT);
            $update_fields[] = 'password = ?'; $params[] = $hashed; $types .= 's';
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Password saat ini salah.']);
            exit;
        }
    }

    // Build and execute update
    $types .= 'i';
    $params[] = $user_id;
    $query = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE id = ?";

    $stmt_up = $koneksi->prepare($query);
    $stmt_up->bind_param($types, ...$params);

    if ($stmt_up->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Profil berhasil diperbarui.']);
        exit;
    } else {
        throw new Exception('Gagal memperbarui profil.');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}

$koneksi->close();
?>
