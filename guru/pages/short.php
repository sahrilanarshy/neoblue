<?php
if (isset($_SESSION['sukses'])) {
    $pesan_sukses = $_SESSION['sukses'];
    unset($_SESSION['sukses']);
}
?>
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
                <a href="#">Daftar Short</a>
            </li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Short</h4>
                    <button class="btn btn-primary btn-round ms-auto"
                        onclick="window.location.href='.?hal=tambahshort';">
                        <i class="fa fa-plus"></i>
                        Tambah
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="short-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Judul</th>
                                <th>Tipe</th>
                                <th>Video</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'hapusshort.php'; ?>

<!-- Modal Sukses universal -->
<div class="modal fade" id="suksesModal" tabindex="-1" aria-labelledby="suksesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="suksesModalLabel"><i class="fas fa-check-circle me-2"></i> Berhasil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fas fa-check-circle fa-4x text-primary mb-3"></i>
                <h5 class="fw-semibold text-dark" id="pesanSuksesModal"></h5>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-primary px-4 rounded-pill" data-bs-dismiss="modal">Oke</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#short-datatables').DataTable({
        "processing": true,
        "serverSide": false, // Data diambil sekali, proses di client-side
        "ajax": {
            "url": "../api/api_short.php",
            "dataSrc": "data"
        },
        "columns": [
            { "data": null, "render": function (data, type, row, meta) { return meta.row + 1; }, "width": "5%" },
            { "data": "tanggal_upload", "render": function(data) { 
                // Format tanggal ke d/m/Y
                const date = new Date(data);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            }},
            { "data": "judul" },
            { "data": "tipe", "render": function(data) {
                const badgeClass = data === 'Free' ? 'btn-success' : 'btn-warning';
                return `<span class="btn btn-primary btn-round btn-xs ${badgeClass}">${data}</span>`;
            }},
            { "data": "video_path", "render": function(data) {
                return `<a href="../${data}" target="_blank" class="btn btn-link btn-info btn-sm"><i class="fas fa-play-circle"></i></a>`;
            }, "orderable": false, "className": "text-center" },
            { "data": null, "render": function(data, type, row) {
                return `
                    <div class="form-button-action">
                        <a href=".?hal=editshort&id=${row.id}" data-bs-toggle="tooltip" title="Edit" class="btn btn-link btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#hapusShortModal" data-id="${row.id}" data-judul="${row.judul}" title="Hapus" class="btn btn-link btn-danger btn-sm"><i class="fa fa-times"></i></a>
                    </div>
                `;
            }, "orderable": false }
        ]
    });
});
</script>

<?php if (isset($pesan_sukses)): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('pesanSuksesModal').innerText = '<?= addslashes($pesan_sukses); ?>';
        new bootstrap.Modal(document.getElementById('suksesModal')).show();
    });
</script>
<?php endif; ?>
