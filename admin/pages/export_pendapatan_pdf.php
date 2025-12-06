<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Asumsi FPDF ada di direktori vendor/fpdf186
require('../vendor/fpdf186/fpdf.php');
include '../config/koneksi.php';

// Keamanan: Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    die("Akses ditolak.");
}

class PDF extends FPDF
{
    // Header Halaman
    function Header()
    {
        // Logo (opsional)
        // $this->Image('path/to/logo.png',10,6,30);
        
        // Set Font
        $this->SetFont('Arial', 'B', 14);
        // Geser ke tengah
        $this->Cell(0, 10, 'Laporan Pendapatan Bulanan', 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, 'NeoBlue - ' . date('d F Y'), 0, 1, 'C');
        // Garis bawah
        $this->Line(10, 30, 200, 30);
        // Line break
        $this->Ln(10);
    }

    // Footer Halaman
    function Footer()
    {
        // Posisi 1.5 cm dari bawah
        $this->SetY(-15);
        // Set Font
        $this->SetFont('Arial', 'I', 8);
        // Nomor halaman
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Tabel data
    function FancyTable($header, $data)
    {
        // Warna, lebar, dan font untuk header
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetDrawColor(128, 0, 0);
        $this->SetFont('', 'B');
        
        // Lebar kolom
        $w = array(15, 50, 35, 45, 45);
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();

        // Restore font dan warna
        $this->SetFont('');
        $this->SetFillColor(245, 245, 245);
        $this->SetTextColor(0);

        $fill = false;
        $total_pendapatan_keseluruhan = 0;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $row[2], 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 6, $row[3], 'LR', 0, 'R', $fill);
            $this->Cell($w[4], 6, $row[4], 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
            $total_pendapatan_keseluruhan += $row[5]; // Ambil nilai float asli
        }
        // Garis penutup tabel
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->Ln();

        // Baris Total
        $this->SetFont('', 'B');
        $this->Cell($w[0] + $w[1] + $w[2], 7, 'Total Pendapatan Keseluruhan', 1, 0, 'R');
        $this->Cell($w[3] + $w[4], 7, 'Rp' . number_format($total_pendapatan_keseluruhan, 0, ',', '.'), 1, 1, 'L');
    }
}

// Ambil data dari database
$query = "SELECT DATE_FORMAT(p.tanggal_konfirmasi, '%M %Y') as bulan, COUNT(p.id) as jumlah_transaksi, SUM(pk.harga) as total_pendapatan FROM pembayaran p JOIN paket pk ON p.paket_id = pk.id WHERE p.status_pembayaran = 'Diterima' GROUP BY DATE_FORMAT(p.tanggal_konfirmasi, '%M %Y') ORDER BY MIN(p.tanggal_konfirmasi) DESC";
$result = mysqli_query($koneksi, $query);

$data_for_pdf = [];
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $totalPendapatan = (float)$row['total_pendapatan'];
    $jumlahTransaksi = (int)$row['jumlah_transaksi'];
    $rataRata = $jumlahTransaksi > 0 ? $totalPendapatan / $jumlahTransaksi : 0;
    $data_for_pdf[] = [
        $no++,
        $row['bulan'],
        $jumlahTransaksi,
        'Rp' . number_format($totalPendapatan, 0, ',', '.'),
        'Rp' . number_format($rataRata, 0, ',', '.'),
        $totalPendapatan // Nilai float untuk kalkulasi
    ];
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);
$header = ['No', 'Bulan', 'Jml Transaksi', 'Total Pendapatan', 'Rata-rata'];
$pdf->FancyTable($header, $data_for_pdf);
$pdf->Output('D', 'Laporan_Pendapatan_NeoBlue_' . date('Y-m-d') . '.pdf');
?>