<?php
require_once __DIR__ . "/../config/config.php";
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
if (!$user_id) { echo json_encode(["success"=>false,"message"=>"Missing user_id"]); exit; }
$stmt = $conn->prepare("SELECT * FROM notifikasi WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$res=$stmt->get_result();
$data=[];
while ($r=$res->fetch_assoc()) $data[]=$r;
echo json_encode(["success"=>true,"data"=>$data]);
?>
