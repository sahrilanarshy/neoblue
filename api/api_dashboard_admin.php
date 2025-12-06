<?php
require_once __DIR__ . '/api_common.php';
header('Content-Type: application/json');
session_start();
require_once '../config/koneksi.php';

$response = [
    'status' => 'error',
    'message' => 'Akses ditolak.',
    'data' => null
];

// 1. Validasi Method (Hanya GET)
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    $response['message'] = 'Method not allowed. Gunakan GET.';
    echo json_encode($response);
    exit();
}

// 2. Cek Login & Role
if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['admin', 'guru'])) {
    http_response_code(403); // Forbidden
    $response['message'] = 'Akses ditolak. Anda harus login sebagai admin atau guru.';
    echo json_encode($response);
    exit();
}

try {
    // 3. Eksekusi Query
    
    // a. Menghitung Jumlah Siswa
    $querySiswa = $koneksi->query("SELECT COUNT(id) as total FROM users WHERE role = 'siswa'");
    $jumlah_siswa = $querySiswa->fetch_assoc()['total'] ?? 0;

    // b. Menghitung Jumlah Guru
    $queryGuru = $koneksi->query("SELECT COUNT(id) as total FROM users WHERE role = 'guru'");
    $jumlah_guru = $queryGuru->fetch_assoc()['total'] ?? 0;

    // c. Menghitung Pesan Masuk
    $queryPesan = $koneksi->query("SELECT COUNT(id) as total FROM kontak");
    $pesan_masuk = $queryPesan->fetch_assoc()['total'] ?? 0;

    // d. Menghitung Total Pendapatan (status Diterima)
    $queryPendapatan = $koneksi->query("SELECT SUM(pk.harga) as total FROM pembayaran p JOIN paket pk ON p.paket_id = pk.id WHERE p.status_pembayaran = 'Diterima'");
    $total_pendapatan = $queryPendapatan->fetch_assoc()['total'] ?? 0;
    $total_pendapatan_formatted = 'Rp ' . number_format($total_pendapatan, 0, ',', '.');

    // e. Statistik User (Free vs Premium)
    $queryUserFree = $koneksi->query("SELECT COUNT(id) as total FROM users WHERE tipe_user = 'Free' AND role = 'siswa'");
    $user_free = $queryUserFree->fetch_assoc()['total'] ?? 0;
    
    $queryUserPremium = $koneksi->query("SELECT COUNT(id) as total FROM users WHERE tipe_user = 'Premium' AND role = 'siswa'");
    $user_premium = $queryUserPremium->fetch_assoc()['total'] ?? 0;
    
    $statistik_user = ['free' => (int)$user_free, 'premium' => (int)$user_premium];

    // f. Statistik Pendapatan Bulanan (Tahun Ini)
    $statistik_pendapatan = array_fill(0, 12, 0); // Inisialisasi array 12 bulan dengan 0
    $tahun_ini = date('Y');
    
    $queryBulanan = $koneksi->prepare("
        SELECT MONTH(p.tanggal_konfirmasi) as bulan, SUM(pk.harga) as total_bulanan 
        FROM pembayaran p
        JOIN paket pk ON p.paket_id = pk.id
        WHERE p.status_pembayaran = 'Diterima' AND YEAR(p.tanggal_konfirmasi) = ?
        GROUP BY MONTH(p.tanggal_konfirmasi)
    ");
    $queryBulanan->bind_param("i", $tahun_ini);
    
    if ($queryBulanan->execute()) {
        $resultBulanan = $queryBulanan->get_result();
        while ($row = $resultBulanan->fetch_assoc()) {
            $bulan_index = (int)$row['bulan'] - 1; // array index 0-11
            $statistik_pendapatan[$bulan_index] = (float)$row['total_bulanan'];
        }
        $queryBulanan->close();
    }

    // 4. Response Sukses
    http_response_code(200);
    $response['status'] = 'success';
    $response['message'] = 'Data dashboard berhasil diambil.';
    $response['data'] = [
        'jumlah_siswa' => (int)$jumlah_siswa,
        'jumlah_guru' => (int)$jumlah_guru,
        'pesan_masuk' => (int)$pesan_masuk,
        'total_pendapatan_formatted' => $total_pendapatan_formatted,
        'statistik_user' => $statistik_user,
        'statistik_pendapatan' => $statistik_pendapatan
    ];

} catch (Exception $e) {
    // 5. Response Error
    http_response_code(500); // Internal Server Error
    $response['status'] = 'error';
    $response['message'] = 'Terjadi error pada server: ' . $e->getMessage();
}

echo json_encode($response);
$koneksi->close();
?>