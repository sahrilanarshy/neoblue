<!-- Modal Konfirmasi Hapus Universal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="confirmDeleteModalLabel"><i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fas fa-trash-alt fa-4x text-danger mb-3"></i>
                <h5 class="fw-semibold text-dark">Apakah Anda yakin ingin menghapus item ini?</h5>
                <p class="text-muted">Anda akan menghapus: <strong id="itemName"></strong></p>
                <p class="text-danger small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" action="">
                    <input type="hidden" name="id" id="deleteItemId">
                    <button type="submit" class="btn btn-danger px-4 rounded-pill">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var confirmDeleteModal = document.getElementById('confirmDeleteModal');
    if (confirmDeleteModal) {
        confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var itemId = button.getAttribute('data-id');
            var itemName = button.getAttribute('data-item-name');
            var urlDelete = button.getAttribute('data-url-delete');

            var modalItemName = confirmDeleteModal.querySelector('#itemName');
            var modalDeleteItemId = confirmDeleteModal.querySelector('#deleteItemId');
            var modalDeleteForm = confirmDeleteModal.querySelector('#deleteForm');

            modalItemName.textContent = itemName;
            modalDeleteItemId.value = itemId;
            modalDeleteForm.action = urlDelete;
        });
    }
});
</script>