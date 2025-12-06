<?php
include '../config/koneksi.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$habit = null;
if ($id > 0) {
    $query = "SELECT * FROM habit_harian WHERE id = $id AND jenis = 'bacaan'";
    $result = mysqli_query($koneksi, $query);
    $habit = mysqli_fetch_assoc($result);
}
if (!$habit) {
    echo "<script>alert('Data habit bacaan tidak ditemukan.'); window.location.href='.?hal=habit';</script>";
    exit;
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Habit Harian</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#"><i class="icon-home"></i></a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=habit">Daftar Habit</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Edit Bacaan</a></li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Bacaan Harian</h4>
                </div>
                <div class="card-body">
                    <form action=".?hal=proses_habit&aksi=edit_bacaan" method="POST">
                        <input type="hidden" name="id" value="<?= $habit['id']; ?>">

                        <div class="form-group mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= htmlspecialchars($habit['tanggal']); ?>" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="subtest" class="form-label">Pilih Subtest</label>
                            <select class="form-select form-control" id="subtest_id" name="subtest_id" required>
                                <option value="" disabled selected>-- Pilih Subtest --</option>
                                <?php
                                $query_subtest = "SELECT * FROM subtest ORDER BY nama_subtest ASC";
                                $result_subtest = mysqli_query($koneksi, $query_subtest);
                                while ($row_subtest = mysqli_fetch_assoc($result_subtest)) {
                                    $selected = ($row_subtest['id'] == $habit['subtest_id']) ? 'selected' : '';
                                    echo "<option value='{$row_subtest['id']}' $selected>" . htmlspecialchars($row_subtest['nama_subtest']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="judul_bacaan" class="form-label">Judul Bacaan</label>
                            <input type="text" class="form-control" id="judul_bacaan" name="judul_bacaan"
                                placeholder="Contoh: Peran Energi Geotermal..." value="<?= htmlspecialchars($habit['judul']); ?>" required />
                        </div>

                        <div class="form-group mb-3" id="container_isi_materi">
                            <label for="editor">Isi Bacaan</label>
                            <textarea id="editor" name="isi_bacaan" class="form-control" rows="10"><?= htmlspecialchars($habit['isi_bacaan']); ?></textarea>
                            <small class="form-text text-muted">Gunakan editor ini untuk menulis isi bacaan dan
                                menyisipkan gambar.</small>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href=".?hal=habit" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
