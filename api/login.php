<?php
require_once __DIR__ . "/config/config.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    echo json_encode(["success"=>false,"message"=>"Missing fields"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, username, password_hash, nama_lengkap, role, email FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    echo json_encode(["success"=>false,"message"=>"Username not found"]);
    exit;
}
$user = $res->fetch_assoc();
if (!password_verify($password, $user['password_hash'])) {
    echo json_encode(["success"=>false,"message"=>"Invalid password"]);
    exit;
}
// remove password_hash before send
unset($user['password_hash']);
echo json_encode(["success"=>true,"message"=>"Login successful","data"=>$user]);
?>
