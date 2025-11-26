<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/auth.php";

$user = require_role(['admin']);
$method = $_SERVER['REQUEST_METHOD'];
$table = "materi";
$pk = "id";

switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM materi WHERE id=?");
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            if ($row) echo json_encode(["success"=>true,"data"=>$row]);
            else echo json_encode(["success"=>false,"message"=>"Not found"]);
        } else {
            $q = $conn->query("SELECT * FROM materi ORDER BY id DESC");
            $data = [];
            while ($r = $q->fetch_assoc()) $data[] = $r;
            echo json_encode(["success"=>true,"data"=>$data]);
        }
        break;
    case 'POST':
        $input = $_POST;
        $judul = $_POST['judul'] ?? null;
        $isi = $_POST['isi'] ?? null;
        $mata_pelajaran_id = $_POST['mata_pelajaran_id'] ?? null;
        $created_by = $_POST['created_by'] ?? null;
        // basic validation
        if (!$judul) { echo json_encode(['success'=>false,'message'=>'Missing judul']); exit; }
        $stmt = $conn->prepare("INSERT INTO materi (judul,isi,mata_pelajaran_id,created_by) VALUES (?,?,?,?)");
        $stmt->bind_param("ssis", judul, isi, mata_pelajaran_id, created_by);
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
        if (isset($put_vars['judul'])) { $fields[] = "judul=?"; $types.="s"; $values[] = $put_vars['judul']; }
        if (isset($put_vars['isi'])) { $fields[] = "isi=?"; $types.="s"; $values[] = $put_vars['isi']; }
        if (isset($put_vars['mata_pelajaran_id'])) { $fields[] = "mata_pelajaran_id=?"; $types.="i"; $values[] = (int)$put_vars['mata_pelajaran_id']; }
        if (isset($put_vars['created_by'])) { $fields[] = "created_by=?"; $types.="s"; $values[] = $put_vars['created_by']; }
        if (count($fields)==0) { echo json_encode(["success"=>false,"message"=>"Nothing to update"]); exit; }
        $sql = "UPDATE materi SET ".implode(",", $fields)." WHERE id=?";
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
        $stmt = $conn->prepare("DELETE FROM materi WHERE id=?");
        $stmt->bind_param("i",$id);
        if ($stmt->execute()) echo json_encode(["success"=>true,"message"=>"Deleted"]);
        else echo json_encode(["success"=>false,"message"=>"Delete failed"]);
        break;
    default:
        http_response_code(405);
        echo json_encode(["success"=>false,"message"=>"Method not allowed"]);
}
?>