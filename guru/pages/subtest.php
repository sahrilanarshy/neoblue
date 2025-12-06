<?php
if (isset($_SESSION['sukses'])) {
    $pesan_sukses = $_SESSION['sukses'];
    unset($_SESSION['sukses']);
}

// Cek apakah ada pesan gagal dari session
if (isset($_SESSION['gagal'])) {
    $pesan_gagal = $_SESSION['gagal'];
    unset($_SESSION['sukses']);
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Materi</h3>
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
                <a href="#">Daftar Subtest</a>
            </li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Subtest</h4>
                    <button class="btn btn-primary btn-round ms-auto"
                        onclick="window.location.href='.?hal=tambahsubtest';">
                        <i class="fa fa-plus"></i>
                        Tambah
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Subtest</th>
                                <th>Singkatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include '../config/koneksi.php';
                            $no = 1;
                            $query = "SELECT * FROM subtest ORDER BY id ASC";
                            $result = mysqli_query($koneksi, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_subtest']) ?></td>
                                <td><?= htmlspecialchars($row['singkatan'] ?? '') ?></td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editsubtest&id=<?= $row['id'] ?>" data-bs-toggle="tooltip"
                                            title="Edit" class="btn btn-link btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#hapusSubtestModal"
                                            data-id="<?= $row['id'] ?>"
                                            data-nama="<?= htmlspecialchars($row['nama_subtest']) ?>" title="Hapus"
                                            class="btn btn-link btn-danger btn-sm">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
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

<?php include 'hapussubtest.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Script untuk menampilkan modal sukses jika ada pesan dari session
    <?php if (isset($pesan_sukses)): ?>
        document.getElementById('pesanSuksesModal').innerText = '<?= addslashes($pesan_sukses); ?>';
        new bootstrap.Modal(document.getElementById('suksesModal')).show();
        window.history.replaceState({}, document.title, window.location.pathname); // Hapus pesan dari URL
    <?php endif; ?>

    // Script untuk menampilkan modal gagal jika ada pesan dari session
    <?php if (isset($pesan_gagal)): ?>
        document.getElementById('pesanGagalModal').innerText = '<?= addslashes($pesan_gagal); ?>';
        new bootstrap.Modal(document.getElementById('gagalModal')).show();
        window.history.replaceState({}, document.title, window.location.pathname); // Hapus pesan dari URL
    <?php endif; ?>
});
</script>
