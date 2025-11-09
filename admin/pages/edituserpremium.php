<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Siswa</h3>
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
                <a href=".?hal=userpremium">Daftar Siswa</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Edit Siswa(Premium)</a>
            </li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Siswa</h4>
                </div>
                <div class="card-body">
                    <form action="proses_update_siswa.php" method="POST">
                        <input type="hidden" name="user_id" value="1">

                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                value="Sahril Sidik" required />
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="sahrilwfc@gmail.com" required />
                        </div>

                        <div class="form-group">
                            <label for="password">Password Baru (Opsional)</label>
                            <input type="password" class="form-control" id="password" name="password" />
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label>Status Langganan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_langganan" id="status_free"
                                    value="free">
                                <label class="form-check-label" for="status_free">
                                    Free
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_langganan" id="status_premium"
                                    value="premium" checked>
                                <label class="form-check-label" for="status_premium">
                                    Premium
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="masa_aktif">Masa Aktif (sampai tanggal)</label>
                            <input type="date" class="form-control" id="masa_aktif" name="masa_aktif" value="2026-10-10" />
                            <small class="form-text text-muted">Hanya diisi jika statusnya "Premium".</small>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href=".?hal=daftarsiswa" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>