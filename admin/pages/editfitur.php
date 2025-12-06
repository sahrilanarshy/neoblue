<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Fitur Langganan</h3>
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
                <a href=".?hal=fitur">Daftar Fitur</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Edit Fitur</a>
            </li>
        </ul>
    </div>

    <?php
    include '../config/koneksi.php';
    $id = $_GET['id'] ?? 0;
    if ($id == 0) {
        echo "<div class='alert alert-danger'>ID Fitur tidak valid.</div>";
    } else {
        $query = mysqli_query($koneksi, "SELECT * FROM fitur WHERE id = '$id'");
        $data = mysqli_fetch_assoc($query);
        if (!$data) {
            echo "<div class='alert alert-danger'>Fitur tidak ditemukan.</div>";
        } else {
    ?>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Fitur</h4>
                </div>
                <div class="card-body">
                    <form action="?hal=proses_update_fitur" method="POST">
                        <input type="hidden" name="id" value="<?= $data['id']; ?>">

                        <div class="form-group">
                            <label for="nama_fitur">Nama Fitur</label>
                            <input type="text" class="form-control" id="nama_fitur" name="nama_fitur"
                                value="<?= htmlspecialchars($data['nama_fitur']); ?>" required />
                        </div>

                        <div class="form-group">
                            <label for="urutan">Nomor Urut</label>
                            <input type="number" class="form-control" id="urutan" name="urutan" value="<?= htmlspecialchars($data['urutan']); ?>"
                                required />
                            <small class="form-text text-muted">Digunakan untuk mengurutkan tampilan fitur.</small>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href=".?hal=fitur" class="btn btn-secondary">Batal</a>
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
