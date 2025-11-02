<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Guru</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=daftarguru">Daftar Guru</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Edit Guru</a></li>
            </ul>
        </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Guru</h4>
                    </div>
                <div class="card-body">
                    <form action="" method="POST"
                        enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama"
                                name="nama"placeholder="Masukkan nama lengkap"
                                required />
                            </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email"
                                name="email" placeholder="Masukkan alamat email"
                                required />
                            </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password"
                                name="password"placeholder="Masukkan password"
                                required />
                            </div>

                        <div class="form-group">
                            <label for="no_telp">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp"
                                name="no_telp"
                                placeholder="Masukkan nomor telepon (opsional)" />
                            </div>

                        <div class="form-group">
                            <label for="foto">Foto Profil</label>
                            <input type="file" class="form-control" id="foto"
                                name="foto" accept="image/png, image/jpeg" />
                            <small class="form-text text-muted">Unggah foto profil guru
                                (format: .jpg, .png). Kosongkan jika tidak ada.</small>
                            </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href=".?hal=daftarguru" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>
