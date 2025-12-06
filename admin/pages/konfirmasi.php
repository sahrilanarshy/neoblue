<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Pembayaran</h3>
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
                <a href=".?hal=konfirmasi">Konfirmasi Pembayaran</a>
            </li>
        </ul>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Pembayaran Menunggu Verifikasi</h4>
                    <a href=".?hal=riwayat" class="btn btn-secondary btn-round ms-auto">
                        <i class="fa fa-history"></i>
                        Lihat Riwayat
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Email</th>
                                <th>Paket</th>
                                <th>Metode</th>
                                <th>Tanggal</th>
                                <th>Catatan</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Email</th>
                                <th>Paket</th>
                                <th>Metode</th>
                                <th>Tanggal</th>
                                <th>Catatan</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            include '../config/koneksi.php';
                            $query = mysqli_query($koneksi, "
                                SELECT p.id, u.nama as nama_siswa, u.email as email, pk.nama_paket, mp.nama_metode, p.tanggal_pembayaran, p.catatan, p.bukti_pembayaran, p.status_pembayaran
                                FROM pembayaran p
                                JOIN users u ON p.user_id = u.id
                                JOIN paket pk ON p.paket_id = pk.id
                                JOIN metode_pembayaran mp ON p.metode_pembayaran_id = mp.id
                                WHERE p.status_pembayaran = 'Menunggu'
                                ORDER BY p.id DESC
                            ");
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($data['nama_siswa']); ?></td>
                                    <td><?= htmlspecialchars($data['email']); ?></td>
                                    <td><?= htmlspecialchars($data['nama_paket']); ?></td>
                                    <td><?= htmlspecialchars($data['nama_metode']); ?></td>
                                    <td><?= date('d M Y, H:i', strtotime($data['tanggal_pembayaran'])); ?></td>
                                    <td><?= htmlspecialchars($data['catatan']); ?></td>
                                    <td>
                                        <a href="../<?= htmlspecialchars($data['bukti_pembayaran']); ?>" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                    </td>
                                    <td><span class="badge bg-warning">Menunggu</span></td>
                                    <td>
                                        <div class="form-button-action">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#actionConfirmModal"
                                               data-id="<?= $data['id']; ?>"
                                               data-action="terima"
                                               data-item-name="Transaksi ID #<?= $data['id']; ?> (<?= htmlspecialchars($data['nama_siswa']); ?>)"
                                               class="btn btn-link btn-success btn-sm" title="Terima">
                                                <i class="fa fa-check"></i>
                                            </a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#actionConfirmModal"
                                               data-id="<?= $data['id']; ?>"
                                               data-action="tolak"
                                               data-item-name="Transaksi ID #<?= $data['id']; ?> (<?= htmlspecialchars($data['nama_siswa']); ?>)"
                                               class="btn btn-link btn-danger btn-sm" title="Tolak">
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
                <?php
                if (isset($_SESSION['sukses'])) {
                    $pesan_sukses = $_SESSION['sukses'];
                    unset($_SESSION['sukses']);
                } elseif (isset($_SESSION['gagal'])) {
                    $pesan_gagal = $_SESSION['gagal'];
                    unset($_SESSION['gagal']);
                }
                ?>

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
