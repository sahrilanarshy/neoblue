<?php
require_once __DIR__ . '/api_common.php';
// RESTful handler for artikel resource
include_once __DIR__ . '/bootstrap.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($method === 'GET') {
    if ($id) {
        $stmt = $koneksi->prepare("SELECT id, judul, kategori, tanggal_publikasi, gambar, konten AS isi_konten FROM artikel WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $row['gambar_url'] = !empty($row['gambar']) ? $row['gambar'] : 'assets/landingpage/img/artikel/default.jpg';
            send_json(200, ['status' => 'success', 'data' => $row]);
        } else {
            send_json(404, ['status' => 'error', 'message' => 'Artikel tidak ditemukan.']);
        }
    } else {
        $query = "SELECT id, judul, kategori, tanggal_publikasi, gambar, LEFT(konten, 150) as ringkasan FROM artikel ORDER BY tanggal_publikasi DESC";
        $res = $koneksi->query($query);
        $list = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $row['gambar_url'] = !empty($row['gambar']) ? $row['gambar'] : 'assets/landingpage/img/artikel/default.jpg';
                $list[] = $row;
            }
            send_json(200, ['status' => 'success', 'data' => $list]);
        } else {
            send_json(500, ['status' => 'error', 'message' => 'Gagal mengambil data artikel.']);
        }
    }
} elseif ($method === 'POST') {
    $body = get_json_input();
    // minimal validation
    if (empty($body['judul']) || empty($body['konten'])) {
        send_json(400, ['status' => 'error', 'message' => 'Judul dan konten wajib diisi.']);
    }
    $judul = $body['judul'];
    $kategori = $body['kategori'] ?? null;
    $konten = $body['konten'];
    $gambar = $body['gambar'] ?? null;
    $tanggal = $body['tanggal_publikasi'] ?? date('Y-m-d H:i:s');

    $stmt = $koneksi->prepare("INSERT INTO artikel (judul, kategori, tanggal_publikasi, gambar, konten) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssss', $judul, $kategori, $tanggal, $gambar, $konten);
    if ($stmt->execute()) {
        $newId = $stmt->insert_id;
        send_json(201, ['status' => 'success', 'message' => 'Artikel dibuat.', 'data' => ['id' => $newId]]);
    } else {
        send_json(500, ['status' => 'error', 'message' => 'Gagal membuat artikel.']);
    }
} elseif ($method === 'PUT') {
    if (!$id) {
        send_json(400, ['status' => 'error', 'message' => 'ID artikel diperlukan untuk update.']);
    }
    $body = get_json_input();
    $judul = $body['judul'] ?? null;
    $kategori = $body['kategori'] ?? null;
    $konten = $body['konten'] ?? null;
    $gambar = $body['gambar'] ?? null;
    $tanggal = $body['tanggal_publikasi'] ?? null;

    // Build dynamic update
    $fields = [];
    $types = '';
    $values = [];
    if ($judul !== null) { $fields[] = 'judul = ?'; $types .= 's'; $values[] = $judul; }
    if ($kategori !== null) { $fields[] = 'kategori = ?'; $types .= 's'; $values[] = $kategori; }
    if ($tanggal !== null) { $fields[] = 'tanggal_publikasi = ?'; $types .= 's'; $values[] = $tanggal; }
    if ($gambar !== null) { $fields[] = 'gambar = ?'; $types .= 's'; $values[] = $gambar; }
    if ($konten !== null) { $fields[] = 'konten = ?'; $types .= 's'; $values[] = $konten; }

    if (empty($fields)) {
        send_json(400, ['status' => 'error', 'message' => 'Tidak ada field untuk diupdate.']);
    }

    $sql = "UPDATE artikel SET " . implode(', ', $fields) . " WHERE id = ?";
    $types .= 'i';
    $values[] = $id;
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param($types, ...$values);
    if ($stmt->execute()) {
        send_json(200, ['status' => 'success', 'message' => 'Artikel diperbarui.']);
    } else {
        send_json(500, ['status' => 'error', 'message' => 'Gagal memperbarui artikel.']);
    }
} elseif ($method === 'DELETE') {
    if (!$id) {
        send_json(400, ['status' => 'error', 'message' => 'ID artikel diperlukan untuk hapus.']);
    }
    $stmt = $koneksi->prepare("DELETE FROM artikel WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        send_json(200, ['status' => 'success', 'message' => 'Artikel dihapus.']);
    } else {
        send_json(500, ['status' => 'error', 'message' => 'Gagal menghapus artikel.']);
    }
} else {
    send_json(405, ['status' => 'error', 'message' => 'Method not allowed']);
}

$koneksi->close();
?>