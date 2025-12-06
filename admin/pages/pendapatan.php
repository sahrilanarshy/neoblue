<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Pendapatan Bulanan</h3>
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
                <a href=".?hal=pendapatan">Laporan Keuangan</a>
            </li>
        </ul>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title">Rekap Pendapatan per Bulan</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bulan</th>
                                <th>Jumlah Transaksi</th>
                                <th>Total Pendapatan</th>
                                <th>Rata-rata per Transaksi</th>
                            </tr>
                        </thead>
                        <tbody id="pendapatan-table-body">
                            <tr><td colspan="5" class="text-center">Memuat data...</td></tr>
                        </tbody>
                        <tfoot id="pendapatan-table-foot" class="table-success">
                            <!-- Total akan di-generate oleh JavaScript -->
                        </tfoot>
                    </table>
                </div>
                <div class="mt-3 text-end">
                    <a href=".?hal=export_pendapatan" class="btn btn-success" target="_blank">
                        <i class="fa fa-file-excel"></i> Ekspor ke Excel
                    </a>
                    <a href=".?hal=export_pendapatan_pdf" class="btn btn-danger" target="_blank">
                        <i class="fa fa-file-pdf"></i> Ekspor ke PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    const tableBody = document.getElementById('pendapatan-table-body');
    const tableFoot = document.getElementById('pendapatan-table-foot');

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    try {
        const response = await fetch('../api/api_pendapatan.php');
        const result = await response.json();

        if (result.status === 'success') {
            tableBody.innerHTML = ''; // Kosongkan isi tabel
            let totalPendapatanKeseluruhan = 0;

            if (result.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Belum ada data pendapatan.</td></tr>';
            } else {
                result.data.forEach((item, index) => {
                    const totalPendapatan = parseFloat(item.total_pendapatan);
                    const jumlahTransaksi = parseInt(item.jumlah_transaksi);
                    const rataRata = jumlahTransaksi > 0 ? totalPendapatan / jumlahTransaksi : 0;
                    totalPendapatanKeseluruhan += totalPendapatan;

                    const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.bulan}</td>
                            <td>${jumlahTransaksi}</td>
                            <td>${formatRupiah(totalPendapatan)}</td>
                            <td>${formatRupiah(rataRata)}</td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            }

            // Update footer dengan total keseluruhan
            tableFoot.innerHTML = `
                <tr>
                    <th colspan="3" class="text-end fw-bold">Total Pendapatan Keseluruhan</th>
                    <th colspan="2" class="text-start fw-bold">${formatRupiah(totalPendapatanKeseluruhan)}</th>
                </tr>`;
        } else {
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Gagal memuat data: ${result.message}</td></tr>`;
        }
    } catch (error) {
        tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Terjadi kesalahan saat menghubungi server.</td></tr>`;
        console.error('Fetch error:', error);
    }
});
</script>
