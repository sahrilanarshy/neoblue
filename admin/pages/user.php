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
                <a href="#">Daftar Siswa</a>
            </li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Siswa</h4>
                    <button class="btn btn-primary btn-round ms-auto"
                        onclick="window.location.href='.?hal=tambahuser';">
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
                                <th>Nama</th>
                                <th>Email</th>
                                <th>aktif sampai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>aktif sampai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tr>
                            <td>1</td>
                            <td>Sahril Sidik</td>
                            <td>sahrilwfc@gmail.com</td>
                            <td>10/10/2026</td>
                            <td>
                                <a class="btn btn-primary btn-round btn-xs btn-success">Free</a>
                            </td>
                            <td>
                                <div class="form-button-action">
                                    <a href=".?hal=edituser" data-bs-toggle="tooltip" title="Edit"
                                        class="btn btn-link btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                        data-id="1" data-item-name="Operasi Bilangan" title="Hapus"
                                        class="btn btn-link btn-danger btn-sm">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Budi Santoso</td>
                            <td>budi.s@example.com</td>
                            <td>12/10/2026</td>
                            <td>
                                <a class="btn btn-primary btn-round btn-xs btn-warning">Premium</a>
                            </td>
                            <td>
                                <div class="form-button-action">
                                    <a href=".?hal=edituser&id=2" data-bs-toggle="tooltip" title="Edit"
                                        class="btn btn-link btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                        data-id="2" data-item-name="Budi Santoso" title="Hapus"
                                        class="btn btn-link btn-danger btn-sm">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <td>Siti Aminah</td>
                            <td>siti.aminah@example.com</td>
                            <td>15/10/2026</td>
                            <td>
                                <a class="btn btn-primary btn-round btn-xs btn-success">Free</a>
                            </td>
                            <td>
                                <div class="form-button-action">
                                    <a href=".?hal=edituser&id=3" data-bs-toggle="tooltip" title="Edit"
                                        class="btn btn-link btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                        data-id="3" data-item-name="Siti Aminah" title="Hapus"
                                        class="btn btn-link btn-danger btn-sm">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>4</td>
                            <td>Agus Setiawan</td>
                            <td>agus.setiawan@example.com</td>
                            <td>18/11/2026</td>
                            <td>
                                <a class="btn btn-primary btn-round btn-xs btn-warning">Premium</a>
                            </td>
                            <td>
                                <div class="form-button-action">
                                    <a href=".?hal=edituser&id=4" data-bs-toggle="tooltip" title="Edit"
                                        class="btn btn-link btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                        data-id="4" data-item-name="Agus Setiawan" title="Hapus"
                                        class="btn btn-link btn-danger btn-sm">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>5</td>
                            <td>Dewi Lestari</td>
                            <td>dewi.l@example.com</td>
                            <td>21/11/2026</td>
                            <td>
                                <a class="btn btn-primary btn-round btn-xs btn-success">Free</a>
                            </td>
                            <td>
                                <div class="form-button-action">
                                    <a href=".?hal=edituser&id=5" data-bs-toggle="tooltip" title="Edit"
                                        class="btn btn-link btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                        data-id="5" data-item-name="Dewi Lestari" title="Hapus"
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