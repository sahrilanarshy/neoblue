<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Paket Langganan</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href=".?hal=beranda">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href=".?hal=paket">Daftar Paket</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href=".?hal=editpaket">Edit Paket</a>
            </li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Paket</h4>
                </div>
                <div class="card-body">
                    <form action="proses_simpan_paket.php" method="POST">

                        <div class="form-group">
                            <label for="nama_paket">Nama Paket</label>
                            <input type="text" class="form-control" id="nama_paket" name="nama_paket"
                                placeholder="Contoh: Paket Premium" required />
                        </div>

                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga"
                                placeholder="Contoh: 199000" required />
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_unggulan"
                                    name="is_unggulan" value="1">
                                <label class="form-check-label" for="is_unggulan">Jadikan Paket Unggulan</label>
                                <small class="form-text text-muted d-block">Aktifkan jika paket ini direkomendasikan,
                                    non-aktifkan untuk paket standar.</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Fitur yang Termasuk</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fitur[]" value="1"
                                    id="f1">
                                <label class="form-check-label" for="f1">Akses Video Materi (7 Subtest)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fitur[]" value="2"
                                    id="f2">
                                <label class="form-check-label" for="f2">Akses Liveclass & Rekaman</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fitur[]" value="3"
                                    id="f3">
                                <label class="form-check-label" for="f3">Akses Smart Scrolling (100+)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fitur[]" value="4"
                                    id="f4">
                                <label class="form-check-label" for="f4">Akses Habit Harian (1000+ Soal)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fitur[]" value="5"
                                    id="f5">
                                <label class="form-check-label" for="f5">Akses Tryout Rutin (20x)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fitur[]" value="6"
                                    id="f6">
                                <label class="form-check-label" for="f6">Akses Pembahasan Tryout (20x)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fitur[]" value="7"
                                    id="f7">
                                <label class="form-check-label" for="f7">Akses Tracker Kemajuan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fitur[]" value="8"
                                    id="f8">
                                <label class="form-check-label" for="f8">Grup Komunitas (Jalur Langit &
                                    Discord)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fitur[]" value="9"
                                    id="f9">
                                <label class="form-check-label" for="f9">Akses Mentoring Persiapan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fitur[]" value="10"
                                    id="f10">
                                <label class="form-check-label" for="f10">Akses Sampai Mei 2026</label>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href=".?hal=paket" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
