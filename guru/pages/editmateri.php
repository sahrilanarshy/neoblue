<?php
include '../config/koneksi.php';
$id_materi = isset($_GET['id']) ? intval($_GET['id']) : 0;
$materi = null;
if ($id_materi > 0) {
    $query = "SELECT * FROM materi WHERE id = $id_materi";
    $result = mysqli_query($koneksi, $query);
    $materi = mysqli_fetch_assoc($result);
}
if (!$materi) {
    echo "<script>alert('Data materi tidak ditemukan.'); window.location.href='.?hal=materi';</script>";
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
                <a href=".?hal=materi">Materi</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Edit Materi</a>
            </li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Materi</h4>
                </div>
                <div class="card-body">
                    <form action=".?hal=proses_materi&aksi=edit" method="POST">
                        <input type="hidden" name="id" value="<?= $materi['id']; ?>">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= htmlspecialchars($materi['tanggal']); ?>" required />
                        </div>
                        <div class="form-group">
                            <label for="judul">Judul Materi</label>
                            <input type="text" class="form-control" id="judul" name="judul" value="<?= htmlspecialchars($materi['judul']); ?>" placeholder="Contoh: Deret & Baris" required />
                        </div>
                        <div class="form-group">
                            <label for="link">Link Video (Opsional)</label>
                            <input type="url" class="form-control" id="link" name="link"
                                placeholder="Contoh: https://www.youtube.com/watch?v=xxxx"
                                value="<?= htmlspecialchars($materi['link'] ?? ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="subtest_id">Pilih Subtest</label>
                            <select class="form-select form-control" id="subtest_id" name="subtest_id" required>
                                <option value="" disabled selected>-- Pilih Subtest --</option>
                                <?php
                                $query_subtest = "SELECT * FROM subtest ORDER BY nama_subtest ASC";
                                $result_subtest = mysqli_query($koneksi, $query_subtest);
                                while ($row_subtest = mysqli_fetch_assoc($result_subtest)) {
                                    $selected = ($row_subtest['id'] == $materi['subtest_id']) ? 'selected' : '';
                                    echo "<option value='{$row_subtest['id']}' $selected>" . htmlspecialchars($row_subtest['nama_subtest']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tipe">Tipe Materi</label>
                            <select class="form-select form-control" id="tipe" name="tipe" required>
                                <option value="Free" <?= ($materi['tipe'] == 'Free') ? 'selected' : ''; ?>>Free</option>
                                <option value="Premium" <?= ($materi['tipe'] == 'Premium') ? 'selected' : ''; ?>>Premium</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi (Opsional)</label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($materi['deskripsi']); ?></textarea>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href=".?hal=materi" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>