<?php
// Common API helpers: headers, json_response, and global error handlers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

function json_response($statusCode = 200, $status = 'success', $message = '', $data = null) {
    http_response_code($statusCode);
    $out = ['status' => $status, 'message' => $message];
    if (!is_null($data)) $out['data'] = $data;
    echo json_encode($out, JSON_UNESCAPED_UNICODE);
    exit;
}

set_exception_handler(function($e) {
    error_log("Uncaught Exception: " . $e->getMessage());
    if (!headers_sent()) json_response(500, 'error', 'Internal server error.');
});

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("PHP Error [$errno] $errstr in $errfile on line $errline");
    if (!headers_sent()) json_response(500, 'error', 'Internal server error.');
});

register_shutdown_function(function() {
    $err = error_get_last();
    if ($err !== null) {
        error_log("Shutdown Error: " . json_encode($err));
        if (!headers_sent()) json_response(500, 'error', 'Internal server error.');
    }
});

?>