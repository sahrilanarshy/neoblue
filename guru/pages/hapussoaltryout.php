<!-- Modal Konfirmasi Hapus Grup Soal Tryout -->
<div class="modal fade" id="hapusSoalTryoutModal" tabindex="-1" aria-labelledby="hapusSoalTryoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="hapusSoalTryoutModalLabel">
                    <i class="fas fa-info-circle me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center p-4">
                <i class="fas fa-trash-alt fa-3x text-primary mb-3"></i>
                <p class="mb-2 text-dark">Apakah Anda yakin ingin menghapus <strong>semua soal</strong> untuk subtest <b><span id="namaSubtestSoal"></span></b> pada tryout ini?</p>
                <small class="text-muted">Aksi ini akan menghapus semua soal dalam grup ini dan tidak dapat dibatalkan.</small>
            </div>

            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">
                    Batal
                </button>
                <a href="#" id="btnHapusSoalTryout" class="btn btn-primary px-4 rounded-pill">
                    Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk menampilkan data grup soal yang dipilih -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hapusSoalTryoutModal = document.getElementById('hapusSoalTryoutModal');
        hapusSoalTryoutModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const tryoutId = button.getAttribute('data-tryout-id');
            const subtestId = button.getAttribute('data-subtest-id');
            const namaSubtest = button.getAttribute('data-item-name');
            hapusSoalTryoutModal.querySelector('#namaSubtestSoal').textContent = namaSubtest;
            hapusSoalTryoutModal.querySelector('#btnHapusSoalTryout').href = `.?hal=proses_soal_tryout&aksi=hapus_grup&id_tryout=${tryoutId}&subtest_id=${subtestId}`;
        });
    });
</script>