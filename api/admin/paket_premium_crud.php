<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/auth.php";

$user = require_role(['admin']);
$method = $_SERVER['REQUEST_METHOD'];
$table = "paket_premium";
$pk = "id";

switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM paket_premium WHERE id=?");
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            if ($row) echo json_encode(["success"=>true,"data"=>$row]);
            else echo json_encode(["success"=>false,"message"=>"Not found"]);
        } else {
            $q = $conn->query("SELECT * FROM paket_premium ORDER BY id DESC");
            $data = [];
            while ($r = $q->fetch_assoc()) $data[] = $r;
            echo json_encode(["success"=>true,"data"=>$data]);
        }
        break;
    case 'POST':
        $input = $_POST;
        $nama_paket = $_POST['nama_paket'] ?? null;
        $harga = $_POST['harga'] ?? null;
        $durasi_hari = $_POST['durasi_hari'] ?? null;
        $keterangan = $_POST['keterangan'] ?? null;
        // basic validation
        if (!$nama_paket) { echo json_encode(['success'=>false,'message'=>'Missing nama_paket']); exit; }
        $stmt = $conn->prepare("INSERT INTO paket_premium (nama_paket,harga,durasi_hari,keterangan) VALUES (?,?,?,?)");
        $stmt->bind_param("siis", nama_paket, harga, durasi_hari, keterangan);
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
        if (isset($put_vars['nama_paket'])) { $fields[] = "nama_paket=?"; $types.="s"; $values[] = $put_vars['nama_paket']; }
        if (isset($put_vars['harga'])) { $fields[] = "harga=?"; $types.="i"; $values[] = (int)$put_vars['harga']; }
        if (isset($put_vars['durasi_hari'])) { $fields[] = "durasi_hari=?"; $types.="i"; $values[] = (int)$put_vars['durasi_hari']; }
        if (isset($put_vars['keterangan'])) { $fields[] = "keterangan=?"; $types.="s"; $values[] = $put_vars['keterangan']; }
        if (count($fields)==0) { echo json_encode(["success"=>false,"message"=>"Nothing to update"]); exit; }
        $sql = "UPDATE paket_premium SET ".implode(",", $fields)." WHERE id=?";
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
        $stmt = $conn->prepare("DELETE FROM paket_premium WHERE id=?");
        $stmt->bind_param("i",$id);
        if ($stmt->execute()) echo json_encode(["success"=>true,"message"=>"Deleted"]);
        else echo json_encode(["success"=>false,"message"=>"Delete failed"]);
        break;
    default:
        http_response_code(405);
        echo json_encode(["success"=>false,"message"=>"Method not allowed"]);
}
?>