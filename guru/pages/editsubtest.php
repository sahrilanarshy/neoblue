<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>NeoBlue | Materi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
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
                <a href=".?hal=materi">Daftar Subtest</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href=".?hal=tambahmateri">Edit Subtest</a>
            </li>
        </ul>
    </div>
    <div class="row mb-5"><!-- mb-5 untuk jarak ke footer -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Subtest</h4>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group mb-3">
                            <label for="nim">Subtest</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nim">Singkatan</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href=".?hal=mahasiswa" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
