<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>NeoBlue | Habit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Habit Harian</h3>
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
                <a href="#">Daftar Habit Harian</a>
            </li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Habit Harian</h4>

                    <div class="btn-group dropdown ms-auto">
                        <button type="button" class="btn btn-primary btn-round dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-plus"></i>
                            Tambah
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href=".?hal=tambahbacaan">
                                    <i class=""></i>Bacaan
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href=".?hal=tambahsoal">
                                    <i class=""></i>Soal
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Subtest</th>
                                <th>Judul</th>
                                <th>Jenis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Subtest</th>
                                <th>Judul</th>
                                <th>Jenis</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>11/10/2025</td>
                                <td>Penalaran Umum</td>
                                <td>Operasi Bilangan</td>
                                <td>
                                    <span class="btn btn-primary btn-round btn-xs btn-success">
                                        Bacaan
                                    </span>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editbacaan" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href=".?hal=hapusbacaan" data-bs-toggle="tooltip" title="Hapus"
                                            class="btn btn-link btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>10/10/2025</td>
                                <td>Bahasa Indonesia</td>
                                <td>Operasi Bilangan</td>
                                <td>
                                    <span class="btn btn-primary btn-round btn-xs btn-info">
                                        Soal
                                    </span>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editsoal" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href=".?hal=hapussoal" data-bs-toggle="tooltip" title="Hapus"
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
