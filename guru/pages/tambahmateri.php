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
                <a href=".?hal=tambahmateri">Tambah Materi</a>
            </li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Materi</h4>
                </div>
                <div class="card-body">
                    <form action=".?hal=proses_materi&aksi=tambah" method="POST">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required />
                        </div>
                        <div class="form-group">
                            <label for="judul">Judul Materi</label>
                            <input type="text" class="form-control" id="judul" name="judul"
                                placeholder="Contoh: Deret & Baris" required />
                        </div>
                        <div class="form-group">
                            <label for="link">Link Video (Opsional)</label>
                            <input type="url" class="form-control" id="link" name="link"
                                placeholder="Contoh: https://www.youtube.com/watch?v=xxxx" />
                        </div>
                        <div class="form-group">
                            <label for="subtest_id">Pilih Subtest</label>
                            <select class="form-select form-control" id="subtest_id" name="subtest_id" required>
                                <option value="" disabled selected>-- Pilih Subtest --</option>
                                <?php
                                include '../config/koneksi.php';
                                $query_subtest = 'SELECT * FROM subtest ORDER BY nama_subtest ASC';
                                $result_subtest = mysqli_query($koneksi, $query_subtest);
                                while ($row_subtest = mysqli_fetch_assoc($result_subtest)) {
                                    echo "<option value='{$row_subtest['id']}'>" . htmlspecialchars($row_subtest['nama_subtest']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tipe">Tipe Materi</label>
                            <select class="form-select form-control" id="tipe" name="tipe" required>
                                <option value="Free">Free</option>
                                <option value="Premium">Premium</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="editor">Deskripsi (Opsional)</label>
                            <textarea id="editor" name="deskripsi" class="form-control" rows="5"></textarea>
                            <small class="form-text text-muted">Gunakan editor ini untuk format teks dan menyisipkan
                                gambar jika perlu.</small>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Materi</button>
                            <a href=".?hal=materi" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
