<?php
require_once("../config/koneksi.php");

$res = $conn->query("SELECT id, nama_lengkap, username, role, created_at FROM users");
$users = [];

while ($row = $res->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode([
    "status" => "success",
    "count" => count($users),
    "data" => $users
]);
?>
