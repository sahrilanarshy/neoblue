<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../config/koneksi.php';

    $nama_metode = mysqli_real_escape_string($koneksi, $_POST['nama_metode']);
    $nomor_rekening = mysqli_real_escape_string($koneksi, $_POST['nomor_rekening']);
    $atas_nama = mysqli_real_escape_string($koneksi, $_POST['atas_nama']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    $query = "INSERT INTO metode_pembayaran (nama_metode, nomor_rekening, atas_nama, status) VALUES ('$nama_metode', '$nomor_rekening', '$atas_nama', '$status')";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['sukses'] = "Metode pembayaran berhasil ditambahkan.";
        header("Location: ./?hal=metodepembayaran");
        exit();
    } else {
        $_SESSION['gagal'] = "Gagal menambahkan metode pembayaran: " . mysqli_error($koneksi);
        header("Location: ./?hal=tambahmetodepembayaran");
        exit();
    }
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
                <a href="#">Tambah Metode Pembayaran</a>
            </li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Metode Pembayaran</h4>
                </div>

                <div class="card-body">
                    <form action="?hal=tambahmetodepembayaran" method="POST">
                        <div class="form-group mb-3">
                            <label for="nama_metode">Nama Metode Pembayaran</label>
                            <input type="text" class="form-control" id="nama_metode" name="nama_metode" placeholder="Contoh: BCA Transfer / QRIS / Gopay" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="nomor_rekening">Nomor Rekening / ID Pembayaran</label>
                            <input type="text" class="form-control" id="nomor_rekening" name="nomor_rekening" placeholder="Masukkan nomor rekening atau ID pembayaran" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="atas_nama">Atas Nama</label>
                            <input type="text" class="form-control" id="atas_nama" name="atas_nama" placeholder="Masukkan nama pemilik akun" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            <a href=".?hal=metodepembayaran" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>