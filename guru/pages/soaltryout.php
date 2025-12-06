<?php
include '../config/koneksi.php';
$id_tryout = isset($_GET['id_tryout']) ? intval($_GET['id_tryout']) : 0;
$tryout = null;
if ($id_tryout > 0) {
    $result = mysqli_query($koneksi, "SELECT nama_tryout FROM tryout WHERE id = $id_tryout");
    $tryout = mysqli_fetch_assoc($result);
}

if (!$tryout) {
    // Jika tidak ada id_tryout, atau tryout tidak ditemukan, tampilkan pesan.
    // Atau bisa redirect ke halaman daftar tryout.
    echo "<div class='page-inner'><div class='alert alert-warning'>Pilih tryout terlebih dahulu untuk melihat daftar soalnya. <a href='.?hal=tryout'>Kembali ke Daftar Tryout</a></div></div>";
    exit;
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
            <li class="nav-item"><a href=".?hal=tryout">Daftar Tryout</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Daftar Soal: <?= htmlspecialchars($tryout['nama_tryout']) ?></a></li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Soal untuk: <strong><?= htmlspecialchars($tryout['nama_tryout']) ?></strong></h4>
                    <button class="btn btn-primary btn-round ms-auto"
                        onclick="window.location.href='.?hal=tambahsoaltryout&id_tryout=<?= $id_tryout ?>';">
                        <i class="fa fa-plus"></i>
                        Tambah Soal
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="soal-tryout-table" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Subtest</th>
                                <th>Waktu</th>
                                <th>Jumlah Soal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const id_tryout = <?= $id_tryout; ?>;
    const table = $('#soal-tryout-table').DataTable({
        "processing": true,
        "serverSide": false, // Kita akan menggunakan client-side processing karena data diambil sekali
        "ajax": {
            "url": `../api/api_soaltryout.php?id_tryout=${id_tryout}`,
            "dataSrc": "data"
        },
        "columns": [
            { "data": null, "render": function (data, type, row, meta) { return meta.row + 1; } },
            { "data": "nama_subtest" },
            { "data": "waktu_pengerjaan", "render": function(data, type, row) { return (data || 0) + ' Menit'; }, "className": "text-center" },
            { "data": "jumlah_soal", "className": "text-center" },
            {
                "data": null,
                "render": function (data, type, row) {
                    const editUrl = `.?hal=editsoaltryout&id_tryout=${row.tryout_id}&subtest_id=${row.subtest_id}`;
                    const deleteAttrs = `data-bs-toggle="modal" data-bs-target="#hapusSoalTryoutModal" data-tryout-id="${row.tryout_id}" data-subtest-id="${row.subtest_id}" data-item-name="${row.nama_subtest}"`;
                    
                    return `
                        <div class="form-button-action">
                            <a href="${editUrl}" data-bs-toggle="tooltip" title="Edit Soal" class="btn btn-link btn-primary btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="#" ${deleteAttrs} title="Hapus Grup Soal Ini" class="btn btn-link btn-danger btn-sm">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    `;
                }
            }
        ],
        "drawCallback": function(settings) {
            // Inisialisasi ulang tooltip setelah tabel digambar ulang
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }
    });
});
</script>

<?php include 'hapussoaltryout.php'; ?>