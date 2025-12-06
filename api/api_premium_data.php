<?php
require_once __DIR__ . '/api_common.php';
// api_premium_data.php
// Mengembalikan data yang diperlukan di halaman Premium dalam satu request:
// { status: 'success', data: { paket: [...], metode_pembayaran: [...] } }

include_once __DIR__ . '/bootstrap.php';

try {
    // Ambil semua paket beserta fitur
    $paket_query = "SELECT id, nama_paket, harga, is_unggulan FROM paket ORDER BY harga ASC";
    $paket_res = $koneksi->query($paket_query);
    $paket_list = [];
    if ($paket_res) {
        while ($row = $paket_res->fetch_assoc()) {
            $row_id = $row['id'];
            $stmt2 = $koneksi->prepare("SELECT f.id, f.nama_fitur FROM fitur f JOIN paket_fitur pf ON f.id = pf.fitur_id WHERE pf.paket_id = ? ORDER BY f.urutan ASC");
            $stmt2->bind_param('i', $row_id);
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            $row['fitur'] = $res2 ? $res2->fetch_all(MYSQLI_ASSOC) : [];
            $paket_list[] = $row;
        }
    }

    // Ambil metode pembayaran aktif
    $metode_query = "SELECT id, nama_metode, nomor_rekening, atas_nama FROM metode_pembayaran WHERE status = 'Aktif' ORDER BY nama_metode ASC";
    $metode_res = $koneksi->query($metode_query);
    $metode_list = $metode_res ? $metode_res->fetch_all(MYSQLI_ASSOC) : [];

    send_json(200, ['status' => 'success', 'data' => ['paket' => $paket_list, 'metode_pembayaran' => $metode_list]]);
} catch (Exception $e) {
    send_json(500, ['status' => 'error', 'message' => 'Server error']);
}

$koneksi->close();
?>
