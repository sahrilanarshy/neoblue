<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Metode Pembayaran</h3>
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
                <a href=".?hal=metodepembayaran">Daftar Metode Pembayaran</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Edit Metode Pembayaran</a>
            </li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Metode Pembayaran</h4>
                </div>

                <div class="card-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="nama_metode">Nama Metode Pembayaran</label>
                            <input type="text" class="form-control" id="nama_metode" placeholder="Contoh: BCA Transfer / QRIS / Gopay" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="nomor_rekening">Nomor Rekening / ID Pembayaran</label>
                            <input type="text" class="form-control" id="nomor_rekening" placeholder="Masukkan nomor rekening atau ID pembayaran" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="atas_nama">Atas Nama</label>
                            <input type="text" class="form-control" id="atas_nama" placeholder="Masukkan nama pemilik akun" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-select" id="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href=".?hal=metodepembayaran" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
