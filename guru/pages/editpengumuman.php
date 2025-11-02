<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Pengumuman</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#"><i class="icon-home"></i></a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=pengumuman">Pengumuman</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Edit Pengumuman</a></li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Edit Pengumuman</div>
                </div>
                <form action=".?hal=proses_tambah_pengumuman" method="POST">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="judul">Judul Pengumuman</label>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Contoh: Extended Promo Grand Launching" required>
                        </div>

                        <div class="form-group">
                            <label for="isi_pengumuman">Isi Pengumuman</label>
                            <textarea class="form-control" id="isi_pengumuman" name="isi_pengumuman" rows="5" placeholder="Masukkan isi lengkap pengumuman..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="link">Link (Opsional)</label>
                            <input type="url" class="form-control" id="link" name="link" placeholder="Contoh: https://neoblue.com/promo">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl_terbit">Tanggal Terbit</label>
                                    <input type="date" class="form-control" id="tgl_terbit" name="tgl_terbit"
                                        required>
                                    <small class="form-text text-muted">Pengumuman akan mulai tampil pada tanggal
                                        ini.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="Published">Published (Tampilkan)</option>
                                        <option value="Draft">Draft (Simpan saja)</option>
                                    </select>
                                </div>
                            </div>

                    </div>
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href=".?hal=pengumuman" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
