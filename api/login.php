<?php
require_once("../config/koneksi.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid method"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$username = $data["username"] ?? "";
$password = $data["password"] ?? "";

if (!$username || !$password) {
    echo json_encode(["status" => "error", "message" => "Field tidak boleh kosong"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $user = $res->fetch_assoc();
    if (password_verify($password, $user["password_hash"])) {
        unset($user["password_hash"]);
        echo json_encode([
            "status" => "success",
            "message" => "Login berhasil",
            "data" => $user
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Password salah"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User tidak ditemukan"]);
}
?>
