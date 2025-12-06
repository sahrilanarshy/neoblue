<?php
require_once __DIR__ . '/api_common.php';
// users resource (RESTful)
include_once __DIR__ . '/bootstrap.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($method === 'GET') {
	if ($id) {
		$stmt = $koneksi->prepare("SELECT id, nama, email, role, tipe_user FROM users WHERE id = ? LIMIT 1");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$res = $stmt->get_result();
		if ($res && $res->num_rows > 0) send_json(200, ['status' => 'success', 'data' => $res->fetch_assoc()]);
		send_json(404, ['status' => 'error', 'message' => 'User tidak ditemukan.']);
	} else {
		$res = $koneksi->query("SELECT id, nama, email, role, tipe_user FROM users ORDER BY id ASC");
		$list = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
		send_json(200, ['status' => 'success', 'data' => $list]);
	}
} elseif ($method === 'POST') {
	$body = get_json_input();
	if (empty($body['nama']) || empty($body['email']) || empty($body['password'])) send_json(400, ['status' => 'error', 'message' => 'nama, email, password wajib diisi.']);
	$nama = $body['nama'];
	$email = $body['email'];
	$password = password_hash($body['password'], PASSWORD_DEFAULT);
	$role = $body['role'] ?? 'user';
	$tipe_user = $body['tipe_user'] ?? null;
	$stmt = $koneksi->prepare("INSERT INTO users (nama, email, password, role, tipe_user) VALUES (?, ?, ?, ?, ?)");
	$stmt->bind_param('sssss', $nama, $email, $password, $role, $tipe_user);
	if ($stmt->execute()) send_json(201, ['status' => 'success', 'message' => 'User dibuat.', 'data' => ['id' => $stmt->insert_id]]);
	send_json(500, ['status' => 'error', 'message' => 'Gagal membuat user.']);
} elseif ($method === 'PUT') {
	if (!$id) send_json(400, ['status' => 'error', 'message' => 'ID diperlukan untuk update.']);
	$body = get_json_input();
	$fields = [];
	$types = '';
	$values = [];
	if (isset($body['nama'])) { $fields[] = 'nama = ?'; $types .= 's'; $values[] = $body['nama']; }
	if (isset($body['email'])) { $fields[] = 'email = ?'; $types .= 's'; $values[] = $body['email']; }
	if (isset($body['password'])) { $fields[] = 'password = ?'; $types .= 's'; $values[] = password_hash($body['password'], PASSWORD_DEFAULT); }
	if (isset($body['role'])) { $fields[] = 'role = ?'; $types .= 's'; $values[] = $body['role']; }
	if (isset($body['tipe_user'])) { $fields[] = 'tipe_user = ?'; $types .= 's'; $values[] = $body['tipe_user']; }
	if (empty($fields)) send_json(400, ['status' => 'error', 'message' => 'Tidak ada field untuk diupdate.']);
	$sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
	$types .= 'i'; $values[] = $id;
	$stmt = $koneksi->prepare($sql);
	$stmt->bind_param($types, ...$values);
	if ($stmt->execute()) send_json(200, ['status' => 'success', 'message' => 'User diperbarui.']);
	send_json(500, ['status' => 'error', 'message' => 'Gagal memperbarui user.']);
} elseif ($method === 'DELETE') {
	if (!$id) send_json(400, ['status' => 'error', 'message' => 'ID diperlukan untuk hapus.']);
	$stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
	$stmt->bind_param('i', $id);
	if ($stmt->execute()) send_json(200, ['status' => 'success', 'message' => 'User dihapus.']);
	send_json(500, ['status' => 'error', 'message' => 'Gagal menghapus user.']);
} else {
	send_json(405, ['status' => 'error', 'message' => 'Method not allowed']);
}

$koneksi->close();
?>
