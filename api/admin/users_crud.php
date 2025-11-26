<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/auth.php";

$user = require_role(['admin']);
$method = $_SERVER['REQUEST_METHOD'];
$table = "users";
$pk = "id";

switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            if ($row) echo json_encode(["success"=>true,"data"=>$row]);
            else echo json_encode(["success"=>false,"message"=>"Not found"]);
        } else {
            $q = $conn->query("SELECT * FROM users ORDER BY id DESC");
            $data = [];
            while ($r = $q->fetch_assoc()) $data[] = $r;
            echo json_encode(["success"=>true,"data"=>$data]);
        }
        break;
    case 'POST':
        $input = $_POST;
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $nama_lengkap = $_POST['nama_lengkap'] ?? null;
        $role = $_POST['role'] ?? null;
        $email = $_POST['email'] ?? null;
        // basic validation
        if (!$username) { echo json_encode(['success'=>false,'message'=>'Missing username']); exit; }
        if (!$password) { echo json_encode(['success'=>false,'message'=>'Missing password']); exit; }
        if (!$nama_lengkap) { echo json_encode(['success'=>false,'message'=>'Missing nama_lengkap']); exit; }
        $stmt = $conn->prepare("INSERT INTO users (username,password_hash,nama_lengkap,role,email) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $username, $password_hash, $nama_lengkap, $role, $email);
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
        if (isset($put_vars['username'])) { $fields[] = "username=?"; $types.="s"; $values[] = $put_vars['username']; }
        if (isset($put_vars['password']) && $put_vars['password']!=='') { $password_hash = password_hash($put_vars['password'], PASSWORD_BCRYPT); $fields[] = "password_hash=?"; $types.="s"; $values[] = $password_hash; }
        if (isset($put_vars['nama_lengkap'])) { $fields[] = "nama_lengkap=?"; $types.="s"; $values[] = $put_vars['nama_lengkap']; }
        if (isset($put_vars['role'])) { $fields[] = "role=?"; $types.="s"; $values[] = $put_vars['role']; }
        if (isset($put_vars['email'])) { $fields[] = "email=?"; $types.="s"; $values[] = $put_vars['email']; }
        if (count($fields)==0) { echo json_encode(["success"=>false,"message"=>"Nothing to update"]); exit; }
        $sql = "UPDATE users SET ".implode(",", $fields)." WHERE id=?";
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
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i",$id);
        if ($stmt->execute()) echo json_encode(["success"=>true,"message"=>"Deleted"]);
        else echo json_encode(["success"=>false,"message"=>"Delete failed"]);
        break;
    default:
        http_response_code(405);
        echo json_encode(["success"=>false,"message"=>"Method not allowed"]);
}
?>