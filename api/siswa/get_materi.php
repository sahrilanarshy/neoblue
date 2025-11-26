<?php
require_once __DIR__ . "/../config/config.php";
$q = $conn->query("SELECT * FROM materi ORDER BY id DESC");
$data=[];
while ($r=$q->fetch_assoc()) $data[]=$r;
echo json_encode(["success"=>true,"data"=>$data]);
?>
