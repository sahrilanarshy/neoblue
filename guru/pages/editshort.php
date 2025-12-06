<?php
include '../config/koneksi.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$short = null;
if ($id > 0) {
    $query = "SELECT * FROM shorts WHERE id = $id";
    $result = mysqli_query($koneksi, $query);
    $short = mysqli_fetch_assoc($result);
}
if (!$short) {
    echo "<script>alert('Data short tidak ditemukan.'); window.location.href='.?hal=short';</script>";
    exit;
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Short</h3>
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
                <a href=".?hal=short">Daftar Short</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Edit Short</a>
            </li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Short</h4>
                </div>
                <div class="card-body">
                    <form action=".?hal=proses_short&aksi=edit" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $short['id']; ?>">
                        <input type="hidden" name="video_path_lama" value="<?= htmlspecialchars($short['video_path']); ?>">

                        <div class="form-group">
                            <label for="tanggal_upload">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal_upload" name="tanggal_upload"
                                value="<?= htmlspecialchars($short['tanggal_upload']); ?>" required />
                        </div>

                        <div class="form-group">
                            <label for="judul_short">Judul Short</label>
                            <input type="text" class="form-control" id="judul_short" name="judul_short"
                                placeholder="Contoh: Trik Cepat Operasi Bilangan" value="<?= htmlspecialchars($short['judul']); ?>" required />
                        </div>

                        <div class="form-group">
                            <label for="video_short">Upload Video Baru (Opsional)</label>
                            <?php if (!empty($short['video_path'])): ?>
                                <p class="form-text text-muted">Video saat ini: <a href="../<?= htmlspecialchars($short['video_path']); ?>" target="_blank"><?= basename($short['video_path']); ?></a></p>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="video_short" name="video_short"
                                accept="video/*" />
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti video.</small>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_premium"
                                    name="is_premium" value="1" <?= $short['tipe'] == 'Premium' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_premium">Jadikan short ini Premium</label>
                                <small class="form-text text-muted d-block">Aktifkan jika short ini berbayar,
                                    non-aktifkan jika gratis.</small>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href=".?hal=short" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>