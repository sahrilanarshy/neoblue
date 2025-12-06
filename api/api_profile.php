<?php
require_once __DIR__ . '/api_common.php';
// Ganti bootstrap.php dengan koneksi.php agar konsisten dengan file lain
require_once '../config/koneksi.php';

session_start();

// --- FUNGSI RESPONSE JSON ---
function send_response($status_code, $status, $message, $data = null) {
    // Bersihkan buffer output agar tidak ada HTML nyasar
    if (ob_get_length()) ob_clean(); 
    
    header('Content-Type: application/json');
    http_response_code($status_code);
    $response = ['status' => $status, 'message' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}

// --- CEK LOGIN ---
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true || !isset($_SESSION['user_id'])) {
    send_response(401, 'error', 'Akses ditolak. Silakan login terlebih dahulu.');
}

$user_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            // Ambil data user terbaru
            $stmt = $koneksi->prepare("SELECT nama, email, telepon, role, tipe_user, foto_profil FROM users WHERE id = ?");
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $res = $stmt->get_result();
            $user_data = $res ? $res->fetch_assoc() : null;
            
            if ($user_data) {
                // UPDATE SESSION: Pastikan session selalu sinkron saat API ini dipanggil
                $_SESSION['foto_profil'] = $user_data['foto_profil']; 
                $_SESSION['nama'] = $user_data['nama'];
                
                send_response(200, 'success', 'Data profil berhasil diambil.', $user_data);
            }
            send_response(404, 'error', 'Data pengguna tidak ditemukan.');
            break;

        case 'POST':
            // --- LOGIKA UPDATE PROFIL ---
            $nama = $_POST['nama'] ?? '';
            $telepon = $_POST['telepon'] ?? '';
            $password_sekarang = $_POST['password_sekarang'] ?? '';
            $password_baru = $_POST['password_baru'] ?? '';

            $foto_path_db = null;

            if (empty($nama) || empty($telepon)) {
                send_response(400, 'error', 'Nama dan telepon tidak boleh kosong.');
            }

            // Persiapan Query Update
            $update_fields = ['nama = ?', 'telepon = ?'];
            $params = [$nama, $telepon];
            $types = 'ss';

            // 1. Handle Upload Foto
            if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
                $upload_dir = __DIR__ . '/../uploads/profile/'; // Pastikan folder ini ada
                
                // Buat folder jika belum ada
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                
                $file_extension = strtolower(pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION));
                $file_name = uniqid() . '.' . $file_extension;
                $target_file = $upload_dir . $file_name;
                
                // Validasi
                if (!in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
                    send_response(400, 'error', 'Format gambar harus JPG, JPEG, atau PNG.');
                }
                if ($_FILES['foto_profil']['size'] > 2000000) { // 2MB
                    send_response(400, 'error', 'Ukuran file maksimal adalah 2MB.');
                }
                
                if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $target_file)) {
                    // Hapus foto lama jika ada
                    $stmt_old = $koneksi->prepare("SELECT foto_profil FROM users WHERE id = ?");
                    $stmt_old->bind_param('i', $user_id);
                    $stmt_old->execute();
                    $r = $stmt_old->get_result();
                    if ($old = $r->fetch_assoc()) {
                        if (!empty($old['foto_profil']) && file_exists(__DIR__ . '/../' . $old['foto_profil'])) {
                            unlink(__DIR__ . '/../' . $old['foto_profil']);
                        }
                    }
                    
                    // Set path untuk database
                    $foto_path_db = 'uploads/profile/' . $file_name;
                }
            }

            // 2. Handle Ganti Password
            if (!empty($password_sekarang) || !empty($password_baru)) {
                if (empty($password_sekarang) || empty($password_baru)) {
                    send_response(400, 'error', 'Isi password sekarang dan baru jika ingin mengganti.');
                }
                
                $stmt_pass = $koneksi->prepare("SELECT password FROM users WHERE id = ?");
                $stmt_pass->bind_param('i', $user_id);
                $stmt_pass->execute();
                $res_pass = $stmt_pass->get_result();
                $user_pass = $res_pass ? $res_pass->fetch_assoc() : null;
                
                if ($user_pass && password_verify($password_sekarang, $user_pass['password'])) {
                    $hashed = password_hash($password_baru, PASSWORD_DEFAULT);
                    $update_fields[] = 'password = ?';
                    $params[] = $hashed;
                    $types .= 's';
                } else {
                    send_response(400, 'error', 'Password saat ini salah.');
                }
            }

            // Tambahkan field foto ke query jika ada upload
            if ($foto_path_db) {
                $update_fields[] = 'foto_profil = ?';
                $params[] = $foto_path_db;
                $types .= 's';
            }

            // Eksekusi Update
            $query = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE id = ?";
            $params[] = $user_id;
            $types .= 'i';
            
            $stmt_up = $koneksi->prepare($query);
            $stmt_up->bind_param($types, ...$params);
            
            if ($stmt_up->execute()) {
                // PENTING: Update Session agar perubahan langsung terlihat di Header
                $_SESSION['nama'] = $nama;
                if ($foto_path_db) {
                    $_SESSION['foto_profil'] = $foto_path_db;
                }
                
                send_response(200, 'success', 'Profil berhasil diperbarui.', [
                    'foto_profil' => $foto_path_db // Kirim balik path foto baru
                ]);
            }
            
            throw new Exception('Gagal memperbarui profil di database.');
            break;

        default:
            send_response(405, 'error', 'Metode tidak diizinkan.');
            break;
    }
} catch (Exception $e) {
    send_response(500, 'error', 'Server Error: ' . $e->getMessage());
} finally {
    if (isset($stmt)) $stmt->close();
    $koneksi->close();
}
?>