<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Paket Langganan</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href=".?hal=beranda"><i class="icon-home"></i></a>
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
                    <form id="search-form">
                        <div class="form-group">
                            <label for="search_user">Masukkan Email atau Nama Pengguna</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search_user" name="search_user"
                                    value="<?= htmlspecialchars($_GET['search_user'] ?? ''); ?>" required />
                                <button class="btn btn-primary" type="submit" id="search-button">
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span> Cari
                                </button>
                            </div>
                            <small class="form-text text-muted">Sistem akan mencari pengguna berdasarkan input di
                                atas.</small>
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
                            <strong>Nama Pengguna:</strong> <p id="display_nama">-</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong> <p id="display_email">-</p>
                        </div>
                    </div>
                    <hr>
                    <form action="?hal=proses_update_langganan" method="POST">
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="form-group">
                            <label>Ubah Tipe Langganan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipe_user" id="status_free"
                                    value="free">
                                <label class="form-check-label" for="status_free">Free</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipe_user" id="status_premium"
                                    value="premium">
                                <label class="form-check-label" for="status_premium">Premium</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="masa_aktif">Masa Aktif Langganan (Sampai Tanggal)</label>
                            <input type="date" class="form-control" id="masa_aktif" name="masa_aktif">
                            <small class="form-text text-muted">Hanya diisi jika statusnya "Premium". Kosongkan jika
                                statusnya "Free".</small>
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
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('search-form');
        const searchInput = document.getElementById('search_user');
        const searchButton = document.getElementById('search-button');
        const spinner = searchButton.querySelector('.spinner-border');
        const manajemenContainer = document.getElementById('manajemen-container');

        // Fungsi untuk menampilkan modal notifikasi
        function showNotification(type, message) {
            const modalId = type === 'sukses' ? 'suksesModal' : 'gagalModal';
            const messageId = type === 'sukses' ? 'pesanSuksesModal' : 'pesanGagalModal';
            document.getElementById(messageId).innerText = message;
            new bootstrap.Modal(document.getElementById(modalId)).show();
        }

        // Fungsi untuk mencari user
        async function findUser(searchTerm) {
            if (!searchTerm) return;

            spinner.classList.remove('d-none');
            searchButton.disabled = true;
            manajemenContainer.style.display = 'none';

            try {
                const response = await fetch(`../api/api_cari_user.php?search=${encodeURIComponent(searchTerm)}`);
                const result = await response.json();

                if (result.status === 'success') {
                    const user = result.data;
                    document.getElementById('display_nama').innerText = user.nama;
                    document.getElementById('display_email').innerText = user.email;
                    document.getElementById('user_id').value = user.id;

                    if (user.tipe_user === 'premium') {
                        document.getElementById('status_premium').checked = true;
                    } else {
                        document.getElementById('status_free').checked = true;
                    }

                    document.getElementById('masa_aktif').value = user.masa_aktif ? user.masa_aktif.split(' ')[0] : '';
                    manajemenContainer.style.display = 'block';
                } else {
                    showNotification('gagal', result.message);
                }
            } catch (error) {
                showNotification('gagal', 'Terjadi kesalahan saat menghubungi server.');
            } finally {
                spinner.classList.add('d-none');
                searchButton.disabled = false;
            }
        }

        // Event listener untuk form pencarian
        searchForm.addEventListener('submit', function(event) {
            event.preventDefault();
            findUser(searchInput.value);
        });

        // Jika ada parameter 'search_user' di URL, langsung cari
        const initialSearchTerm = searchInput.value;
        if (initialSearchTerm) {
            findUser(initialSearchTerm);
        }
    });
</script>
