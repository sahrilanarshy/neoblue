<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/auth.php";

$user = require_role(['admin']);
$method = $_SERVER['REQUEST_METHOD'];
$table = "soal";
$pk = "id";

switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM soal WHERE id=?");
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            if ($row) echo json_encode(["success"=>true,"data"=>$row]);
            else echo json_encode(["success"=>false,"message"=>"Not found"]);
        } else {
            $q = $conn->query("SELECT * FROM soal ORDER BY id DESC");
            $data = [];
            while ($r = $q->fetch_assoc()) $data[] = $r;
            echo json_encode(["success"=>true,"data"=>$data]);
        }
        break;
    case 'POST':
        $input = $_POST;
        $kuis_id = $_POST['kuis_id'] ?? null;
        $pertanyaan = $_POST['pertanyaan'] ?? null;
        $opsi_a = $_POST['opsi_a'] ?? null;
        $opsi_b = $_POST['opsi_b'] ?? null;
        $opsi_c = $_POST['opsi_c'] ?? null;
        $opsi_d = $_POST['opsi_d'] ?? null;
        $jawaban_benar = $_POST['jawaban_benar'] ?? null;
        // basic validation
        if (!$pertanyaan) { echo json_encode(['success'=>false,'message'=>'Missing pertanyaan']); exit; }
        $stmt = $conn->prepare("INSERT INTO soal (kuis_id,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("issssss", kuis_id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar);
        if ($stmt->execute()) {
            echo json_encode(["success"=>true,"message"=>"Created","id"=>$stmt->insert_id]);
        } else {
            echo json_encode(["success"=>false,"message"=>"Insert failed"]);
        }
        break;
    case 'PUT':
        parse_str(file_get_contents("php://input"), $put_vars);
        $id = isset($put_vars['id']) ? (int)$put_vars['id'] : null;
        if (!$id) { echo json_encode(["success"=>false,"message"=>"Missing id"]); exit; }
        $fields = [];
        $types = "";
        $values = [];
        if (isset($put_vars['kuis_id'])) { $fields[] = "kuis_id=?"; $types.="i"; $values[] = (int)$put_vars['kuis_id']; }
        if (isset($put_vars['pertanyaan'])) { $fields[] = "pertanyaan=?"; $types.="s"; $values[] = $put_vars['pertanyaan']; }
        if (isset($put_vars['opsi_a'])) { $fields[] = "opsi_a=?"; $types.="s"; $values[] = $put_vars['opsi_a']; }
        if (isset($put_vars['opsi_b'])) { $fields[] = "opsi_b=?"; $types.="s"; $values[] = $put_vars['opsi_b']; }
        if (isset($put_vars['opsi_c'])) { $fields[] = "opsi_c=?"; $types.="s"; $values[] = $put_vars['opsi_c']; }
        if (isset($put_vars['opsi_d'])) { $fields[] = "opsi_d=?"; $types.="s"; $values[] = $put_vars['opsi_d']; }
        if (isset($put_vars['jawaban_benar'])) { $fields[] = "jawaban_benar=?"; $types.="s"; $values[] = $put_vars['jawaban_benar']; }
        if (count($fields)==0) { echo json_encode(["success"=>false,"message"=>"Nothing to update"]); exit; }
        $sql = "UPDATE soal SET ".implode(",", $fields)." WHERE id=?";
        $types .= "i";
        $values[] = $id;
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$values);
        if ($stmt->execute()) echo json_encode(["success"=>true,"message"=>"Updated"]);
        else echo json_encode(["success"=>false,"message"=>"Update failed"]);
        break;
    case 'DELETE':
        parse_str(file_get_contents("php://input"), $del_vars);
        $id = $del_vars['id'] ?? $_GET['id'] ?? null;
        if (!$id) { echo json_encode(["success"=>false,"message"=>"Missing id"]); exit; }
        $stmt = $conn->prepare("DELETE FROM soal WHERE id=?");
        $stmt->bind_param("i",$id);
        if ($stmt->execute()) echo json_encode(["success"=>true,"message"=>"Deleted"]);
        else echo json_encode(["success"=>false,"message"=>"Delete failed"]);
        break;
    default:
        http_response_code(405);
        echo json_encode(["success"=>false,"message"=>"Method not allowed"]);
}
?>