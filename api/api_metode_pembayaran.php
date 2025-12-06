<?php
require_once __DIR__ . '/api_common.php';
// metode_pembayaran resource (RESTful)
include_once __DIR__ . '/bootstrap.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($method === 'GET') {
    // optional: show all if ?all=1
    $all = isset($_GET['all']) && $_GET['all'] == '1';
    if ($id) {
        $stmt = $koneksi->prepare("SELECT id, nama_metode, nomor_rekening, atas_nama, status FROM metode_pembayaran WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) send_json(200, ['status' => 'success', 'data' => $res->fetch_assoc()]);
        send_json(404, ['status' => 'error', 'message' => 'Metode pembayaran tidak ditemukan.']);
    } else {
        if ($all) $query = "SELECT id, nama_metode, nomor_rekening, atas_nama, status FROM metode_pembayaran ORDER BY nama_metode ASC";
        else $query = "SELECT id, nama_metode, nomor_rekening, atas_nama FROM metode_pembayaran WHERE status = 'Aktif' ORDER BY nama_metode ASC";
        $res = $koneksi->query($query);
        $list = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        send_json(200, ['status' => 'success', 'data' => $list]);
    }
} elseif ($method === 'POST') {
    $body = get_json_input();
    if (empty($body['nama_metode']) || empty($body['nomor_rekening'])) send_json(400, ['status' => 'error', 'message' => 'nama_metode dan nomor_rekening wajib diisi.']);
    $nama = $body['nama_metode'];
    $nomor = $body['nomor_rekening'];
    $atas = $body['atas_nama'] ?? null;
    $status = $body['status'] ?? 'Aktif';
    $stmt = $koneksi->prepare("INSERT INTO metode_pembayaran (nama_metode, nomor_rekening, atas_nama, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $nama, $nomor, $atas, $status);
    if ($stmt->execute()) send_json(201, ['status' => 'success', 'message' => 'Metode pembayaran dibuat.', 'data' => ['id' => $stmt->insert_id]]);
    send_json(500, ['status' => 'error', 'message' => 'Gagal membuat metode pembayaran.']);
} elseif ($method === 'PUT') {
    if (!$id) send_json(400, ['status' => 'error', 'message' => 'ID diperlukan untuk update.']);
    $body = get_json_input();
    $fields = [];
    $types = '';
    $values = [];
    if (isset($body['nama_metode'])) { $fields[] = 'nama_metode = ?'; $types .= 's'; $values[] = $body['nama_metode']; }
    if (isset($body['nomor_rekening'])) { $fields[] = 'nomor_rekening = ?'; $types .= 's'; $values[] = $body['nomor_rekening']; }
    if (isset($body['atas_nama'])) { $fields[] = 'atas_nama = ?'; $types .= 's'; $values[] = $body['atas_nama']; }
    if (isset($body['status'])) { $fields[] = 'status = ?'; $types .= 's'; $values[] = $body['status']; }
    if (empty($fields)) send_json(400, ['status' => 'error', 'message' => 'Tidak ada field untuk diupdate.']);
    $sql = "UPDATE metode_pembayaran SET " . implode(', ', $fields) . " WHERE id = ?";
    $types .= 'i'; $values[] = $id;
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param($types, ...$values);
    if ($stmt->execute()) send_json(200, ['status' => 'success', 'message' => 'Metode pembayaran diperbarui.']);
    send_json(500, ['status' => 'error', 'message' => 'Gagal memperbarui metode pembayaran.']);
} elseif ($method === 'DELETE') {
    if (!$id) send_json(400, ['status' => 'error', 'message' => 'ID diperlukan untuk hapus.']);
    $stmt = $koneksi->prepare("DELETE FROM metode_pembayaran WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) send_json(200, ['status' => 'success', 'message' => 'Metode pembayaran dihapus.']);
    send_json(500, ['status' => 'error', 'message' => 'Gagal menghapus metode pembayaran.']);
} else {
    send_json(405, ['status' => 'error', 'message' => 'Method not allowed']);
}

$koneksi->close();
?>