<?php
require_once __DIR__ . '/api_common.php';
// RESTful handler for paket resource
include_once __DIR__ . '/bootstrap.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($method === 'GET') {
    try {
        if ($id) {
            $stmt = $koneksi->prepare("SELECT id, nama_paket, harga, is_unggulan FROM paket WHERE id = ? LIMIT 1");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $paket = $res->fetch_assoc();
                // ambil fitur
                $stmt2 = $koneksi->prepare("SELECT f.id, f.nama_fitur FROM fitur f JOIN paket_fitur pf ON f.id = pf.fitur_id WHERE pf.paket_id = ? ORDER BY f.urutan ASC");
                $stmt2->bind_param('i', $id);
                $stmt2->execute();
                $res2 = $stmt2->get_result();
                $paket['fitur'] = $res2->fetch_all(MYSQLI_ASSOC);
                send_json(200, ['status' => 'success', 'data' => $paket]);
            } else {
                send_json(404, ['status' => 'error', 'message' => 'Paket tidak ditemukan.']);
            }
        } else {
            $query = "SELECT id, nama_paket, harga, is_unggulan FROM paket ORDER BY harga ASC";
            $res = $koneksi->query($query);
            $list = [];
            while ($row = $res->fetch_assoc()) {
                $row_id = $row['id'];
                $stmt2 = $koneksi->prepare("SELECT f.id, f.nama_fitur FROM fitur f JOIN paket_fitur pf ON f.id = pf.fitur_id WHERE pf.paket_id = ? ORDER BY f.urutan ASC");
                $stmt2->bind_param('i', $row_id);
                $stmt2->execute();
                $res2 = $stmt2->get_result();
                $row['fitur'] = $res2->fetch_all(MYSQLI_ASSOC);
                $list[] = $row;
            }
            send_json(200, ['status' => 'success', 'data' => $list]);
        }
    } catch (Exception $e) {
        send_json(500, ['status' => 'error', 'message' => 'Server error']);
    }
} elseif ($method === 'POST') {
    $body = get_json_input();
    if (empty($body['nama_paket']) || !isset($body['harga'])) {
        send_json(400, ['status' => 'error', 'message' => 'nama_paket dan harga wajib diisi.']);
    }
    $nama = $body['nama_paket'];
    $harga = $body['harga'];
    $is_unggulan = $body['is_unggulan'] ?? 0;

    $stmt = $koneksi->prepare("INSERT INTO paket (nama_paket, harga, is_unggulan) VALUES (?, ?, ?)");
    $stmt->bind_param('sdi', $nama, $harga, $is_unggulan);
    if ($stmt->execute()) {
        $newId = $stmt->insert_id;
        // optional: insert paket_fitur if provided
        if (!empty($body['fitur']) && is_array($body['fitur'])) {
            foreach ($body['fitur'] as $fid) {
                $stmtf = $koneksi->prepare("INSERT INTO paket_fitur (paket_id, fitur_id) VALUES (?, ?)");
                $stmtf->bind_param('ii', $newId, $fid);
                $stmtf->execute();
            }
        }
        send_json(201, ['status' => 'success', 'message' => 'Paket dibuat.', 'data' => ['id' => $newId]]);
    } else {
        send_json(500, ['status' => 'error', 'message' => 'Gagal membuat paket.']);
    }
} elseif ($method === 'PUT') {
    if (!$id) send_json(400, ['status' => 'error', 'message' => 'ID paket diperlukan untuk update.']);
    $body = get_json_input();
    $fields = [];
    $types = '';
    $values = [];
    if (isset($body['nama_paket'])) { $fields[] = 'nama_paket = ?'; $types .= 's'; $values[] = $body['nama_paket']; }
    if (isset($body['harga'])) { $fields[] = 'harga = ?'; $types .= 'd'; $values[] = $body['harga']; }
    if (isset($body['is_unggulan'])) { $fields[] = 'is_unggulan = ?'; $types .= 'i'; $values[] = $body['is_unggulan']; }
    if (empty($fields)) send_json(400, ['status' => 'error', 'message' => 'Tidak ada field untuk diupdate.']);
    $sql = "UPDATE paket SET " . implode(', ', $fields) . " WHERE id = ?";
    $types .= 'i'; $values[] = $id;
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param($types, ...$values);
    if ($stmt->execute()) {
        send_json(200, ['status' => 'success', 'message' => 'Paket diperbarui.']);
    } else {
        send_json(500, ['status' => 'error', 'message' => 'Gagal memperbarui paket.']);
    }
} elseif ($method === 'DELETE') {
    if (!$id) send_json(400, ['status' => 'error', 'message' => 'ID paket diperlukan untuk hapus.']);
    // hapus relasi paket_fitur terlebih dahulu
    $stmt1 = $koneksi->prepare("DELETE FROM paket_fitur WHERE paket_id = ?");
    $stmt1->bind_param('i', $id);
    $stmt1->execute();
    $stmt = $koneksi->prepare("DELETE FROM paket WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        send_json(200, ['status' => 'success', 'message' => 'Paket dihapus.']);
    } else {
        send_json(500, ['status' => 'error', 'message' => 'Gagal menghapus paket.']);
    }
} else {
    send_json(405, ['status' => 'error', 'message' => 'Method not allowed']);
}

$koneksi->close();
?>