<?php
include '../config/koneksi.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pengumuman = null;
if ($id > 0) {
    $query = "SELECT * FROM pengumuman WHERE id = $id";
    $result = mysqli_query($koneksi, $query);
    $pengumuman = mysqli_fetch_assoc($result);
}
if (!$pengumuman) {
    echo "<script>alert('Data pengumuman tidak ditemukan.'); window.location.href='.?hal=pengumuman';</script>";
    exit;
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Pengumuman</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#"><i class="icon-home"></i></a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=pengumuman">Pengumuman</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Edit Pengumuman</a></li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Edit Pengumuman</div>
                </div>
                <form action=".?hal=proses_pengumuman&aksi=edit" method="POST">
                    <input type="hidden" name="id" value="<?= $pengumuman['id']; ?>">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="judul">Judul Pengumuman</label>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Contoh: Extended Promo Grand Launching" value="<?= htmlspecialchars($pengumuman['judul']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="isi_pengumuman">Isi Pengumuman</label>
                            <textarea class="form-control" id="isi_pengumuman" name="isi_pengumuman" rows="5" placeholder="Masukkan isi lengkap pengumuman..." required><?= htmlspecialchars($pengumuman['isi']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="link">Link (Opsional)</label>
                            <input type="url" class="form-control" id="link" name="link" placeholder="Contoh: https://neoblue.com/promo" value="<?= htmlspecialchars($pengumuman['link']); ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tgl_terbit">Tanggal Terbit</label>
                                    <input type="date" class="form-control" id="tgl_terbit" name="tgl_terbit" value="<?= htmlspecialchars($pengumuman['tanggal_terbit']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tgl_selesai">Tanggal Selesai (Opsional)</label>
                                    <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai" value="<?= htmlspecialchars($pengumuman['tanggal_selesai'] ?? ''); ?>">
                                    <small class="form-text text-muted">Biarkan kosong jika tidak ada batas waktu.</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="Published" <?= $pengumuman['status'] == 'Published' ? 'selected' : ''; ?>>Published (Tampilkan)</option>
                                        <option value="Draft" <?= $pengumuman['status'] == 'Draft' ? 'selected' : ''; ?>>Draft (Simpan saja)</option>
                                    </select>
                                </div>
                            </div>
                    </div>
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href=".?hal=pengumuman" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
