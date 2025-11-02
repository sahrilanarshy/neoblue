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
            <li class="nav-item"><a href="#">Tambah Bacaan</a></li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Bacaan Harian</h4>
                </div>
                <div class="card-body">
                    <form action="proses_simpan_bacaan.php" method="POST">

                        <div class="form-group mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="subtest" class="form-label">Pilih Subtest</label>
                            <select class="form-select form-control" id="subtest" name="subtest" required>
                                <option value="" disabled selected>-- Pilih Subtest --</option>
                                <option value="1">Penalaran Umum</option>
                                <option value="2">Pemahaman Bacaan dan Menulis</option>
                                <option value="3">Pengetahuan dan Pemahaman Umum</option>
                                <option value="4">Literasi Bahasa Inggris</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="judul_bacaan" class="form-label">Judul Bacaan</label>
                            <input type="text" class="form-control" id="judul_bacaan" name="judul_bacaan"
                                placeholder="Contoh: Peran Energi Geotermal dalam Pembangunan Berkelanjutan" required />
                        </div>

                        <div class="form-group mb-3" id="container_isi_materi">
                            <label for="editor">Isi Bacaan</label>
                            <textarea id="editor" name="isi_materi" class="form-control" rows="10"></textarea>
                            <small class="form-text text-muted">Gunakan editor ini untuk menulis materi teks dan
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
