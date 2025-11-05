<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Artikel</h3>
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
                <a href="#">Daftar Artikel</a>
            </li>
        </ul>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Artikel</h4>
                    <button class="btn btn-primary btn-round ms-auto"
                        onclick="window.location.href='.?hal=tambahartikel';">
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
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>5 Strategi Belajar Efektif untuk Lolos UTBK</td>
                                <td>Tips Belajar</td>
                                <td>25 Oktober 2025</td>
                                <td>
                                    <img src="../assets/landingpage/img/artikel/artikel3.jpg" alt="Gambar Artikel"
                                        width="80" class="rounded">
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editartikel" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                            data-id="1" data-item-name="Artikel"
                                            class="btn btn-link btn-danger btn-sm" title="Hapus">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>Jurusan Paling Diminati di UTBK 2025</td>
                                <td>Informasi Kampus</td>
                                <td>27 Oktober 2025</td>
                                <td>
                                    <img src="../assets/landingpage/img/artikel/artikel2.jpg" alt="Gambar Artikel"
                                        width="80" class="rounded">
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editartikel" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                            data-id="2" data-item-name="Artikel"
                                            class="btn btn-link btn-danger btn-sm" title="Hapus">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>Tetap Termotivasi di Tengah Padatnya Persiapan</td>
                                <td>Motivasi</td>
                                <td>28 Oktober 2025</td>
                                <td>
                                    <img src="../assets/landingpage/img/artikel/artikel1.jpg" alt="Gambar Artikel"
                                        width="80" class="rounded">
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editartikel" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                            data-id="3" data-item-name="Artikel"
                                            class="btn btn-link btn-danger btn-sm" title="Hapus">
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
