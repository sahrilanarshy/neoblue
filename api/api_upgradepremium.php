<?php
require_once __DIR__ . '/api_common.php';
// API untuk meng-upgrade / mengubah status langganan user (Premium/Free)
// MODIFIED FOR MOBILE APP INTEGRATION
// - Menerima POST
// - Validasi Admin DIHAPUS/DILONGGARKAN agar user bisa upgrade via aplikasi

include_once __DIR__ . '/bootstrap.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Hanya POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json(405, ['status' => 'error', 'message' => 'Metode request tidak diizinkan. Gunakan POST.']);
}

// --------------------------------------------------------------------------
// BAGIAN INI DIMODIFIKASI UNTUK AKSES MOBILE
// --------------------------------------------------------------------------
// Jika ingin tetap aman, idealnya gunakan token. 
// Untuk saat ini, kita izinkan akses jika ada parameter dari Mobile App
$input = get_json_input();
if (empty($input)) {
    $input = $_POST;
}

// Jika request datang dari browser/admin panel, tetap cek session
// Tapi jika dari Mobile (biasanya tidak bawa cookie session admin), kita bypass
// logika sederhana: jika tidak ada session admin, kita anggap ini request sistem/mobile
$isAdmin = (isset($_SESSION['status_login']) && $_SESSION['status_login'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

// Opsional: Anda bisa menambahkan "secret key" di header aplikasi android jika ingin lebih aman
// if (!$isAdmin) {
//      // Logic bypass untuk mobile, misalnya cek user_id valid
// }
// --------------------------------------------------------------------------

$user_id = isset($input['user_id']) ? intval($input['user_id']) : 0;
$tipe_user = isset($input['tipe_user']) ? trim($input['tipe_user']) : 'Premium';
$masa_aktif = isset($input['masa_aktif']) && $input['masa_aktif'] !== '' ? $input['masa_aktif'] : null;
$pembayaran_id = isset($input['pembayaran_id']) ? intval($input['pembayaran_id']) : 0;

if ($user_id <= 0) {
    send_json(400, ['status' => 'error', 'message' => 'Parameter user_id diperlukan.']);
}

// Normalisasi tipe_user
$tipe_user = ($tipe_user === 'Premium' || strtolower($tipe_user) === 'premium') ? 'Premium' : 'Free';

// Validasi tanggal
if ($tipe_user === 'Premium' && $masa_aktif !== null) {
    $d = DateTime::createFromFormat('Y-m-d', $masa_aktif);
    if (!$d || $d->format('Y-m-d') !== $masa_aktif) {
        send_json(400, ['status' => 'error', 'message' => 'Format masa_aktif tidak valid. Gunakan YYYY-MM-DD atau kosongkan.']);
    }
}

// Tentukan Admin ID (Jika dari mobile, gunakan ID 0 atau ID user itu sendiri sebagai penanda)
$admin_id = $isAdmin ? $_SESSION['user_id'] : 0; 

$koneksi->begin_transaction();
try {
    // Opsional: tandai pembayaran sebagai Diterima
    if ($pembayaran_id > 0) {
        // Jika admin_id 0, mungkin perlu diupdate querynya agar tidak error foreign key (tergantung struktur DB)
        // Asumsi: admin_id boleh 0 atau NULL, atau tabel pembayaran mengizinkan null
        
        $stmt_pay = $koneksi->prepare("UPDATE pembayaran SET status_pembayaran = 'Diterima', tanggal_konfirmasi = NOW(), admin_id = ? WHERE id = ? AND status_pembayaran = 'Menunggu'");
        $stmt_pay->bind_param('ii', $admin_id, $pembayaran_id);
        if (!$stmt_pay->execute()) {
            throw new Exception('Gagal memperbarui status pembayaran: ' . $stmt_pay->error);
        }
        $stmt_pay->close();
    }

    // Update users
    if ($tipe_user === 'Premium') {
        if ($masa_aktif === null) {
            $stmt = $koneksi->prepare("UPDATE users SET tipe_user = 'Premium', masa_aktif = NULL WHERE id = ?");
            $stmt->bind_param('i', $user_id);
        } else {
            $stmt = $koneksi->prepare("UPDATE users SET tipe_user = 'Premium', masa_aktif = ? WHERE id = ?");
            $stmt->bind_param('si', $masa_aktif, $user_id);
        }
    } else {
        $stmt = $koneksi->prepare("UPDATE users SET tipe_user = 'Free', masa_aktif = NULL WHERE id = ?");
        $stmt->bind_param('i', $user_id);
    }

    if (!$stmt->execute()) {
        throw new Exception('Gagal memperbarui data pengguna: ' . $stmt->error);
    }
    $stmt->close();

    // Ambil data user yang diupdate untuk dikembalikan
    $stmt_u = $koneksi->prepare("SELECT id, nama, email, role, tipe_user, masa_aktif FROM users WHERE id = ? LIMIT 1");
    $stmt_u->bind_param('i', $user_id);
    $stmt_u->execute();
    $res = $stmt_u->get_result();
    $user = $res->fetch_assoc();
    $stmt_u->close();

    $koneksi->commit();

    send_json(200, ['status' => 'success', 'message' => 'Status langganan berhasil diperbarui.', 'data' => ['user' => $user, 'pembayaran_id' => $pembayaran_id > 0 ? $pembayaran_id : null]]);
} catch (Exception $e) {
    $koneksi->rollback();
    send_json(500, ['status' => 'error', 'message' => $e->getMessage()]);
}
?>