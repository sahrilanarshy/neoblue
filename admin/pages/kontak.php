<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>NeoBlue | Manajemen Pesan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Manajemen Pesan</h3>
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
                <a href="#">Kontak</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Daftar Pesan Masuk</a>
            </li>
        </ul>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Pesan Masuk</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pengirim</th>
                                <th>Email</th>
                                <th>Subjek</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pengirim</th>
                                <th>Email</th>
                                <th>Subjek</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>26/10/2025</td>
                                <td>Budi Santoso</td>
                                <td>budi.s@example.com</td>
                                <td>Pertanyaan tentang paket</td>
                                <td>
                                    <span class="btn btn-primary btn-round btn-xs btn-danger">
                                        Baru
                                    </span>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=lihatpesan" data-bs-toggle="tooltip" title="Lihat Pesan"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href=".?hal=hapuspesan" data-bs-toggle="tooltip" title="Hapus"
                                            class="btn btn-link btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>25/10/2025</td>
                                <td>Siti Aminah</td>
                                <td>siti@example.com</td>
                                <td>Laporan Bug di Halaman Materi</td>
                                <td>
                                    <span class="btn btn-primary btn-round btn-xs btn-info">
                                        Sudah Dibaca
                                    </span>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=lihatpesan" data-bs-toggle="tooltip" title="Lihat Pesan"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href=".?hal=hapuspesan" data-bs-toggle="tooltip" title="Hapus"
                                            class="btn btn-link btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>24/10/2025</td>
                                <td>Joko Widodo</td>
                                <td>joko.w@example.com</td>
                                <td>Kerjasama Partnership</td>
                                <td>
                                    <span class="btn btn-primary btn-round btn-xs btn-success">
                                        Sudah Dibalas
                                    </span>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=lihatpesan" data-bs-toggle="tooltip" title="Lihat Pesan"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href=".?hal=hapuspesan" data-bs-toggle="tooltip" title="Hapus"
                                            class="btn btn-link btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>