<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/auth.php";

$user = require_role(['admin']);
$method = $_SERVER['REQUEST_METHOD'];
$table = "kuis_tryout";
$pk = "id";

switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM kuis_tryout WHERE id=?");
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            if ($row) echo json_encode(["success"=>true,"data"=>$row]);
            else echo json_encode(["success"=>false,"message"=>"Not found"]);
        } else {
            $q = $conn->query("SELECT * FROM kuis_tryout ORDER BY id DESC");
            $data = [];
            while ($r = $q->fetch_assoc()) $data[] = $r;
            echo json_encode(["success"=>true,"data"=>$data]);
        }
        break;
    case 'POST':
        $input = $_POST;
        $nama_kuis = $_POST['nama_kuis'] ?? null;
        $mata_pelajaran_id = $_POST['mata_pelajaran_id'] ?? null;
        $kategori_soal_id = $_POST['kategori_soal_id'] ?? null;
        $waktu_menit = $_POST['waktu_menit'] ?? null;
        $created_by = $_POST['created_by'] ?? null;
        // basic validation
        if (!$nama_kuis) { echo json_encode(['success'=>false,'message'=>'Missing nama_kuis']); exit; }
        $stmt = $conn->prepare("INSERT INTO kuis_tryout (nama_kuis,mata_pelajaran_id,kategori_soal_id,waktu_menit,created_by) VALUES (?,?,?,?,?)");
        $stmt->bind_param("siiis", nama_kuis, mata_pelajaran_id, kategori_soal_id, waktu_menit, created_by);
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
        if (isset($put_vars['nama_kuis'])) { $fields[] = "nama_kuis=?"; $types.="s"; $values[] = $put_vars['nama_kuis']; }
        if (isset($put_vars['mata_pelajaran_id'])) { $fields[] = "mata_pelajaran_id=?"; $types.="i"; $values[] = (int)$put_vars['mata_pelajaran_id']; }
        if (isset($put_vars['kategori_soal_id'])) { $fields[] = "kategori_soal_id=?"; $types.="i"; $values[] = (int)$put_vars['kategori_soal_id']; }
        if (isset($put_vars['waktu_menit'])) { $fields[] = "waktu_menit=?"; $types.="i"; $values[] = (int)$put_vars['waktu_menit']; }
        if (isset($put_vars['created_by'])) { $fields[] = "created_by=?"; $types.="s"; $values[] = $put_vars['created_by']; }
        if (count($fields)==0) { echo json_encode(["success"=>false,"message"=>"Nothing to update"]); exit; }
        $sql = "UPDATE kuis_tryout SET ".implode(",", $fields)." WHERE id=?";
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
        $stmt = $conn->prepare("DELETE FROM kuis_tryout WHERE id=?");
        $stmt->bind_param("i",$id);
        if ($stmt->execute()) echo json_encode(["success"=>true,"message"=>"Deleted"]);
        else echo json_encode(["success"=>false,"message"=>"Delete failed"]);
        break;
    default:
        http_response_code(405);
        echo json_encode(["success"=>false,"message"=>"Method not allowed"]);
}
?>