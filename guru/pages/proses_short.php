<?php
// session_start() tidak diperlukan karena sudah dipanggil di index.php
include '../config/koneksi.php';

if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['admin', 'guru'])) {
    echo "<script>alert('Akses ditolak!'); window.location.href='../login.php';</script>";
    exit();
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
$redirect_url = "index.php?hal=short";
$upload_dir = '../uploads/shorts/';

// Pastikan direktori upload ada
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($aksi == 'tambah') {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul_short']);
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal_upload']);
    $tipe = isset($_POST['is_premium']) && $_POST['is_premium'] == '1' ? 'Premium' : 'Free';

    if (isset($_FILES['video_short']) && $_FILES['video_short']['error'] == 0) {
        $video_name = uniqid() . '-' . basename($_FILES["video_short"]["name"]);
        $target_file = $upload_dir . $video_name;

        if (move_uploaded_file($_FILES["video_short"]["tmp_name"], $target_file)) {
            $video_path = 'uploads/shorts/' . $video_name;
            $query = "INSERT INTO shorts (judul, tanggal_upload, video_path, tipe) VALUES ('$judul', '$tanggal', '$video_path', '$tipe')";
            
            if (mysqli_query($koneksi, $query)) {
                $_SESSION['sukses'] = "Short berhasil ditambahkan.";
            } else {
                $_SESSION['gagal'] = "Gagal menyimpan data ke database: " . mysqli_error($koneksi); // Tambahkan detail error
                unlink($target_file); // Hapus file jika query gagal
            }
        } else {
            $_SESSION['gagal'] = "Gagal mengunggah file video.";
        }
    } else {
        $_SESSION['gagal'] = "Tidak ada file video yang diunggah atau terjadi error.";
    }

} elseif ($aksi == 'edit') {
    $id = intval($_POST['id']);
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul_short']);
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal_upload']);
    $tipe = isset($_POST['is_premium']) && $_POST['is_premium'] == '1' ? 'Premium' : 'Free';
    $video_path_lama = $_POST['video_path_lama'];

    $video_path = $video_path_lama;

    // Cek jika ada file video baru yang diunggah
    if (isset($_FILES['video_short']) && $_FILES['video_short']['error'] == 0 && !empty($_FILES['video_short']['name'])) {
        $video_name = uniqid() . '-' . basename($_FILES["video_short"]["name"]);
        $target_file = $upload_dir . $video_name;

        if (move_uploaded_file($_FILES["video_short"]["tmp_name"], $target_file)) {
            // Hapus file video lama jika berhasil unggah yang baru
            if (!empty($video_path_lama) && file_exists('../' . $video_path_lama)) {
                unlink('../' . $video_path_lama);
            }
            $video_path = 'uploads/shorts/' . $video_name;
        } else {
            $_SESSION['gagal'] = "Gagal mengunggah file video baru.";
            header("Location: index.php?hal=short");
            exit;
        }
    }

    $query = "UPDATE shorts SET judul='$judul', tanggal_upload='$tanggal', video_path='$video_path', tipe='$tipe' WHERE id=$id";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['sukses'] = "Short berhasil diperbarui.";
    } else {
        $_SESSION['gagal'] = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }

} elseif ($aksi == 'hapus') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id > 0) {
        // Ambil path video untuk dihapus filenya
        $result = mysqli_query($koneksi, "SELECT video_path FROM shorts WHERE id=$id");
        if ($row = mysqli_fetch_assoc($result)) {
            $video_path_to_delete = '../' . $row['video_path'];

            // Hapus data dari database
            $query_delete = "DELETE FROM shorts WHERE id=$id";
            if (mysqli_query($koneksi, $query_delete)) {
                // Jika berhasil hapus dari DB, hapus file fisiknya
                if (file_exists($video_path_to_delete)) {
                    unlink($video_path_to_delete);
                }
                $_SESSION['sukses'] = "Short berhasil dihapus."; // Tambahkan detail error
            } else {
                $_SESSION['gagal'] = "Gagal menghapus data dari database: " . mysqli_error($koneksi);
            }
        } else {
            $_SESSION['gagal'] = "Data short tidak ditemukan.";
        }
    } else {
        $_SESSION['gagal'] = "ID Short tidak valid.";
    }
} else {
    $_SESSION['gagal'] = "Aksi tidak dikenal.";
}

// Gunakan JavaScript untuk redirect karena header sudah dikirim oleh index.php
echo "<script>window.location.href = '{$redirect_url}';</script>";
exit;

?>