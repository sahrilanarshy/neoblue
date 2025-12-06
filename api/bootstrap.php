<?php
// Common bootstrap for API endpoints
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Include database connection (provide $koneksi)
include_once __DIR__ . '/../config/koneksi.php';

function send_json($code, $payload) {
    http_response_code($code);
    echo json_encode($payload);
    exit;
}

function get_json_input() {
    $data = json_decode(file_get_contents("php://input"), true);
    return $data ?? [];
}

?>
