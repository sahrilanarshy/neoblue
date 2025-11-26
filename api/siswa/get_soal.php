<?php
require_once __DIR__ . "/../config/config.php";
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$id) { echo json_encode(["success"=>false,"message"=>"Missing id"]); exit; }
$stmt = $conn->prepare("SELECT id, kuis_id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d FROM soal WHERE kuis_id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$res = $stmt->get_result();
$data=[];
while ($r=$res->fetch_assoc()) $data[]=$r;
echo json_encode(["success"=>true,"data"=>$data]);
?>
