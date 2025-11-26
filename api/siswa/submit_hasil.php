<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/auth.php";
$user = require_role(['siswa','admin']);
$kuis_id = $_POST['kuis_id'] ?? null;
$skor = $_POST['skor'] ?? null;
$jawaban_json = $_POST['jawaban_json'] ?? null;
if (!$kuis_id || $skor===null) { echo json_encode(["success"=>false,"message"=>"Missing fields"]); exit; }
$stmt = $conn->prepare("INSERT INTO hasil_siswa (user_id,kuis_id,skor,jawaban_json) VALUES (?,?,?,?)");
$stmt->bind_param("iiis", $user['id'], $kuis_id, $skor, $jawaban_json);
if ($stmt->execute()) echo json_encode(["success"=>true,"message"=>"Saved","id"=>$stmt->insert_id]);
else echo json_encode(["success"=>false,"message"=>"Failed"]);
?>
