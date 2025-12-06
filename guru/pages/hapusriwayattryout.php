<!-- Modal Konfirmasi Hapus Riwayat Tryout -->
<div class="modal fade" id="hapusRiwayatModal" tabindex="-1" aria-labelledby="hapusRiwayatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="hapusRiwayatModalLabel">
                    <i class="fas fa-info-circle me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center p-4">
                <i class="fas fa-trash-alt fa-3x text-primary mb-3"></i>
                <p class="mb-2 text-dark">Apakah Anda yakin ingin menghapus <b><span id="namaRiwayat"></span></b>?</p>
                <small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>
            </div>

            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">
                    Batal
                </button>
                <a href="#" id="btnHapusRiwayat" class="btn btn-primary px-4 rounded-pill">
                    Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk menampilkan data riwayat yang dipilih -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hapusRiwayatModal = document.getElementById('hapusRiwayatModal');
        hapusRiwayatModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-item-name');
            hapusRiwayatModal.querySelector('#namaRiwayat').textContent = nama;
            hapusRiwayatModal.querySelector('#btnHapusRiwayat').href = `pages/proses_riwayat_tryout.php?aksi=hapus&session_id=${id}`;
        });
    });
</script>