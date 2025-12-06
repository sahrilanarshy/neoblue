<?php
include '../config/koneksi.php';

$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $telepon = mysqli_real_escape_string($koneksi, $_POST['no_telp']);

    // Cek apakah email sudah ada (dan bukan milik user ini)
    $check_email = mysqli_query($koneksi, "SELECT id FROM users WHERE email = '$email' AND id != '$id'");
    if (mysqli_num_rows($check_email) > 0) {
        $_SESSION['gagal'] = "Email sudah digunakan oleh pengguna lain.";
        header("Location: ./?hal=edituser&id=$id");
        exit();
    }

    // Bangun query update
    $query = "UPDATE users SET nama = '$nama', email = '$email', telepon = '$telepon'";

    // Jika password diisi, update juga passwordnya
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query .= ", password = '$password'";
    }

    $query .= " WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['sukses'] = "Data siswa berhasil diperbarui.";
    } else {
        $_SESSION['gagal'] = "Gagal memperbarui data siswa: " . mysqli_error($koneksi);
    }
    header("Location: ./?hal=user");
    exit();
}

// Ambil data user untuk ditampilkan di form
if ($id > 0) {
    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id' AND role = 'siswa'");
    $data = mysqli_fetch_assoc($result);
    if (!$data) {
        $_SESSION['gagal'] = "Siswa tidak ditemukan.";
        header("Location: ./?hal=user");
        exit();
    }
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Siswa</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href=".?hal=beranda"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=user">Daftar Siswa</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Edit Siswa</a></li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Siswa</h4>
                </div>
                <div class="card-body">

                    <form action="?hal=edituser" method="POST">
                        <input type="hidden" name="id" value="<?= $data['id']; ?>">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                value="<?= htmlspecialchars($data['nama']); ?>" required />
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($data['email']); ?>" required />
                        </div>

                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Kosongkan jika tidak ingin diubah" />
                        </div>

                        <div class="form-group">
                            <label for="no_telp">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp"
                                value="<?= htmlspecialchars($data['telepon']); ?>" placeholder="Masukkan nomor telepon (opsional)" />
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