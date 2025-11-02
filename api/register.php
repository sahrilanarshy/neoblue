<?php
require_once("../config/koneksi.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid method"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$fullname = $data["nama_lengkap"] ?? "";
$username = $data["username"] ?? "";
$password = $data["password"] ?? "";
$role = $data["role"] ?? "siswa";

if (!$fullname || !$username || !$password) {
    echo json_encode(["status" => "error", "message" => "Field tidak boleh kosong"]);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (nama_lengkap, username, password_hash, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $fullname, $username, $hash, $role);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Registrasi berhasil"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal registrasi"]);
}
$stmt->close();
?>
