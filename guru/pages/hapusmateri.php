<!-- Modal Konfirmasi Hapus Materi -->
<div class="modal fade" id="hapusMateriModal" tabindex="-1" aria-labelledby="hapusMateriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="hapusMateriModalLabel">
                    <i class="fas fa-info-circle me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center p-4">
                <i class="fas fa-trash-alt fa-3x text-primary mb-3"></i>
                <p class="mb-2 text-dark">Apakah kamu yakin ingin menghapus <b><span id="judulMateri"></span></b>?</p>
                <small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>
            </div>

            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">
                    Batal
                </button>
                <a href="#" id="btnHapusMateri" class="btn btn-primary px-4 rounded-pill">
                    Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk menampilkan data materi yang dipilih -->
<script>
    const hapusModal = document.getElementById('hapusMateriModal');
    hapusModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const judul = button.getAttribute('data-judul');

        const judulMateri = hapusModal.querySelector('#judulMateri');
        const btnHapus = hapusModal.querySelector('#btnHapusMateri');

        judulMateri.textContent = judul;
        btnHapus.href = '.?hal=materi'; // Ganti sesuai file PHP kamu
    });
</script>
