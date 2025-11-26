<?php
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('Asia/Jakarta');

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "neoblue";

$conn = new mysqli($db_host,$db_user,$db_pass,$db_name);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success"=>false,"message"=>"DB connection failed"]);
    exit;
}
$conn->set_charset("utf8mb4");
?>
