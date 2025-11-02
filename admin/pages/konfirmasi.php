<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>NeoBlue | Konfirmasi Pembayaran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>

<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Pembayaran</h3>
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
                <a href="#">Konfirmasi Pembayaran</a>
            </li>
        </ul>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Pembayaran Menunggu Verifikasi</h4>
                    <button class="btn btn-primary btn-round ms-auto" onclick="window.location.href='.?hal=riwayatpembayaran';">
                        <i class="fa fa-list"></i> Lihat Semua
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Paket</th>
                                <th>Metode</th>
                                <th>Tanggal</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Rafi Pratama</td>
                                <td>Premium 1 Bulan</td>
                                <td>Transfer BRI</td>
                                <td>28 Okt 2025</td>
                                <td>
                                    <a href="uploads/bukti1.jpg" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                </td>
                                <td><span class="badge bg-warning">Menunggu</span></td>
                                <td>
                                    <div class="form-button-action">
                                        <a href="#" class="btn btn-link btn-success" title="Terima">
                                            <i class="fa fa-check"></i>
                                        </a>
                                        <a href="#" class="btn btn-link btn-danger" title="Tolak">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Siti Rahma</td>
                                <td>Premium 3 Bulan</td>
                                <td>QRIS</td>
                                <td>27 Okt 2025</td>
                                <td>
                                    <a href="uploads/bukti2.jpg" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                </td>
                                <td><span class="badge bg-warning">Menunggu</span></td>
                                <td>
                                    <div class="form-button-action">
                                        <a href="#" class="btn btn-link btn-success" title="Terima">
                                            <i class="fa fa-check"></i>
                                        </a>
                                        <a href="#" class="btn btn-link btn-danger" title="Tolak">
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
