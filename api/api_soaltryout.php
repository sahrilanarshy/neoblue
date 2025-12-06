<?php
require_once __DIR__ . '/api_common.php';
// soal tryout — use prepared statements and bootstrap
include_once __DIR__ . '/bootstrap.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$id_tryout = isset($_GET['id_tryout']) ? intval($_GET['id_tryout']) : 0;
$data = [];

if ($id_tryout > 0) {
    $query = "SELECT
                t.id AS tryout_id,
                t.nama_tryout,
                s.id AS subtest_id,
                s.nama_subtest,
                ts.waktu_pengerjaan,
                COUNT(st.id) AS jumlah_soal
                FROM soal_tryout st
                JOIN tryout t ON st.tryout_id = t.id
                JOIN subtest s ON st.subtest_id = s.id
                LEFT JOIN tryout_subtest ts ON st.tryout_id = ts.tryout_id AND st.subtest_id = ts.subtest_id
                WHERE st.tryout_id = ?
                GROUP BY st.tryout_id, st.subtest_id
                ORDER BY s.nama_subtest ASC";

    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('i', $id_tryout);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) $data[] = $row;
    $stmt->close();
}

send_json(200, ['data' => $data]);
?>
