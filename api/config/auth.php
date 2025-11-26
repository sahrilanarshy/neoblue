<?php
// Simple role-based auth helper. Caller must include config.php first.
function get_current_user() {
    $userId = $_SERVER['HTTP_X_USER_ID'] ?? null;
    $role = $_SERVER['HTTP_X_USER_ROLE'] ?? null;
    if (!$userId) return null;
    return ["id" => (int)$userId, "role" => $role];
}

function require_role($allowed_roles = []) {
    $user = get_current_user();
    if (!$user) {
        http_response_code(401);
        echo json_encode(["success"=>false,"message"=>"Unauthorized: missing user header"]);
        exit;
    }
    if (!in_array($user['role'], $allowed_roles)) {
        http_response_code(403);
        echo json_encode(["success"=>false,"message"=>"Forbidden: insufficient role"]);
        exit;
    }
    return $user;
}
?>
