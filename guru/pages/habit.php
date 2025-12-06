<?php
if (isset($_SESSION['sukses'])) {
    $pesan_sukses = $_SESSION['sukses'];
    unset($_SESSION['sukses']);
}

// Cek apakah ada pesan gagal dari session
if (isset($_SESSION['gagal'])) {
    // Simpan pesan ke variabel, lalu hapus dari session
    $pesan_gagal = $_SESSION['gagal'];
    unset($_SESSION['gagal']);
    unset($_SESSION['sukses']);
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Habit Harian</h3>
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
                <a href="#">Daftar Habit Harian</a>
            </li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Habit Harian</h4>

                    <div class="btn-group dropdown ms-auto">
                        <button type="button" class="btn btn-primary btn-round dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-plus"></i>
                            Tambah
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href=".?hal=tambahbacaan">
                                    <i class=""></i>Bacaan
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href=".?hal=tambahsoal">
                                    <i class=""></i>Soal
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="habit-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Subtest</th>
                                <th>Judul</th>
                                <th>Jenis</th>
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

<!-- Modal Gagal universal -->
<div class="modal fade" id="gagalModal" tabindex="-1" aria-labelledby="gagalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="gagalModalLabel"><i class="fas fa-times-circle me-2"></i> Gagal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
                <h5 class="fw-semibold text-dark" id="pesanGagalModal"></h5>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-danger px-4 rounded-pill" data-bs-dismiss="modal">Oke</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#habit-datatables').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": "../api/api_habit.php",
            "dataSrc": "data"
        },
        "columns": [
            { "data": null, "render": function (data, type, row, meta) { return meta.row + 1; } },
            { "data": "tanggal", "render": function(data) { return new Date(data).toLocaleDateString('id-ID'); } },
            { "data": "nama_subtest" },
            { "data": "judul" },
            { "data": "jenis", "render": function(data) {
                if (data === 'bacaan') {
                    return '<span class="btn btn-primary btn-round btn-xs btn-success">Bacaan</span>';
                } else {
                    return '<span class="btn btn-primary btn-round btn-xs btn-info">Soal</span>';
                }
            }},
            { "data": null, "render": function(data, type, row) {
                const editUrl = row.jenis === 'bacaan' ? `.?hal=editbacaan&id=${row.id}` : `.?hal=editsoal&id=${row.id}`;
                return `
                    <div class="form-button-action">
                        <a href="${editUrl}" data-bs-toggle="tooltip" title="Edit" class="btn btn-link btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#hapusHabitModal" data-id="${row.id}" data-item-name="${row.judul}" title="Hapus" class="btn btn-link btn-danger btn-sm"><i class="fa fa-times"></i></a>
                    </div>
                `;
            }}
        ]
    });

    <?php if (isset($pesan_sukses)): ?>
    document.getElementById('pesanSuksesModal').innerText = '<?= addslashes($pesan_sukses); ?>';
    new bootstrap.Modal(document.getElementById('suksesModal')).show();
    // Hapus pesan dari URL setelah modal ditampilkan
    window.history.replaceState({}, document.title, window.location.pathname);
    <?php endif; ?>

    <?php if (isset($pesan_gagal)): ?>
    document.getElementById('pesanGagalModal').innerText = '<?= addslashes($pesan_gagal); ?>';
    new bootstrap.Modal(document.getElementById('gagalModal')).show();
    // Hapus pesan dari URL setelah modal ditampilkan
    window.history.replaceState({}, document.title, window.location.pathname);
    <?php endif; ?>

});
</script>

<?php include 'hapushabit.php'; ?>
