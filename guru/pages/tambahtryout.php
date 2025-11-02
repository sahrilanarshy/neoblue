<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Tryout</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#"><i class="icon-home"></i></a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=tryout">Tryout</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Tambah Tryout</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Tambah Tryout</div>
                </div>
                <form action=".?hal=proses_tambah_tryout" method="POST">
                    <div class="card-body">
                                <div class="form-group">
                                    <label for="subtest">Pilih Tryout</label>
                                    <select class="form-select form-control" id="subtest" name="subtest" required>
                                        <option value="" disabled selected>-- Pilih Tryout --</option>
                                        <option value="1">Tryout UTBK - SNBT #1</option>
                                        <option value="2">Tryout UTBK - SNBT #2</option>
                                        <option value="3">Tryout UTBK - SNBT #3</option>
                                        <option value="4">Tryout UTBK - SNBT #4</option>
                                        <option value="5">Tryout UTBK - SNBT #5</option>
                                    </select>
                                </div>

                        <div class="form-group">
                            <label for="waktu">Waktu Pengerjaan (Menit)</label>
                            <input type="number" class="form-control" id="waktu" name="waktu"
                                placeholder="Contoh: 30" required>
                            <small class="form-text text-muted">Masukkan durasi tryout dalam satuan menit.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl_mulai">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl_selesai">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Status (Ketersediaan)</label>
                            <select class="form-control" id="status" name="status">
                                <option value="Mendatang">Mendatang</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                            <small class="form-text text-muted">Status berdasarkan tanggal.</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Tipe Akses (Free / Premium)</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_premium"
                                    name="is_premium" value="1">
                                <label class="form-check-label" for="is_premium">Jadikan Tryout ini Premium (Berbayar)</label>
                                <small class="form-text text-muted d-block">Non-aktifkan (matikan) jika tryout ini gratis (Free).</small>
                            </div>
                        </div>

                    </div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href=".?hal=tryout" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>