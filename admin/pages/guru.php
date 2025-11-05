<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Guru</h3>
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
                <a href="#">Daftar Guru</a>
            </li>
        </ul>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Guru</h4>
                    <button class="btn btn-primary btn-round ms-auto"
                        onclick="window.location.href='.?hal=tambahguru';">
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
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <img src="../assets/admin/img/logo/icon profile.png" alt="Foto Guru" width="45" height="45"
                                        class="rounded-circle border border-2 shadow-sm">
                                </td>
                                <td>Sahril Sidik</td>
                                <td>sahrilwfc@gmail.com</td>
                                <td>083119897273</td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editguru" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                            data-id="1" data-item-name="Sahril Sidik" title="Hapus"
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
