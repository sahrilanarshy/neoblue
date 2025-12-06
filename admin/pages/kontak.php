<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Manajemen Pesan</h3>
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
                <a href=".?hal=kontak">Daftar Pesan Masuk</a>
            </li>
        </ul>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Pesan Masuk</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pengirim</th>
                                <th>Email</th>
                                <th>Subjek</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pengirim</th>
                                <th>Email</th>
                                <th>Subjek</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            include '../config/koneksi.php';
                            $query = mysqli_query($koneksi, "SELECT * FROM kontak ORDER BY id DESC");
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($query)) {
                                $status_badge = '';
                                switch ($data['status']) {
                                    case 'Baru':
                                        $status_badge = '<span class="badge bg-danger">Baru</span>';
                                        break;
                                    case 'Sudah Dibaca':
                                        $status_badge = '<span class="badge bg-info">Sudah Dibaca</span>';
                                        break;
                                    case 'Sudah Dibalas':
                                        $status_badge = '<span class="badge bg-success">Sudah Dibalas</span>';
                                        break;
                                }
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= date('d M Y, H:i', strtotime($data['tanggal_kirim'])); ?></td>
                                    <td><?= htmlspecialchars($data['nama']); ?></td>
                                    <td><?= htmlspecialchars($data['email']); ?></td>
                                    <td><?= htmlspecialchars($data['subjek']); ?></td>
                                    <td><?= $status_badge; ?></td>
                                    <td>
                                        <div class="form-button-action">
                                            <a href=".?hal=lihatpesan&id=<?= $data['id']; ?>" data-bs-toggle="tooltip" title="Lihat Pesan"
                                                class="btn btn-link btn-primary btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                                data-id="<?= $data['id']; ?>" data-item-name="Pesan dari <?= htmlspecialchars($data['nama']); ?>"
                                                data-url-delete="?hal=proses_hapus_pesan" title="Hapus"
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
