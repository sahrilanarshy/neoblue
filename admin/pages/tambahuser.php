<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../config/koneksi.php';

    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telepon = mysqli_real_escape_string($koneksi, $_POST['no_telp']);
    $role = 'siswa';
    $tipe_user = 'free';

    // Cek apakah email sudah ada
    $check_email = mysqli_query($koneksi, "SELECT email FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $_SESSION['gagal'] = "Email sudah terdaftar. Silakan gunakan email lain.";
    } else {
        $query = "INSERT INTO users (nama, email, password, telepon, role, tipe_user) VALUES ('$nama', '$email', '$password', '$telepon', '$role', '$tipe_user')";
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['sukses'] = "Siswa berhasil ditambahkan.";
            header("Location: ./?hal=user");
            exit();
        } else {
            $_SESSION['gagal'] = "Gagal menambahkan siswa: " . mysqli_error($koneksi);
        }
    }
    header("Location: ./?hal=tambahuser");
    exit();
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Siswa</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=user">Daftar Siswa</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Tambah Siswa</a></li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Siswa</h4>
                </div>
                <div class="card-body">

                    <form action="?hal=tambahuser" method="POST">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukkan nama lengkap" required />
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Masukkan alamat email" required />
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Masukkan password" required />
                        </div>

                        <div class="form-group">
                            <label for="no_telp">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp"
                                placeholder="Masukkan nomor telepon (opsional)" />
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href=".?hal=user" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>