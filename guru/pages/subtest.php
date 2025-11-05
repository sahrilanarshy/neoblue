<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Materi</h3>
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
                <a href="#">Daftar Subtest</a>
            </li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Subtest</h4>
                    <button class="btn btn-primary btn-round ms-auto"
                        onclick="window.location.href='.?hal=tambahsubtest';">
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
                                <th>ID</th>
                                <th>Subtest</th>
                                <th>Singkatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Subtest</th>
                                <th>Singkatan</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Penalaran Umum</td>
                                <td>PU</td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editsubtest" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a href=".?hal=hapussubtest" data-bs-toggle="tooltip" title="Hapus"
                                            class="btn btn-link btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Pemahamaan Bacaan dan menulis</td>
                                <td>PBM</td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editsubtest" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a href=".?hal=hapusubtest" data-bs-toggle="tooltip" title="Hapus"
                                            class="btn btn-link btn-danger btn-sm"
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
