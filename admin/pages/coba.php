<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>NeoBlue | Manajemen Langganan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                    <h4 class="card-title">Daftar Paket & Fitur</h4>

                    <div class="btn-group dropdown ms-auto">
                        <button type="button" class="btn btn-primary btn-round dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-plus"></i>
                            Tambah
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#form-paket">
                                    Paket Baru
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#form-fitur">
                                    Fitur Baru
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <h5>Paket Langganan</h5>
                <div class="table-responsive">
                    <table class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Paket</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
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
                                        <a href="#form-paket" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="tooltip" title="Hapus"
                                            class="btn btn-link btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus data ini?');">
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
                                        <a href="#form-paket" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="tooltip" title="Hapus"
                                            class="btn btn-link btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus data ini?');">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h5 class="mt-4">Fitur Langganan</h5>
                <div class="table-responsive">
                    <table class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Fitur</th>
                                <th>Urutan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Akses Video Materi (7 Subtest)</td>
                                <td>10</td>
                                <td>
                                    <div class="form-button-action">
                                        <a href="#form-fitur" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="tooltip" title="Hapus"
                                            class="btn btn-link btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus data ini?');">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                             <tr>
                                <td>2</td>
                                <td>Akses Tryout Rutin (20x)</td>
                                <td>20</td>
                                <td>
                                    <div class="form-button-action">
                                        <a href="#form-fitur" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="tooltip" title="Hapus"
                                            class="btn btn-link btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus data ini?');">
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
    
    <div class="col-md-12 mt-4" id="form-paket">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Paket</h4>
            </div>
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <label for="nama_paket">Nama Paket</label>
                        <input type="text" class="form-control" id="nama_paket" value="Paket Premium">
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" class="form-control" id="harga" value="199000">
                    </div>
                    <div class="form-group">
                         <label class="form-check-label"><input type="checkbox" class="form-check-input" checked> Jadikan Paket Unggulan</label>
                    </div>
                     <div class="form-group">
                        <label>Fitur yang Termasuk</label><br>
                        <label class="form-check-label d-block"><input type="checkbox" class="form-check-input" checked> Akses Video Materi</label>
                        <label class="form-check-label d-block"><input type="checkbox" class="form-check-input" checked> Akses Tryout Rutin</label>
                        <label class="form-check-label d-block"><input type="checkbox" class="form-check-input"> Akses Liveclass</label>
                    </div>
                    <div class="card-action">
                        <button class="btn btn-primary">Simpan</button>
                        <button class="btn btn-danger">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>