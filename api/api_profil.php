<?php
require_once __DIR__ . '/api_common.php';
// Mobile-friendly endpoint untuk mengambil profil berdasarkan user_id (POST)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Handle OPTIONS
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

$input = $_POST;
$user_id = isset($input['user_id']) ? intval($input['user_id']) : 0;

if ($user_id <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Parameter user_id diperlukan.']);
    exit;
}

try {
    $stmt = $koneksi->prepare("SELECT id, nama, email, telepon, role, tipe_user, foto_profil FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res ? $res->fetch_assoc() : null;

    if ($user) {
        // Map fields to what Android expects
        $data = [
            'id' => (string)$user['id'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'telepon' => $user['telepon'],
            'foto' => $user['foto_profil'],
            'tipe_user' => $user['tipe_user']
        ];

        echo json_encode(['status' => 'success', 'message' => 'Data profil berhasil diambil.', 'data' => $data]);
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        exit;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}

$koneksi->close();
?>
