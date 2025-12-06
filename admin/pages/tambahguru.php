<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../config/koneksi.php';

    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telepon = mysqli_real_escape_string($koneksi, $_POST['no_telp']);
    $role = 'guru';
    $foto_path = '';

    // Cek apakah email sudah ada
    $check_email = mysqli_query($koneksi, "SELECT email FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $_SESSION['gagal'] = "Email sudah terdaftar. Silakan gunakan email lain.";
        header("Location: ./?hal=tambahguru");
        exit();
    }

    // Proses upload foto jika ada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "uploads/guru/";
        if (!file_exists('../' . $target_dir)) {
            mkdir('../' . $target_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES["foto"]["name"]);
        $target_file = '../' . $target_dir . $file_name;
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $foto_path = $target_dir . $file_name;
        } else {
            $_SESSION['gagal'] = "Gagal mengunggah foto.";
            header("Location: ./?hal=tambahguru");
            exit();
        }
    }

    $query = "INSERT INTO users (nama, email, password, telepon, role, foto_profil) VALUES ('$nama', '$email', '$password', '$telepon', '$role', '$foto_path')";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['sukses'] = "Guru berhasil ditambahkan.";
        header("Location: ./?hal=guru");
        exit();
    } else {
        $_SESSION['gagal'] = "Gagal menambahkan guru: " . mysqli_error($koneksi);
        // Hapus foto yang sudah terupload jika query gagal
        if (!empty($foto_path) && file_exists('../' . $foto_path)) {
            unlink('../' . $foto_path);
        }
        header("Location: ./?hal=tambahguru");
        exit();
    }
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Guru</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=guru">Daftar Guru</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Tambah Guru</a></li>
            </ul>
        </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Guru</h4>
                    </div>
                <div class="card-body"> <form action="?hal=tambahguru" method="POST"
                        enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama"
                                name="nama"placeholder="Masukkan nama lengkap"
                                required />
                            </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email"
                                name="email" placeholder="Masukkan alamat email"
                                required />
                            </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password"
                                name="password"placeholder="Masukkan password"
                                required />
                            </div>

                        <div class="form-group">
                            <label for="no_telp">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp"
                                name="no_telp"
                                placeholder="Masukkan nomor telepon (opsional)" />
                            </div>

                        <div class="form-group">
                            <label for="foto">Foto Profil</label>
                            <input type="file" class="form-control" id="foto"
                                name="foto" accept="image/png, image/jpeg" />
                            <small class="form-text text-muted">Unggah foto profil guru
                                (format: .jpg, .png). Kosongkan jika tidak ada.</small>
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
