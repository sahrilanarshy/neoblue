<?php
include '../config/koneksi.php';
$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nama_metode = mysqli_real_escape_string($koneksi, $_POST['nama_metode']);
    $nomor_rekening = mysqli_real_escape_string($koneksi, $_POST['nomor_rekening']);
    $atas_nama = mysqli_real_escape_string($koneksi, $_POST['atas_nama']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    $query = "UPDATE metode_pembayaran SET nama_metode = '$nama_metode', nomor_rekening = '$nomor_rekening', atas_nama = '$atas_nama', status = '$status' WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['sukses'] = "Metode pembayaran berhasil diperbarui.";
    } else {
        $_SESSION['gagal'] = "Gagal memperbarui metode pembayaran: " . mysqli_error($koneksi);
    }
    header("Location: ./?hal=metodepembayaran");
    exit();
}

// Ambil data untuk ditampilkan di form
if ($id > 0) {
    $result = mysqli_query($koneksi, "SELECT * FROM metode_pembayaran WHERE id = '$id'");
    $data = mysqli_fetch_assoc($result);
    if (!$data) {
        $_SESSION['gagal'] = "Metode pembayaran tidak ditemukan.";
        header("Location: ./?hal=metodepembayaran");
        exit();
    }
} else {
    $_SESSION['gagal'] = "ID tidak valid.";
    header("Location: ./?hal=metodepembayaran");
    exit();
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Metode Pembayaran</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href=".?hal=beranda">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href=".?hal=metodepembayaran">Daftar Metode Pembayaran</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Edit Metode Pembayaran</a>
            </li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Metode Pembayaran</h4>
                </div>

                <div class="card-body">
                    <form action="?hal=editmetodepembayaran" method="POST">
                        <input type="hidden" name="id" value="<?= $data['id']; ?>">
                        <div class="form-group mb-3">
                            <label for="nama_metode">Nama Metode Pembayaran</label>
                            <input type="text" class="form-control" id="nama_metode" name="nama_metode" value="<?= htmlspecialchars($data['nama_metode']); ?>" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="nomor_rekening">Nomor Rekening / ID Pembayaran</label>
                            <input type="text" class="form-control" id="nomor_rekening" name="nomor_rekening" value="<?= htmlspecialchars($data['nomor_rekening']); ?>" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="atas_nama">Atas Nama</label>
                            <input type="text" class="form-control" id="atas_nama" name="atas_nama" value="<?= htmlspecialchars($data['atas_nama']); ?>" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Aktif" <?= $data['status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                                <option value="Nonaktif" <?= $data['status'] == 'Nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                            </select>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href=".?hal=metodepembayaran" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
