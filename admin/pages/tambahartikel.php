<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Artikel</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=artikel">Daftar Artikel</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Tambah Artikel</a></li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Artikel</h4>
                </div>

                <div class="card-body">

                    <?php
                    if (isset($error_message)) {
                        echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
                    }
                    ?>

                    <form action="proses_tambahartikel.php" method="POST" enctype="multipart/form-data">

                        <div class="form-group mb-3">
                            <label for="judul">Judul Artikel</label>
                            <input type="text" class="form-control" id="judul" name="judul"
                                placeholder="Masukkan judul artikel" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="kategori">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Tips Belajar">Tips Belajar</option>
                                <option value="Informasi Kampus">Informasi Kampus</option>
                                <option value="Motivasi">Motivasi</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tanggal">Tanggal Publikasi</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="gambar">Gambar Artikel</label>
                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required />
                            <small class="form-text text-muted d-block">Gunakan format JPG/PNG, ukuran maks. 2MB</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="konten">Isi Artikel</label>
                            <textarea class="form-control" id="konten" name="konten" rows="6"
                                placeholder="Tulis isi artikel di sini..." required></textarea>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href=".?hal=artikel" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Batal
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
