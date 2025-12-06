<?php
include '../config/koneksi.php';
$id_subtest = isset($_GET['id']) ? intval($_GET['id']) : 0;
$subtest = null;
if ($id_subtest > 0) {
    $query = "SELECT * FROM subtest WHERE id = $id_subtest";
    $result = mysqli_query($koneksi, $query);
    $subtest = mysqli_fetch_assoc($result);
}
if (!$subtest) {
    echo "<script>alert('Data subtest tidak ditemukan.'); window.location.href='.?hal=subtest';</script>";
    exit;
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Materi</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href=".?hal=subtest">Daftar Subtest</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href=".?hal=tambahmateri">Edit Subtest</a>
            </li>
        </ul>
    </div>
    <div class="row mb-5"><!-- mb-5 untuk jarak ke footer -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Subtest</h4>
                </div>
                <div class="card-body">
                    <form action=".?hal=proses_subtest&aksi=edit" method="POST">
                        <input type="hidden" name="id" value="<?= $subtest['id']; ?>">
                        <div class="form-group mb-3">
                            <label for="nama_subtest">Nama Subtest</label>
                            <input type="text" name="nama_subtest" id="nama_subtest" class="form-control" value="<?= htmlspecialchars($subtest['nama_subtest']); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="singkatan">Singkatan</label>
                            <input type="text" name="singkatan" id="singkatan" class="form-control" value="<?= htmlspecialchars($subtest['singkatan']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href=".?hal=subtest" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
