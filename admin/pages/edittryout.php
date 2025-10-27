<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>NeoBlue | Edit Tryout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Edit Tryout</h3>
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
                    <div class="card-title">Edit Tryout</div>
                </div>
                <form action=".?hal=proses_tambah_tryout" method="POST">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama_tryout">Nama Tryout</label>
                            <input type="text" class="form-control" id="nama_tryout" name="nama_tryout"
                                placeholder="Contoh: Tryout UTBK - SNBT #17" required>
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
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="Mendatang">Mendatang</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_premium"
                                    name="is_premium" value="1" checked>
                                <label class="form-check-label" for="is_premium">Jadikan short ini Premium</label>
                                <small class="form-text text-muted d-block">Aktifkan jika Tryout ini berbayar,
                                    non-aktifkan jika gratis.</small>
                            </div>
                        </div>

                    </div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <a href=".?hal=tryout" class="btn btn-danger">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>