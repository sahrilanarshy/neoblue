<?php
require_once __DIR__ . '/api_common.php';
// tryout listing — add bootstrap and consistent response
include_once __DIR__ . '/bootstrap.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$is_premium = (isset($_SESSION['tipe_user']) && $_SESSION['tipe_user'] == 'Premium');

$query = "SELECT id, nama_tryout, tanggal_mulai, tanggal_selesai, tipe FROM tryout ORDER BY tanggal_mulai ASC";
$res = $koneksi->query($query);
$tryouts = [];
if ($res) while ($row = $res->fetch_assoc()) $tryouts[] = $row;

send_json(200, ['is_premium' => $is_premium, 'tryouts' => $tryouts]);
?>