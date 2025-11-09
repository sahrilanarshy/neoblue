<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Materi</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#"><i class="icon-home"></i></a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Daftar Materi</a></li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Materi</h4>
                    <button class="btn btn-primary btn-round ms-auto"
                        onclick="window.location.href='.?hal=tambahmateri';">
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
                                <th>Subtest</th>
                                <th>Materi</th>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Subtest</th>
                                <th>Materi</th>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Penalaran Umum</td>
                                <td>Operasi Bilangan</td>
                                <td>10/10/2025</td>
                                <td>
                                    <a class="btn btn-primary btn-round btn-xs btn-success">Free</a>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editmateri" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a href="#" data-bs-toggle="modal" data-bs-target="#hapusMateriModal"
                                            data-id="1" data-item-name="Operasi Bilangan" title="Hapus"
                                            class="btn btn-link btn-danger btn-sm">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Penalaran Umum</td>
                                <td>Logika Matematika</td>
                                <td>11/10/2025</td>
                                <td>
                                    <a class="btn btn-primary btn-round btn-xs btn-warning">Premium</a>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editmateri&id=2" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a href="#" data-bs-toggle="modal" data-bs-target="#hapusMateriModal"
                                            data-id="2" data-item-name="Logika Matematika" title="Hapus"
                                            class="btn btn-link btn-danger btn-sm">
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
<?php include 'hapusmateri.php'; ?>