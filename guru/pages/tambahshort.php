<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Short</h3>
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
                <a href=".?hal=short">Daftar Short</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href=".?hal=tambahshort">Tambah Short</a>
            </li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Short</h4>
                </div>
                <div class="card-body">
                    <form action=".?hal=proses_short&aksi=tambah" method="POST" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="tanggal_upload">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal_upload" name="tanggal_upload"
                                required />
                        </div>

                        <div class="form-group">
                            <label for="judul_short">Judul Short</label>
                            <input type="text" class="form-control" id="judul_short" name="judul_short"
                                placeholder="Contoh: Trik Cepat Operasi Bilangan" required />
                        </div>

                        <div class="form-group">
                            <label for="video_short">Upload Video Short</label>
                            <input type="file" class="form-control" id="video_short" name="video_short"
                                accept="video/*" required />
                            <small class="form-text text-muted">Pilih file video dari komputer Anda (format .mp4, .mov,
                                dll).</small>
                        </div>

                        <!-- Pratinjau Video -->
                        <div id="preview_container" class="form-group" style="display: none;">
                            <label>Pratinjau Video</label>
                            <video id="video_preview" width="100%" controls style="max-height: 300px; border-radius: 8px; background-color: #f0f0f0;"></video>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_premium"
                                    name="is_premium" value="1" checked>
                                <label class="form-check-label" for="is_premium">Jadikan short ini Premium</label>
                                <small class="form-text text-muted d-block">Aktifkan jika short ini berbayar,
                                    non-aktifkan jika gratis.</small>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href=".?hal=short" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoInput = document.getElementById('video_short');
    const videoPreview = document.getElementById('video_preview');
    const previewContainer = document.getElementById('preview_container');

    videoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const fileURL = URL.createObjectURL(file);
            videoPreview.src = fileURL;
            previewContainer.style.display = 'block';
        } else {
            previewContainer.style.display = 'none';
            videoPreview.src = '';
        }
    });
});
</script>
