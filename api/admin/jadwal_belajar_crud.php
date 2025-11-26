<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/auth.php";

$user = require_role(['admin']);
$method = $_SERVER['REQUEST_METHOD'];
$table = "jadwal_belajar";
$pk = "id";

switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM jadwal_belajar WHERE id=?");
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            if ($row) echo json_encode(["success"=>true,"data"=>$row]);
            else echo json_encode(["success"=>false,"message"=>"Not found"]);
        } else {
            $q = $conn->query("SELECT * FROM jadwal_belajar ORDER BY id DESC");
            $data = [];
            while ($r = $q->fetch_assoc()) $data[] = $r;
            echo json_encode(["success"=>true,"data"=>$data]);
        }
        break;
    case 'POST':
        $input = $_POST;
        $user_id = $_POST['user_id'] ?? null;
        $hari = $_POST['hari'] ?? null;
        $subtest = $_POST['subtest'] ?? null;
        $waktu_mulai = $_POST['waktu_mulai'] ?? null;
        $waktu_selesai = $_POST['waktu_selesai'] ?? null;
        // basic validation
        // no strict validation
        $stmt = $conn->prepare("INSERT INTO jadwal_belajar (user_id,hari,subtest,waktu_mulai,waktu_selesai) VALUES (?,?,?,?,?)");
        $stmt->bind_param("issss", user_id, hari, subtest, waktu_mulai, waktu_selesai);
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
        if (isset($put_vars['hari'])) { $fields[] = "hari=?"; $types.="s"; $values[] = $put_vars['hari']; }
        if (isset($put_vars['subtest'])) { $fields[] = "subtest=?"; $types.="s"; $values[] = $put_vars['subtest']; }
        if (isset($put_vars['waktu_mulai'])) { $fields[] = "waktu_mulai=?"; $types.="s"; $values[] = $put_vars['waktu_mulai']; }
        if (isset($put_vars['waktu_selesai'])) { $fields[] = "waktu_selesai=?"; $types.="s"; $values[] = $put_vars['waktu_selesai']; }
        if (count($fields)==0) { echo json_encode(["success"=>false,"message"=>"Nothing to update"]); exit; }
        $sql = "UPDATE jadwal_belajar SET ".implode(",", $fields)." WHERE id=?";
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
        $stmt = $conn->prepare("DELETE FROM jadwal_belajar WHERE id=?");
        $stmt->bind_param("i",$id);
        if ($stmt->execute()) echo json_encode(["success"=>true,"message"=>"Deleted"]);
        else echo json_encode(["success"=>false,"message"=>"Delete failed"]);
        break;
    default:
        http_response_code(405);
        echo json_encode(["success"=>false,"message"=>"Method not allowed"]);
}
?>