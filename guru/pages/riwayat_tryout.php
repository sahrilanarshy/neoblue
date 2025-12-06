<?php
// Pastikan hanya guru yang bisa mengakses
if (isset($_SESSION['sukses'])) {
    $pesan_sukses = $_SESSION['sukses'];
    unset($_SESSION['sukses']);
} elseif (isset($_SESSION['gagal'])) {
    $pesan_gagal = $_SESSION['gagal'];
    unset($_SESSION['gagal']);
}

if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../login.php");
    exit();
}

include '../config/koneksi.php';

// Ambil riwayat tryout untuk semua user
$stmt = mysqli_prepare($koneksi, "
    SELECT 
        uts.id as session_id,
        u.nama as nama_siswa,
        u.email as email,
        t.nama_tryout,
        s.nama_subtest,
        uts.score,
        uts.end_time
    FROM 
        user_tryout_sessions uts
    JOIN 
        users u ON uts.user_id = u.id
    JOIN 
        tryout t ON uts.tryout_id = t.id
    JOIN 
        subtest s ON uts.subtest_id = s.id
    WHERE 
        uts.status = 'completed'
    ORDER BY 
        uts.end_time DESC
");

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$riwayat_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>

<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Riwayat Tryout Siswa</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href=".?hal=beranda">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href=".?hal=riwayat_tryout">Riwayat Tryout</a>
            </li>
        </ul>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Semua Tryout yang Telah Diselesaikan Siswa</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Email</th>
                                <th>Nama Tryout</th>
                                <th>Subtest</th>
                                <th>Skor</th>
                                <th>Tanggal Selesai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($riwayat_list as $index => $riwayat): ?>
                                <tr>
                                    <td><?= $index + 1; ?></td>
                                    <td><?= htmlspecialchars($riwayat['nama_siswa']); ?></td>
                                    <td><?= htmlspecialchars($riwayat['email']); ?></td>
                                    <td><?= htmlspecialchars($riwayat['nama_tryout']); ?></td>
                                    <td><?= htmlspecialchars($riwayat['nama_subtest']); ?></td>
                                    <td><span class="badge bg-primary"><?= htmlspecialchars($riwayat['score']); ?></span></td>
                                    <td><?= date('d M Y, H:i', strtotime($riwayat['end_time'])); ?></td>
                                    <td>
                                        <div class="form-button-action">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusRiwayatModal"
                                               data-id="<?= $riwayat['session_id']; ?>"
                                               data-item-name="riwayat pengerjaan oleh <?= htmlspecialchars($riwayat['nama_siswa']); ?>"
                                               title="Hapus" class="btn btn-link btn-danger btn-sm">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'hapusriwayattryout.php'; ?>
<?php include 'sukses_gagal_modal.php'; ?>

<script>
    <?php if (isset($pesan_sukses)): ?> showModal('suksesModal', '<?= addslashes($pesan_sukses); ?>'); <?php endif; ?>
    <?php if (isset($pesan_gagal)): ?> showModal('gagalModal', '<?= addslashes($pesan_gagal); ?>'); <?php endif; ?>
</script>