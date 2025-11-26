<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/auth.php";

$user = require_role(['admin']);
$method = $_SERVER['REQUEST_METHOD'];
$table = "hasil_siswa";
$pk = "id";

switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM hasil_siswa WHERE id=?");
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            if ($row) echo json_encode(["success"=>true,"data"=>$row]);
            else echo json_encode(["success"=>false,"message"=>"Not found"]);
        } else {
            $q = $conn->query("SELECT * FROM hasil_siswa ORDER BY id DESC");
            $data = [];
            while ($r = $q->fetch_assoc()) $data[] = $r;
            echo json_encode(["success"=>true,"data"=>$data]);
        }
        break;
    case 'POST':
        $input = $_POST;
        $user_id = $_POST['user_id'] ?? null;
        $kuis_id = $_POST['kuis_id'] ?? null;
        $skor = $_POST['skor'] ?? null;
        $jawaban_json = $_POST['jawaban_json'] ?? null;
        // basic validation
        // no strict validation
        $stmt = $conn->prepare("INSERT INTO hasil_siswa (user_id,kuis_id,skor,jawaban_json) VALUES (?,?,?,?)");
        $stmt->bind_param("iiis", user_id, kuis_id, skor, jawaban_json);
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
        if (isset($put_vars['user_id'])) { $fields[] = "user_id=?"; $types.="i"; $values[] = (int)$put_vars['user_id']; }
        if (isset($put_vars['kuis_id'])) { $fields[] = "kuis_id=?"; $types.="i"; $values[] = (int)$put_vars['kuis_id']; }
        if (isset($put_vars['skor'])) { $fields[] = "skor=?"; $types.="i"; $values[] = (int)$put_vars['skor']; }
        if (isset($put_vars['jawaban_json'])) { $fields[] = "jawaban_json=?"; $types.="s"; $values[] = $put_vars['jawaban_json']; }
        if (count($fields)==0) { echo json_encode(["success"=>false,"message"=>"Nothing to update"]); exit; }
        $sql = "UPDATE hasil_siswa SET ".implode(",", $fields)." WHERE id=?";
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
        $stmt = $conn->prepare("DELETE FROM hasil_siswa WHERE id=?");
        $stmt->bind_param("i",$id);
        if ($stmt->execute()) echo json_encode(["success"=>true,"message"=>"Deleted"]);
        else echo json_encode(["success"=>false,"message"=>"Delete failed"]);
        break;
    default:
        http_response_code(405);
        echo json_encode(["success"=>false,"message"=>"Method not allowed"]);
}
?>