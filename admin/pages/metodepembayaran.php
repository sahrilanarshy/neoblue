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
        <h3 class="fw-bold mb-3">Metode Pembayaran</h3>
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
                <a href=".?hal=metodepembayaran">Daftar Metode Pembayaran</a>
            </li>
        </ul>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Metode Pembayaran</h4>
                    <button class="btn btn-primary btn-round ms-auto"
                        onclick="window.location.href='.?hal=tambahmetodepembayaran';">
                        <i class="fa fa-plus"></i>
                        Tambah
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Metode</th>
                                <th>Nomor Rekening / ID</th>
                                <th>Atas Nama</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Metode</th>
                                <th>Nomor Rekening / ID</th>
                                <th>Atas Nama</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            include '../config/koneksi.php';
                            $query = mysqli_query($koneksi, "SELECT * FROM metode_pembayaran ORDER BY id DESC");
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr class="align-middle">
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($data['nama_metode']); ?></td>
                                    <td><?= htmlspecialchars($data['nomor_rekening']); ?></td>
                                    <td><?= htmlspecialchars($data['atas_nama']); ?></td>
                                    <td class="text-center">
                                        <?php if ($data['status'] == 'Aktif') : ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else : ?>
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-button-action">
                                            <a href=".?hal=editmetodepembayaran&id=<?= $data['id']; ?>" data-bs-toggle="tooltip" title="Edit"
                                                class="btn btn-link btn-primary btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                                data-id="<?= $data['id']; ?>" data-item-name="<?= htmlspecialchars($data['nama_metode']); ?>"
                                                data-url-delete="?hal=proses_hapus_metode" title="Hapus"
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
