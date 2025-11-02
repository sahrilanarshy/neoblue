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
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Subtest</th>
                                <th>Materi</th>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Penalaran Umum</td>
                                <td>Operasi Bilangan</td>
                                <td>10/10/2025</td>
                                <td>
                                    <a class="btn btn-primary btn-round btn-xs btn-success">Free</a>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editmateri&id=1" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                            data-id="1" data-item-name="Operasi Bilangan" title="Hapus"
                                            class="btn btn-link btn-danger">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Penalaran Umum</td>
                                <td>Logika Matematika</td>
                                <td>11/10/2025</td>
                                <td>
                                    <a class="btn btn-primary btn-round btn-xs btn-warning">Premium</a>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <a href=".?hal=editmateri&id=2" data-bs-toggle="tooltip" title="Edit"
                                            class="btn btn-link btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                            data-id="2" data-item-name="Logika Matematika" title="Hapus"
                                            class="btn btn-link btn-danger">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus materi <strong id="modal-item-name"></strong>?
                <p class="text-danger small mt-2">Aksi ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="confirmDeleteButton" class="btn btn-danger">Ya, Hapus</a>
            </div>
        </div>
    </div>
</div>

<script>
    const confirmDeleteModal = document.getElementById('confirmDeleteModal');

    confirmDeleteModal.addEventListener('show.bs.modal', event => {
        // Tombol yang memicu modal
        const button = event.relatedTarget;

        // Ekstrak informasi dari atribut data-*
        const itemId = button.getAttribute('data-id');
        const itemName = button.getAttribute('data-item-name');

        // Perbarui konten modal
        const modalItemName = confirmDeleteModal.querySelector('#modal-item-name');
        const confirmButton = confirmDeleteModal.querySelector('#confirmDeleteButton');

        modalItemName.textContent = `"${itemName}"`;

        // Atur link href untuk tombol hapus di dalam modal
        confirmButton.href = `.?hal=hapusmateri&id=${itemId}`;
    });
</script>