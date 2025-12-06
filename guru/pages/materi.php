<?php
// Cek apakah ada pesan sukses dari session
if (isset($_SESSION['sukses'])) {
    // Simpan pesan ke variabel, lalu hapus dari session
    $pesan_sukses = $_SESSION['sukses'];
    unset($_SESSION['sukses']);
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Materi</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#"><i class="icon-home"></i></a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Daftar Materi</a></li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Materi</h4>
                    <button class="btn btn-primary btn-round ms-auto"
                        onclick="window.location.href='.?hal=tambahmateri';">
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
                                <th>Materi</th>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="materi-table-body">
                            <?php
                            include '../config/koneksi.php';
                            $no = 1;
                            $query = "SELECT m.id, m.judul, m.tanggal, m.tipe, s.nama_subtest FROM materi m LEFT JOIN subtest s ON m.subtest_id = s.id ORDER BY m.tanggal DESC, m.id DESC";
                            $result = mysqli_query($koneksi, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $tipeClass = $row['tipe'] === 'Free' ? 'btn-success' : 'btn-warning';
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_subtest'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['judul']) ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                <td>
                                    <a
                                        class="btn btn-primary btn-round btn-xs <?= $tipeClass ?>"><?= htmlspecialchars($row['tipe']) ?></a>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editmateri&id=<?= $row['id'] ?>" data-bs-toggle="tooltip"
                                            title="Edit" class="btn btn-link btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#hapusMateriModal"
                                            data-id="<?= $row['id'] ?>"
                                            data-judul="<?= htmlspecialchars($row['judul']) ?>" title="Hapus"
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

<?php include 'hapusmateri.php'; ?>

<!-- Modal Sukses universal -->
<div class="modal fade" id="suksesModal" tabindex="-1" aria-labelledby="suksesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="suksesModalLabel"><i class="fas fa-check-circle me-2"></i> Berhasil
                </h5>
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

<?php if (isset($pesan_sukses)): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('pesanSuksesModal').innerText = '<?= addslashes($pesan_sukses) ?>';
        new bootstrap.Modal(document.getElementById('suksesModal')).show();
    });
</script>
<?php endif; ?>
