<?php
require_once __DIR__ . '/api_common.php';
// fitur resource (RESTful)
include_once __DIR__ . '/bootstrap.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($method === 'GET') {
    if ($id) {
        $stmt = $koneksi->prepare("SELECT id, nama_fitur, urutan FROM fitur WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            send_json(200, ['status' => 'success', 'data' => $row]);
        } else {
            send_json(404, ['status' => 'error', 'message' => 'Fitur tidak ditemukan.']);
        }
    } else {
        $res = $koneksi->query("SELECT id, nama_fitur, urutan FROM fitur ORDER BY urutan ASC");
        $list = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        send_json(200, ['status' => 'success', 'data' => $list]);
    }
} elseif ($method === 'POST') {
    $body = get_json_input();
    if (empty($body['nama_fitur'])) send_json(400, ['status' => 'error', 'message' => 'nama_fitur wajib diisi.']);
    $nama = $body['nama_fitur'];
    $urutan = $body['urutan'] ?? 0;
    $stmt = $koneksi->prepare("INSERT INTO fitur (nama_fitur, urutan) VALUES (?, ?)");
    $stmt->bind_param('si', $nama, $urutan);
    if ($stmt->execute()) {
        send_json(201, ['status' => 'success', 'message' => 'Fitur dibuat.', 'data' => ['id' => $stmt->insert_id]]);
    } else {
        send_json(500, ['status' => 'error', 'message' => 'Gagal membuat fitur.']);
    }
} elseif ($method === 'PUT') {
    if (!$id) send_json(400, ['status' => 'error', 'message' => 'ID diperlukan untuk update.']);
    $body = get_json_input();
    $fields = [];
    $types = '';
    $values = [];
    if (isset($body['nama_fitur'])) { $fields[] = 'nama_fitur = ?'; $types .= 's'; $values[] = $body['nama_fitur']; }
    if (isset($body['urutan'])) { $fields[] = 'urutan = ?'; $types .= 'i'; $values[] = intval($body['urutan']); }
    if (empty($fields)) send_json(400, ['status' => 'error', 'message' => 'Tidak ada field untuk diupdate.']);
    $sql = "UPDATE fitur SET " . implode(', ', $fields) . " WHERE id = ?";
    $types .= 'i'; $values[] = $id;
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param($types, ...$values);
    if ($stmt->execute()) send_json(200, ['status' => 'success', 'message' => 'Fitur diperbarui.']);
    send_json(500, ['status' => 'error', 'message' => 'Gagal memperbarui fitur.']);
} elseif ($method === 'DELETE') {
    if (!$id) send_json(400, ['status' => 'error', 'message' => 'ID diperlukan untuk hapus.']);
    $stmt = $koneksi->prepare("DELETE FROM fitur WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) send_json(200, ['status' => 'success', 'message' => 'Fitur dihapus.']);
    send_json(500, ['status' => 'error', 'message' => 'Gagal menghapus fitur.']);
} else {
    send_json(405, ['status' => 'error', 'message' => 'Method not allowed']);
}

$koneksi->close();
?>