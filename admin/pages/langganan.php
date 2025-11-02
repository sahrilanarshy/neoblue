<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Paket Langganan</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#"><i class="icon-home"></i></a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Manajemen Langganan</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cari Pengguna</h4>
                </div>
                <div class="card-body">
                    <form id="search-form" action="#" method="GET">
                        <div class="form-group">
                            <label for="search_user">Masukkan Email atau Nama Pengguna</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search_user" name="search_user"required />
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </div>
                            <small class="form-text text-muted">Sistem akan mencari pengguna berdasarkan input di atas.</small>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12" id="manajemen-container" style="display: none;">
            <div class="card" id="manajemen-langganan-card">
                <div class="card-header">
                    <h4 class="card-title">Atur Langganan Pengguna</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Nama Pengguna:</strong>
                            <p id="display_nama">Sahril Sidik</p> 
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p id="display_email">sahrilwfc@gmail.com</p>
                        </div>
                    </div>
                    <hr>
                    <form action="proses_update_langganan.php" method="POST">
                        <input type="hidden" name="user_id" value="1">
                        <div class="form-group">
                            <label>Ubah Tipe Langganan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_langganan" id="status_free" value="free">
                                <label class="form-check-label" for="status_free">Free</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_langganan" id="status_premium" value="premium" checked>
                                <label class="form-check-label" for="status_premium">Premium</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="masa_aktif">Masa Aktif Langganan (Sampai Tanggal)</label>
                            <input type="date" class="form-control" id="masa_aktif" name="masa_aktif" value="2026-10-10">
                            <small class="form-text text-muted">Hanya diisi jika statusnya "Premium". Kosongkan jika statusnya "Free".</small>
                        </div>
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href=".?hal=langganan" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const searchForm = document.getElementById('search-form');
    const manajemenContainer = document.getElementById('manajemen-container');
    searchForm.addEventListener('submit', function(event) {
        event.preventDefault();
        manajemenContainer.style.display = 'block';
    });
</script>