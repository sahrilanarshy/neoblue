<?php
if (isset($_SESSION['sukses'])) {
    $pesan_sukses = $_SESSION['sukses'];
    unset($_SESSION['sukses']);
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Tryout</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#"><i class="icon-home"></i></a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Daftar Tryout</a></li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Tryout</h4>
                    <button class="btn btn-primary btn-round ms-auto" onclick="window.location.href='.?hal=tambahtryout';">
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
                                <th>Nama Tryout</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Tipe</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include '../config/koneksi.php';
                            $no = 1;
                            $query = "SELECT id, nama_tryout, tanggal_mulai, tanggal_selesai, tipe FROM tryout ORDER BY tanggal_mulai DESC";
                            $result = mysqli_query($koneksi, $query);
                            $today = date('Y-m-d');
                            while ($row = mysqli_fetch_assoc($result)) {
                                $status = '';
                                $status_badge = '';
                                if ($today < $row['tanggal_mulai']) {
                                    $status = 'Mendatang';
                                    $status_badge = 'btn-warning';
                                } elseif ($today >= $row['tanggal_mulai'] && $today <= $row['tanggal_selesai']) {
                                    $status = 'Aktif';
                                    $status_badge = 'btn-success';
                                } else {
                                    $status = 'Selesai';
                                    $status_badge = 'btn-secondary';
                                }

                                $tipe_badge = $row['tipe'] == 'Free' ? 'btn-success' : 'btn-warning';
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><a href=".?hal=soaltryout&id_tryout=<?= $row['id']; ?>"><?= htmlspecialchars($row['nama_tryout']); ?></a></td>
                                    <td><?= date('d/m/Y', strtotime($row['tanggal_mulai'])); ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['tanggal_selesai'])); ?></td>
                                    <td><a class="btn btn-primary btn-round btn-xs <?= $tipe_badge; ?>"><?= htmlspecialchars($row['tipe']); ?></a></td>
                                    <td>
                                        <div class="form-button-action">
                                            <a href=".?hal=tambahsoaltryout&id_tryout=<?= $row['id']; ?>" data-bs-toggle="tooltip" title="Tambah Soal" class="btn btn-link btn-success btn-sm">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                            <a href=".?hal=edittryout&id=<?= $row['id']; ?>" data-bs-toggle="tooltip" title="Edit" class="btn btn-link btn-primary btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusTryoutModal" data-id="<?= $row['id']; ?>" data-item-name="<?= htmlspecialchars($row['nama_tryout']); ?>" title="Hapus" class="btn btn-link btn-danger btn-sm">
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
<script>
    <?php if (isset($pesan_sukses)): ?>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('pesanSuksesModal').innerText = '<?= addslashes($pesan_sukses); ?>';
        new bootstrap.Modal(document.getElementById('suksesModal')).show();
    });
    <?php endif; ?>
</script>

<?php include 'hapustryout.php'; ?>