<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Riwayat Pembayaran</h3>
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
                <a href="#">Transaksi Selesai</a>
            </li>
        </ul>
    </div>

    <div class="col-md-12">
        <div class="card">
            <?php
            include '../config/koneksi.php';
            // Hitung jumlah pembayaran yang menunggu
            $result_pending = mysqli_query($koneksi, "SELECT COUNT(id) as total_pending FROM pembayaran WHERE status_pembayaran = 'Menunggu'");
            $pending_count = mysqli_fetch_assoc($result_pending)['total_pending'];
            ?>
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Pembayaran Selesai</h4>
                    <a href=".?hal=konfirmasi" class="btn btn-warning btn-round ms-auto">
                        <i class="fa fa-hourglass-half"></i>
                        Pembayaran Menunggu
                        <span class="badge bg-danger ms-1"><?= $pending_count; ?></span>
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
                                <th>Tgl. Konfirmasi</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_pendapatan = 0;
                            $query = mysqli_query($koneksi, "
                                SELECT 
                                    p.id, u.nama as nama_siswa, u.email as email, pk.nama_paket, pk.harga, mp.nama_metode, 
                                    p.tanggal_konfirmasi, p.status_pembayaran
                                FROM pembayaran p
                                JOIN users u ON p.user_id = u.id
                                JOIN paket pk ON p.paket_id = pk.id
                                JOIN metode_pembayaran mp ON p.metode_pembayaran_id = mp.id
                                WHERE p.status_pembayaran IN ('Diterima', 'Ditolak')
                                ORDER BY p.tanggal_konfirmasi DESC
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
                                    <td><?= date('d M Y, H:i', strtotime($data['tanggal_konfirmasi'])); ?></td>
                                    <td>Rp<?= number_format($data['harga'], 0, ',', '.'); ?></td>
                                    <td>
                                        <?php if ($data['status_pembayaran'] == 'Diterima') : ?>
                                            <span class="badge bg-success">Diterima</span>
                                            <?php $total_pendapatan += $data['harga']; ?>
                                        <?php else : ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                               data-id="<?= $data['id']; ?>"
                                               data-item-name="Transaksi ID #<?= $data['id']; ?> (<?= htmlspecialchars($data['nama_siswa']); ?>)"
                                               data-url-delete=".?hal=proses_hapus_pembayaran"
                                               class="btn btn-link btn-danger btn-sm" title="Hapus">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7" class="text-end fw-bold">Total Pendapatan (Diterima)</th>
                                <th colspan="2" class="text-start fw-bold">Rp<?= number_format($total_pendapatan, 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
