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
                    <form action="proses_simpan_materi.php" method="POST">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required />
                        </div>
                        <div class="form-group">
                            <label for="judul_materi">Judul Materi</label>
                            <input type="text" class="form-control" id="judul_materi" name="judul_materi"
                                placeholder="Contoh: Deret & Baris" required />
                        </div>
                        <div class="form-group">
                            <label for="subtest">Pilih Subtest</label>
                            <select class="form-select form-control" id="subtest" name="subtest" required>
                                <option value="" disabled selected>-- Pilih Subtest --</option>
                                <option value="1">Penalaran Umum</option>
                                <option value="2">Pemahaman Bacaan dan Menulis</option>
                                <option value="3">Pengetahuan dan Pemahaman Umum</option>
                                <option value="4">Literasi Bahasa Inggris</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required />
                        </div>
                        <div class="form-group">
                            <label for="link_video">Link Video</label>
                            <input type="url" class="form-control" id="link_video" name="link_video"
                                placeholder="https://www.youtube.com/watch?v=..." />
                            <small class="form-text text-muted">Masukkan URL lengkap dari video materi.</small>
                        </div>
                        <div class="form-group" id="container_isi_materi">
                            <label for="editor">Isi Konten</label>
                            <textarea id="editor" name="isi_materi" class="form-control" rows="10"></textarea>
                            <small class="form-text text-muted">Gunakan editor ini untuk menulis materi teks dan
                                menyisipkan gambar.</small>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_premium"
                                    name="is_premium" value="1" checked>
                                <label class="form-check-label" for="is_premium">Jadikan short ini Premium</label>
                                <small class="form-text text-muted d-block">Aktifkan jika short ini berbayar,
                                    non-aktifkan jika gratis.</small>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href=".?hal=materi" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>