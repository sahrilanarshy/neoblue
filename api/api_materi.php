<?php
require_once __DIR__ . '/api_common.php';
// materi resource (RESTful) — integrate bootstrap and keep session auth
include_once __DIR__ . '/bootstrap.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// compatibility wrapper to reuse existing message style
function send_response($status_code, $status, $message, $data = null) {
    $payload = ['status' => $status, 'message' => $message];
    if ($data !== null) $payload['data'] = $data;
    send_json($status_code, $payload);
}

// --- AUTH ---
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    send_response(401, 'error', 'Akses ditolak. Anda harus login.');
}

$method = $_SERVER['REQUEST_METHOD'];
$is_authorized_to_modify = isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'guru']);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $koneksi->prepare("SELECT m.*, s.nama_subtest FROM materi m LEFT JOIN subtest s ON m.subtest_id = s.id WHERE m.id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $materi = $result->fetch_assoc();
                send_response(200, 'success', 'Data materi ditemukan.', $materi);
            } else {
                send_response(404, 'error', 'Materi tidak ditemukan.');
            }
            $stmt->close();
        } else {
            // support optional filter by subtest_id
            if (isset($_GET['subtest_id'])) {
                $subtest_id = intval($_GET['subtest_id']);
                $stmt = $koneksi->prepare("SELECT m.id, m.judul, m.tanggal, m.tipe, s.nama_subtest FROM materi m LEFT JOIN subtest s ON m.subtest_id = s.id WHERE m.subtest_id = ? ORDER BY m.tanggal DESC, m.id DESC");
                $stmt->bind_param("i", $subtest_id);
                $stmt->execute();
                $res = $stmt->get_result();
                $materi_list = [];
                while ($row = $res->fetch_assoc()) $materi_list[] = $row;
                send_response(200, 'success', 'Data materi untuk subtest berhasil diambil.', $materi_list);
                $stmt->close();
            } else {
                $result = $koneksi->query("SELECT m.id, m.judul, m.tanggal, m.tipe, s.nama_subtest FROM materi m LEFT JOIN subtest s ON m.subtest_id = s.id ORDER BY m.tanggal DESC, m.id DESC");
                $materi_list = [];
                while ($row = $result->fetch_assoc()) $materi_list[] = $row;
                send_response(200, 'success', 'Data semua materi berhasil diambil.', $materi_list);
            }
        }
        break;

    case 'POST':
        if (!$is_authorized_to_modify) send_response(403, 'error', 'Anda tidak memiliki izin untuk menambahkan materi.');
        $data = (object) get_json_input();
        if (empty($data->judul) || empty($data->subtest_id) || empty($data->tanggal) || empty($data->tipe)) send_response(400, 'error', 'Semua field wajib diisi.');
        $deskripsi = $data->deskripsi ?? '';
        $stmt = $koneksi->prepare("INSERT INTO materi (judul, deskripsi, subtest_id, tanggal, tipe) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $data->judul, $deskripsi, $data->subtest_id, $data->tanggal, $data->tipe);
        if ($stmt->execute()) send_response(201, 'success', 'Materi baru berhasil ditambahkan.');
        send_response(500, 'error', 'Gagal menambahkan materi: ' . $stmt->error);
        $stmt->close();
        break;

    case 'PUT':
        if (!$is_authorized_to_modify) send_response(403, 'error', 'Anda tidak memiliki izin untuk mengubah materi.');
        $data = (object) get_json_input();
        if (!isset($data->id) || empty($data->judul) || empty($data->subtest_id) || empty($data->tanggal) || empty($data->tipe)) send_response(400, 'error', 'ID dan semua field wajib diisi.');
        $id = intval($data->id);
        $deskripsi = $data->deskripsi ?? '';
        $stmt = $koneksi->prepare("UPDATE materi SET judul = ?, deskripsi = ?, subtest_id = ?, tanggal = ?, tipe = ? WHERE id = ?");
        $stmt->bind_param("ssissi", $data->judul, $deskripsi, $data->subtest_id, $data->tanggal, $data->tipe, $id);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) send_response(200, 'success', 'Materi berhasil diperbarui.');
            send_response(200, 'info', 'Tidak ada perubahan data.');
        } else {
            send_response(500, 'error', 'Gagal memperbarui materi: ' . $stmt->error);
        }
        $stmt->close();
        break;

    case 'DELETE':
        if (!$is_authorized_to_modify) send_response(403, 'error', 'Anda tidak memiliki izin untuk menghapus materi.');
        if (!isset($_GET['id'])) send_response(400, 'error', 'ID materi wajib disertakan.');
        $id = intval($_GET['id']);
        $stmt_select = $koneksi->prepare("SELECT thumbnail FROM materi WHERE id = ?");
        $stmt_select->bind_param("i", $id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        if ($row = $result->fetch_assoc()) {
            $file_path = '../assets/user/img/materi/' . $row['thumbnail'];
            if (file_exists($file_path) && $row['thumbnail'] !== 'default.jpg') unlink($file_path);
        }
        $stmt_select->close();
        $stmt_delete = $koneksi->prepare("DELETE FROM materi WHERE id = ?");
        $stmt_delete->bind_param("i", $id);
        if ($stmt_delete->execute()) {
            if ($stmt_delete->affected_rows > 0) send_response(200, 'success', 'Materi berhasil dihapus.');
            send_response(404, 'error', 'Materi tidak ditemukan untuk dihapus.');
        } else {
            send_response(500, 'error', 'Gagal menghapus materi: ' . $stmt_delete->error);
        }
        $stmt_delete->close();
        break;

    default:
        send_response(405, 'error', 'Metode request tidak diizinkan.');
        break;
}

$koneksi->close();
?>