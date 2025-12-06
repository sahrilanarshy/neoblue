<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['admin', 'guru'])) {
    echo "<script>alert('Akses ditolak!'); window.location.href='../login.php';</script>"; // Perbaiki redirect
    exit();
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
$redirect_url = ".?hal=profile";

if ($aksi == 'update') {
    $user_id = $_SESSION['user_id'];
    $nama = $_POST['fullName'];
    $email = $_POST['email'];
    $telepon = $_POST['noTelp'];
    $password_baru = $_POST['password'];

    // Ambil data lama untuk perbandingan dan penghapusan file lama
    $stmt_old = mysqli_prepare($koneksi, "SELECT foto_profil FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt_old, "i", $user_id);
    mysqli_stmt_execute($stmt_old);
    $result_old_data = mysqli_stmt_get_result($stmt_old);
    $old_data = mysqli_fetch_assoc($result_old_data);
    $foto_profil_lama = $old_data['foto_profil'];

    // Persiapkan query update
    $query_parts = ["nama = ?", "email = ?", "telepon = ?"];
    $params = [$nama, $email, $telepon];
    $types = "sss";
    // Handle upload foto profil
    if (isset($_FILES['foto_upload']) && $_FILES['foto_upload']['error'] == 0) {
        $upload_dir = '../uploads/profile/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = uniqid() . '-' . basename($_FILES["foto_upload"]["name"]);
        $target_file = $upload_dir . $file_name;

        // Cek tipe file (opsional tapi disarankan)
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $_SESSION['gagal'] = "Hanya format JPG, JPEG, & PNG yang diizinkan.";
            echo "<script>window.location.href = '{$redirect_url}';</script>";
            exit;
        }

        if (move_uploaded_file($_FILES["foto_upload"]["tmp_name"], $target_file)) {
            // Hapus foto lama jika ada
            if (!empty($foto_profil_lama) && file_exists('../' . $foto_profil_lama)) {
                unlink('../' . $foto_profil_lama);
            }
            $foto_path_db = 'uploads/profile/' . $file_name;
            $query_parts[] = "foto_profil = ?";
            $params[] = $foto_path_db;
            $types .= "s";
        } else {
            $_SESSION['gagal'] = "Gagal mengunggah foto profil.";
            echo "<script>window.location.href = '{$redirect_url}';</script>";
            exit;
        }
    }

    // Handle update password jika diisi
    if (!empty($password_baru)) {
        if (strlen($password_baru) < 6) {
            $_SESSION['gagal'] = "Password baru minimal harus 6 karakter.";
            echo "<script>window.location.href = '{$redirect_url}';</script>";
            exit;
        }
        $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
        $query_parts[] = "password = ?";
        $params[] = $hashed_password;
        $types .= "s";
    }

    $query_update = "UPDATE users SET " . implode(', ', $query_parts) . " WHERE id = ?";
    $params[] = $user_id;
    $types .= "i";

    $stmt_update = mysqli_prepare($koneksi, $query_update);
    mysqli_stmt_bind_param($stmt_update, $types, ...$params);

    if (mysqli_stmt_execute($stmt_update)) {
        // Perbarui session nama jika berubah
        $_SESSION['nama'] = $nama;
        $_SESSION['sukses'] = "Profil berhasil diperbarui.";
    } else {
        $_SESSION['gagal'] = "Gagal memperbarui profil: " . mysqli_error($koneksi);
    }
} else {
    $_SESSION['gagal'] = "Aksi tidak dikenal.";
}

// Gunakan JavaScript untuk redirect karena header sudah dikirim oleh index.php
echo "<script>window.location.href = '{$redirect_url}';</script>";
exit;
?>