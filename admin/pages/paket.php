<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>NeoBlue | Paket Langganan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Paket Langganan</h3>
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
                <a href="#">Daftar Paket Langganan</a>
            </li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Paket Langganan</h4>
                        <button class="btn btn-primary btn-round ms-auto"
                            onclick="window.location.href='.?hal=tambahpaket';">
                            <i class="fa fa-plus"></i>
                            Tambah
                        </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Paket</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Nama Paket</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Paket Gratis</td>
                                <td>Rp 0</td>
                                <td>
                                    <span class="btn btn-primary btn-round btn-xs btn-secondary">
                                        Standar
                                    </span>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editpaket" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href=".?hal=hapuspaket" data-bs-toggle="tooltip" title="Hapus"
                                            class="btn btn-link btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Paket Premium</td>
                                <td>Rp 199.000</td>
                                <td>
                                    <span class="btn btn-primary btn-round btn-xs btn-success">
                                        Unggulan
                                    </span>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editpaket" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href=".?hal=hapuspaket" data-bs-toggle="tooltip" title="Hapus"
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