<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Fitur Langganan</h3>
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
                <a href=".?hal=fitur">Daftar Fitur</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Tambah Fitur</a>
            </li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Fitur Baru</h4>
                </div>
                <div class="card-body">
                    <form action="proses_simpan_fitur.php" method="POST">

                        <div class="form-group">
                            <label for="nama_fitur">Nama Fitur</label>
                            <input type="text" class="form-control" id="nama_fitur" name="nama_fitur"
                                placeholder="Contoh: Grup Diskusi Premium" required />
                        </div>

                        <div class="form-group">
                            <label for="urutan">Nomor Urut</label>
                            <input type="number" class="form-control" id="urutan" name="urutan"
                                placeholder="Contoh: 40 (semakin kecil, semakin atas)" required />
                            <small class="form-text text-muted">Digunakan untuk mengurutkan tampilan fitur.</small>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href=".?hal=fitur" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
