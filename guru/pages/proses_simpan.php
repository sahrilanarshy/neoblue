
<!-- Modal Sukses Simpan -->
<div class="modal fade" id="suksesModal" tabindex="-1" aria-labelledby="suksesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="suksesModalLabel">
                    <i class="fas fa-check-circle me-2"></i> Berhasil Disimpan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center p-4">
                <i class="fas fa-check-circle fa-4x text-primary mb-3"></i>
                <h5 class="fw-semibold text-dark">Data materi berhasil disimpan!</h5>
                <p class="text-muted small mb-0">Kamu bisa melihatnya di daftar materi.</p>
            </div>

            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-primary px-4 rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-check"></i> Oke
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Style tambahan -->
<style>
    .modal-content {
        transition: all 0.3s ease-in-out;
    }

    .modal-header.bg-primary {
        background: linear-gradient(135deg, #007bff, #0062cc);
    }

    .modal-body i {
        animation: pop 0.4s ease-in-out;
    }

    @keyframes pop {
        0% {
            transform: scale(0.5);
            opacity: 0;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>
