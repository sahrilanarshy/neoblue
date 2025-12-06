<?php
if (isset($_SESSION['sukses'])) {
    $pesan_sukses = $_SESSION['sukses'];
    unset($_SESSION['sukses']);
} elseif (isset($_SESSION['gagal'])) {
    $pesan_gagal = $_SESSION['gagal'];
    unset($_SESSION['gagal']);
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Guru</h3>
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
                <a href=".?hal=guru">Daftar Guru</a>
            </li>
        </ul>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Guru</h4>
                    <button class="btn btn-primary btn-round ms-auto"
                        onclick="window.location.href='.?hal=tambahguru';">
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
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            include '../config/koneksi.php';
                            $query = mysqli_query($koneksi, "SELECT * FROM users WHERE role = 'guru' ORDER BY id DESC");
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($query)) {
                                $foto_path = !empty($data['foto_profil']) ? '../' . $data['foto_profil'] : '../assets/admin/img/logo/icon profile.png';
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td>
                                        <img src="<?= $foto_path ?>" alt="Foto Guru" width="45" height="45" class="rounded-circle border border-2 shadow-sm">
                                    </td>
                                    <td><?= htmlspecialchars($data['nama']); ?></td>
                                    <td><?= htmlspecialchars($data['email']); ?></td>
                                    <td><?= htmlspecialchars($data['telepon'] ?? 'N/A'); ?></td>
                                    <td>
                                        <div class="form-button-action">
                                            <a href=".?hal=editguru&id=<?= $data['id']; ?>" data-bs-toggle="tooltip" title="Edit"
                                                class="btn btn-link btn-primary btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                                data-id="<?= $data['id']; ?>" data-item-name="<?= htmlspecialchars($data['nama']); ?>"
                                                data-url-delete="?hal=proses_hapus_guru" title="Hapus"
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    function showModal(modalId, message) {
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            const messageElementId = modalId === 'suksesModal' ? 'pesanSuksesModal' : 'pesanGagalModal';
            document.getElementById(messageElementId).innerText = message;
            new bootstrap.Modal(modalElement).show();
        }
    }

    <?php if (isset($pesan_sukses)): ?>
        showModal('suksesModal', '<?= addslashes($pesan_sukses); ?>');
    <?php elseif (isset($pesan_gagal)): ?>
        showModal('gagalModal', '<?= addslashes($pesan_gagal); ?>');
    <?php endif; ?>
});
</script>
