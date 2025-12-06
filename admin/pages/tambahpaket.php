<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Paket Langganan</h3>
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
                <a href=".?hal=paket">Daftar Paket</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href=".?hal=tambahpaket">Tambah Paket</a>
            </li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Paket</h4>
                </div>
                <div class="card-body">
                    <form action="?hal=proses_simpan_paket" method="POST">

                        <div class="form-group">
                            <label for="nama_paket">Nama Paket</label>
                            <input type="text" class="form-control" id="nama_paket" name="nama_paket"
                                placeholder="Contoh: Paket Premium" required />
                        </div>

                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga"
                                placeholder="Contoh: 199000" required />
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_unggulan"
                                    name="is_unggulan" value="1">
                                <label class="form-check-label" for="is_unggulan">Jadikan Paket Unggulan</label>
                                <small class="form-text text-muted d-block">Aktifkan jika paket ini direkomendasikan,
                                    non-aktifkan untuk paket standar.</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Fitur yang Termasuk</label>
                            <?php
                            include '../config/koneksi.php';
                            $query_fitur = mysqli_query($koneksi, "SELECT * FROM fitur ORDER BY urutan ASC");
                            while ($fitur = mysqli_fetch_assoc($query_fitur)) {
                            ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fitur[]" value="<?= $fitur['id']; ?>" id="fitur_<?= $fitur['id']; ?>">
                                    <label class="form-check-label" for="fitur_<?= $fitur['id']; ?>">
                                        <?= htmlspecialchars($fitur['nama_fitur']); ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href=".?hal=paket" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
