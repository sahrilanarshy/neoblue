<!-- Modal Konfirmasi Aksi (Terima/Tolak) -->
<div class="modal fade" id="actionConfirmModal" tabindex="-1" aria-labelledby="actionConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="actionConfirmModalLabel"><i class="fas fa-question-circle me-2"></i> Konfirmasi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fas fa-exclamation-circle fa-4x text-primary mb-3"></i>
                <h5 class="fw-semibold text-dark" id="actionConfirmMessage">Apakah Anda yakin ingin melanjutkan aksi ini?</h5>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="actionConfirmBtn" class="btn btn-primary px-4 rounded-pill">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const actionModal = document.getElementById('actionConfirmModal');
    const actionMessage = document.getElementById('actionConfirmMessage');
    const actionBtn = document.getElementById('actionConfirmBtn');

    if (!actionModal) return;

    actionModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const action = button.getAttribute('data-action');
        const itemName = button.getAttribute('data-item-name');

        let message = 'Apakah Anda yakin ingin melanjutkan aksi ini?';
        if (action === 'terima') {
            message = 'Anda yakin ingin MENERIMA pembayaran ini?';
        } else if (action === 'tolak') {
            message = 'Anda yakin ingin MENOLAK pembayaran ini?';
        }

        if (itemName) {
            // tambahkan nama singkat di bawah pesan jika tersedia (gunakan <br> agar tampil rapi)
            message = message + '<br><small class="text-muted">' + escapeHtml(itemName) + '</small>';
        }

        actionMessage.innerHTML = message;

        // simpan info pada tombol konfirmasi
        actionBtn.dataset.id = id;
        actionBtn.dataset.action = action;
    });

    actionBtn.addEventListener('click', function () {
        const id = this.dataset.id;
        const action = this.dataset.action;
        if (!id || !action) return;

        // Redirect ke handler yang sesuai
        const url = `.?hal=proses_konfirmasi_pembayaran&id=${id}&action=${action}`;
        window.location.href = url;
    });
    // Utility to escape HTML for safe insertion
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
});
</script>