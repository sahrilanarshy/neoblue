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
                <a href="#">Daftar Metode Pembayaran</a>
            </li>
        </ul>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Metode Pembayaran</h4>
                    <button class="btn btn-primary btn-round ms-auto"
                        onclick="window.location.href='.?hal=tambahmetodepembayaran';">
                        <i class="fa fa-plus"></i>
                        Tambah
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Metode</th>
                                <th>Nomor Rekening / ID</th>
                                <th>Atas Nama</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Metode</th>
                                <th>Nomor Rekening / ID</th>
                                <th>Atas Nama</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr class="align-middle">
                                <td>1</td>
                                <td>BCA</td>
                                <td>1234567890</td>
                                <td>Sahril Sidik</td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td class="text-center">
                                    <div class="form-button-action">
                                        <a href=".?hal=editmetodepembayaran" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                            data-id="1" data-item-name="BCA Transfer" title="Hapus"
                                            class="btn btn-link btn-danger btn-sm">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr class="align-middle">
                                <td>2</td>
                                <td>BRI</td>
                                <td>2944 2934 222</td>
                                <td>Sahril Sidik</td>
                                <td><span class="badge bg-secondary">Nonaktif</span></td>
                                <td class="text-center">
                                    <div class="form-button-action">
                                        <a href=".?hal=editmetodepembayaran" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                            data-id="2" data-item-name="QRIS" title="Hapus"
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
