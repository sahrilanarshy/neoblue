<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Pengumuman</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#"><i class="icon-home"></i></a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Daftar Pengumuman</a></li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Pengumuman</h4>
                    <button class="btn btn-primary btn-round ms-auto"
                        onclick="window.location.href='.?hal=tambahpengumuman';">
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
                                <th>Judul</th>
                                <th>Isi (Snippet)</th>
                                <th>Tanggal Terbit</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Isi (Snippet)</th>
                                <th>Tanggal Terbit</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>HTCommunity</td>
                                <td>Psst... semua pejuang SNBT udah nongkrong...</td>
                                <td>1/10/2025</td>
                                <td>
                                    <span class="btn btn-primary btn-round btn-xs btn-success">Published</span>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editpengumuman&id=1" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                            data-id="1" data-item-name="HTCommunity" title="Hapus"
                                            class="btn btn-link btn-danger">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Extended Promo Grand Launching...</td>
                                <td>Dapatkan harga spesial untuk berlangganan...</td>
                                <td>27/09/2025</td>
                                <td>
                                    <span class="btn btn-primary btn-round btn-xs btn-success">Published</span>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editpengumuman&id=2" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                            data-id="2" data-item-name="Extended Promo" title="Hapus"
                                            class="btn btn-link btn-danger">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Maintenance Terjadwal</td>
                                <td>Akan ada perbaikan sistem pada...</td>
                                <td>25/10/2025</td>
                                <td>
                                    <span class="btn btn-primary btn-round btn-xs btn-warning">Draft</span>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editpengumuman&id=3" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                            data-id="3" data-item-name="Maintenance Terjadwal" title="Hapus"
                                            class="btn btn-link btn-danger">
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