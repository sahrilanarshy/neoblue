<?php
include '../config/koneksi.php';

$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $telepon = mysqli_real_escape_string($koneksi, $_POST['no_telp']);
    $foto_lama = $_POST['foto_lama'];
    $foto_path = $foto_lama;

    // Cek apakah email sudah ada (dan bukan milik user ini)
    $check_email = mysqli_query($koneksi, "SELECT id FROM users WHERE email = '$email' AND id != '$id'");
    if (mysqli_num_rows($check_email) > 0) {
        $_SESSION['gagal'] = "Email sudah digunakan oleh pengguna lain.";
        header("Location: ./?hal=editguru&id=$id");
        exit();
    }

    // Proses upload foto baru jika ada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "uploads/guru/";
        if (!file_exists('../' . $target_dir)) {
            mkdir('../' . $target_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES["foto"]["name"]);
        $target_file = '../' . $target_dir . $file_name;
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            // Hapus foto lama jika ada
            if (!empty($foto_lama) && file_exists('../' . $foto_lama)) {
                unlink('../' . $foto_lama);
            }
            $foto_path = $target_dir . $file_name;
        }
    }

    // Bangun query update
    $query = "UPDATE users SET nama = '$nama', email = '$email', telepon = '$telepon', foto_profil = '$foto_path'";

    // Jika password diisi, update juga passwordnya
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query .= ", password = '$password'";
    }

    $query .= " WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['sukses'] = "Data guru berhasil diperbarui.";
    } else {
        $_SESSION['gagal'] = "Gagal memperbarui data guru: " . mysqli_error($koneksi);
    }
    header("Location: ./?hal=guru");
    exit();
}

// Ambil data guru untuk ditampilkan di form
if ($id > 0) {
    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id' AND role = 'guru'");
    $data = mysqli_fetch_assoc($result);
    if (!$data) {
        $_SESSION['gagal'] = "Guru tidak ditemukan.";
        header("Location: ./?hal=guru");
        exit();
    }
} else {
    $_SESSION['gagal'] = "ID Guru tidak valid.";
    header("Location: ./?hal=guru");
    exit();
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Guru</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href=".?hal=beranda"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=guru">Daftar Guru</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Edit Guru</a></li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Guru</h4>
                </div>
                <div class="card-body">
                    <form action="?hal=editguru" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $data['id']; ?>">
                        <input type="hidden" name="foto_lama" value="<?= $data['foto_profil']; ?>">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama"
                                name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required />
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($data['email']); ?>" required />
                        </div>

                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" class="form-control" id="password"
                                name="password" placeholder="Kosongkan jika tidak ingin diubah" />
                        </div>

                        <div class="form-group">
                            <label for="no_telp">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp"
                                value="<?= htmlspecialchars($data['telepon']); ?>" placeholder="Masukkan nomor telepon (opsional)" />
                        </div>

                        <div class="form-group">
                            <label for="foto">Foto Profil</label>
                            <?php if (!empty($data['foto_profil'])) : ?>
                                <div class="mb-2">
                                    <img src="../<?= $data['foto_profil']; ?>" alt="Foto saat ini" height="80" class="rounded">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="foto" name="foto"
                                accept="image/png, image/jpeg" />
                            <small class="form-text text-muted">Unggah foto baru untuk mengganti. Kosongkan jika tidak ingin diubah.</small>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href=".?hal=guru" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
