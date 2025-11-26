<?php
require_once __DIR__ . "/config/config.php";

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$nama = $_POST['nama_lengkap'] ?? '';
$email = $_POST['email'] ?? '';

if (!$username || !$password || !$nama || !$email) {
    echo json_encode(["success"=>false,"message"=>"Missing fields"]);
    exit;
}

$check = $conn->prepare("SELECT id FROM users WHERE username=?");
$check->bind_param("s", $username);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    echo json_encode(["success"=>false,"message"=>"Username already used"]);
    exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);
$ins = $conn->prepare("INSERT INTO users (username,password_hash,nama_lengkap,email) VALUES (?,?,?,?)");
$ins->bind_param("ssss", $username, $hash, $nama, $email);
if ($ins->execute()) {
    echo json_encode(["success"=>true,"message"=>"Registered"]);
} else {
    echo json_encode(["success"=>false,"message"=>"Register failed"]);
}
?>
