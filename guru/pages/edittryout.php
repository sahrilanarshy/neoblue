<?php
include '../config/koneksi.php';
$id_tryout = isset($_GET['id']) ? intval($_GET['id']) : 0;
$tryout = null;
if ($id_tryout > 0) {
    $result = mysqli_query($koneksi, "SELECT * FROM tryout WHERE id = $id_tryout");
    $tryout = mysqli_fetch_assoc($result);
}
if (!$tryout) {
    echo "<script>alert('Data tryout tidak ditemukan.'); window.location.href='.?hal=tryout';</script>";
    exit;
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Edit Tryout</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#"><i class="icon-home"></i></a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=tryout">Tryout</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Edit Tryout</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Edit Tryout</div>
                </div>
                <form action=".?hal=proses_tryout&aksi=edit" method="POST">
                    <input type="hidden" name="id" value="<?= $tryout['id']; ?>">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama_tryout">Nama Tryout</label>
                            <input type="text" class="form-control" id="nama_tryout" name="nama_tryout"
                                value="<?= htmlspecialchars($tryout['nama_tryout']); ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl_mulai">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" value="<?= htmlspecialchars($tryout['tanggal_mulai']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl_selesai">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai" value="<?= htmlspecialchars($tryout['tanggal_selesai']); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_premium"
                                    name="is_premium" value="1" <?= $tryout['tipe'] == 'Premium' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_premium">Jadikan short ini Premium</label>
                                <small class="form-text text-muted d-block">Aktifkan jika Tryout ini berbayar,
                                    non-aktifkan jika gratis.</small>
                            </div>
                        </div>

                    </div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href=".?hal=tryout" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>