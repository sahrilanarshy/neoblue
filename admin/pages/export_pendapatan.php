<?php
session_start();
include '../config/koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    die("Akses ditolak.");
    exit();
}

// Nama file untuk diunduh
$filename = "laporan_pendapatan_" . date('Y-m-d') . ".csv";

// Ambil data pendapatan dari database
$filename_xls = "laporan_pendapatan_" . date('Y-m-d') . ".xls";

// Set header HTTP agar file dibuka oleh Excel (HTML table inside .xls)
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename_xls . '"');

// Pastikan tidak ada output sebelumnya yang mengacaukan file
if (ob_get_level()) {
    ob_end_clean();
}

// Tulis BOM UTF-8 agar Excel membaca karakter UTF-8
echo "\xEF\xBB\xBF";

$query = "
    SELECT 
        DATE_FORMAT(p.tanggal_konfirmasi, '%M %Y') as bulan,
        COUNT(p.id) as jumlah_transaksi,
        SUM(pk.harga) as total_pendapatan
    FROM 
        pembayaran p
    JOIN 
        paket pk ON p.paket_id = pk.id
    WHERE 
        p.status_pembayaran = 'Diterima'
    GROUP BY 
        DATE_FORMAT(p.tanggal_konfirmasi, '%M %Y')
    ORDER BY 
        MIN(p.tanggal_konfirmasi) DESC
";

$result = mysqli_query($koneksi, $query);

$rows = [];
$no = 1;
$total_pendapatan_keseluruhan = 0;
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $totalPendapatan = (float)$row['total_pendapatan'];
        $jumlahTransaksi = (int)$row['jumlah_transaksi'];
        $rataRata = $jumlahTransaksi > 0 ? $totalPendapatan / $jumlahTransaksi : 0;
        $total_pendapatan_keseluruhan += $totalPendapatan;

        $rows[] = [
            'no' => $no++,
            'bulan' => $row['bulan'],
            'jumlah_transaksi' => $jumlahTransaksi,
            'total_pendapatan' => $totalPendapatan,
            'rata_rata' => $rataRata,
        ];
    }
}

// Output sebagai HTML table agar Excel menampilkan format yang lebih baik
echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /></head><body>";
echo "<table border='0' cellpadding='5' cellspacing='0' style='font-family:Arial,sans-serif;'>";
echo "<tr><td colspan='5' style='font-size:16px;font-weight:bold;padding:8px 0;'>Laporan Pendapatan Bulanan</td></tr>";
echo "<tr><td colspan='5' style='padding-bottom:8px;'>Tanggal Generate: " . date('d F Y H:i') . "</td></tr>";
echo "</table>";

echo "<table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse;font-family:Arial,sans-serif;width:100%;'>";
echo "<thead><tr style='background:#f2f2f2;'>";
echo "<th>No</th><th>Bulan</th><th>Jumlah Transaksi</th><th>Total Pendapatan</th><th>Rata-rata per Transaksi</th>";
echo "</tr></thead><tbody>";

foreach ($rows as $r) {
    echo "<tr>";
    echo "<td style='text-align:center;'>" . $r['no'] . "</td>";
    echo "<td>" . htmlspecialchars($r['bulan']) . "</td>";
    echo "<td style='text-align:center;'>" . number_format($r['jumlah_transaksi']) . "</td>";
    echo "<td style='text-align:right;'>Rp" . number_format($r['total_pendapatan'], 0, ',', '.') . "</td>";
    echo "<td style='text-align:right;'>Rp" . number_format($r['rata_rata'], 0, ',', '.') . "</td>";
    echo "</tr>";
}

// Total row
echo "</tbody><tfoot>";
echo "<tr style='font-weight:bold;background:#e9ffe9;'>";
echo "<td colspan='3' style='text-align:right;'>Total Pendapatan Keseluruhan</td>";
echo "<td colspan='2' style='text-align:right;'>Rp" . number_format($total_pendapatan_keseluruhan, 0, ',', '.') . "</td>";
echo "</tr>";
echo "</tfoot></table>";

echo "</body></html>";
exit();
?>