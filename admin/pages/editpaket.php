<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Paket Langganan</h3>
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
                <a href=".?hal=paket">Daftar Paket</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href=".?hal=editpaket">Edit Paket</a>
            </li>
        </ul>
    </div>

    <?php
    include '../config/koneksi.php';
    $id = $_GET['id'] ?? 0;
    if ($id == 0) {
        echo "<div class='alert alert-danger'>ID Paket tidak valid.</div>";
    } else {
        $query_paket = mysqli_query($koneksi, "SELECT * FROM paket WHERE id = '$id'");
        $data_paket = mysqli_fetch_assoc($query_paket);

        if (!$data_paket) {
            echo "<div class='alert alert-danger'>Paket tidak ditemukan.</div>";
        } else {
            // Ambil fitur yang sudah terhubung dengan paket ini
            $query_paket_fitur = mysqli_query($koneksi, "SELECT fitur_id FROM paket_fitur WHERE paket_id = '$id'");
            $fitur_terhubung = [];
            while ($pf = mysqli_fetch_assoc($query_paket_fitur)) {
                $fitur_terhubung[] = $pf['fitur_id'];
            }

            // Ambil semua fitur yang ada
            $query_semua_fitur = mysqli_query($koneksi, "SELECT * FROM fitur ORDER BY urutan ASC");
    ?>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Paket</h4>
                </div>
                <div class="card-body">
                    <form action="?hal=proses_update_paket" method="POST">
                        <input type="hidden" name="id" value="<?= $data_paket['id']; ?>">

                        <div class="form-group">
                            <label for="nama_paket">Nama Paket</label>
                            <input type="text" class="form-control" id="nama_paket" name="nama_paket"
                                value="<?= htmlspecialchars($data_paket['nama_paket']); ?>" required />
                        </div>

                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga"
                                value="<?= htmlspecialchars($data_paket['harga']); ?>" required />
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_unggulan"
                                    name="is_unggulan" value="1" <?= $data_paket['is_unggulan'] == 1 ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_unggulan">Jadikan Paket Unggulan</label>
                                <small class="form-text text-muted d-block">Aktifkan jika paket ini direkomendasikan,
                                    non-aktifkan untuk paket standar.</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Fitur yang Termasuk</label>
                            <?php while ($fitur = mysqli_fetch_assoc($query_semua_fitur)) : ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fitur[]" value="<?= $fitur['id']; ?>" id="fitur_<?= $fitur['id']; ?>"
                                        <?= in_array($fitur['id'], $fitur_terhubung) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="fitur_<?= $fitur['id']; ?>">
                                        <?= htmlspecialchars($fitur['nama_fitur']); ?>
                                    </label>
                                </div>
                            <?php endwhile; ?>
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
    <?php
        }
    } ?>
</div>
