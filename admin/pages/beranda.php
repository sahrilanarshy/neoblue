<div class="page-inner">
    <div class="container-fluid px-4 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <?php $admin_name = htmlspecialchars($_SESSION['nama'] ?? 'Admin'); ?>
                <h2 class="fw-bold text-dark mb-1">Dashboard Overview</h2>
                <p class="text-muted mb-0">Halo <strong><?= $admin_name ?></strong>, berikut statistik platform hari ini.
                </p>
            </div>
            <div class="d-none d-md-block">
                <span class="badge bg-light text-dark px-3 py-2 shadow-sm border">
                    <i class="far fa-calendar-alt me-2"></i> <?= date('d F Y') ?>
                </span>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="card card-modern">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Total Siswa</p>
                                <h3 class="stat-number" id="jumlah-siswa">...</h3>
                            </div>
                            <div class="stat-icon-wrapper bg-gradient-primary">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                        </div>
                        <div class="mt-4 d-flex align-items-center">
                            <a href=".?hal=user" class="stat-link text-primary">
                                Detail Siswa <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-modern">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Total Guru</p>
                                <h3 class="stat-number" id="jumlah-guru">...</h3>
                            </div>
                            <div class="stat-icon-wrapper bg-gradient-warning">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </div>
                        <div class="mt-4 d-flex align-items-center">
                            <a href=".?hal=guru" class="stat-link text-warning">
                                Detail Guru <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-modern">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Pesan Baru</p>
                                <h3 class="stat-number" id="pesan-masuk">...</h3>
                            </div>
                            <div class="stat-icon-wrapper bg-gradient-success-real">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        <div class="mt-4 d-flex align-items-center">
                            <a href=".?hal=kontak" class="stat-link text-success">
                                Cek Kotak Masuk <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-modern">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Pendapatan</p>
                                <h3 class="stat-number" id="total-pendapatan">...</h3>
                            </div>
                            <div class="stat-icon-wrapper bg-gradient-info">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                        <div class="mt-4 d-flex align-items-center">
                            <a href=".?hal=pendapatan" class="stat-link text-info">
                                Riwayat Transaksi <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-5">
                <div class="card card-modern">
                    <div class="chart-card-header">
                        <h5 class="chart-title">statistik Pengguna</h5>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div class="chart-container" style="height: 280px; width: 100%;">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card card-modern">
                    <div class="chart-card-header">
                        <h5 class="chart-title">statistik Pendapatan</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-modern action-card shadow-lg border-0">
                    <div
                        class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <div class="mb-3 mb-md-0">
                            <h4 class="fw-bold mb-1 text-white"><i class="fas fa-bolt me-2 text-warning"></i>
                                Maintenance Center</h4>
                            <p class="mb-0 text-white-50">Jalankan sinkronisasi sistem untuk memperbarui status
                                langganan siswa yang telah habis.</p>
                        </div>
                        <a href=".?hal=proses_cek_kadaluarsa"
                            class="btn btn-warning btn-lg fw-bold btn-glow rounded-pill px-4"
                            onclick="return confirm('Anda yakin ingin memperbarui semua status langganan yang sudah kedaluwarsa menjadi Free?');">
                            <i class="fas fa-sync-alt fa-spin me-2"></i> Sinkronisasi Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async function() {
        // Cache DOM elements
        const elements = {
            siswa: document.getElementById('jumlah-siswa'),
            guru: document.getElementById('jumlah-guru'),
            pesan: document.getElementById('pesan-masuk'),
            pendapatan: document.getElementById('total-pendapatan')
        };

        const updateElement = (key, value) => {
            if (elements[key]) elements[key].textContent = value;
        };

        // --- Modern Chart Config ---
        Chart.defaults.global.defaultFontFamily = "'Inter', 'Segoe UI', sans-serif";
        Chart.defaults.global.defaultFontColor = '#858796';

        const initCharts = (chartData) => {
            // 1. Modern Doughnut Chart (Pengganti Pie)
            if (chartData.statistik_user && document.getElementById("pieChart")) {
                const pieCtx = document.getElementById("pieChart").getContext("2d");
                new Chart(pieCtx, {
                    type: "doughnut", // Ubah ke Doughnut biar lebih modern
                    data: {
                        labels: ["User Free", "User Premium"],
                        datasets: [{
                            data: [
                                chartData.statistik_user.free || 0,
                                chartData.statistik_user.premium || 0
                            ],
                            backgroundColor: ["#e2e6ea",
                            "#667eea"], // Soft Gray & Modern Purple
                            hoverBackgroundColor: ["#dbe2e8", "#5a6fd6"],
                            borderWidth: 5,
                            borderColor: '#ffffff',
                            hoverBorderColor: '#ffffff'
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutoutPercentage: 70, // Lubang tengah
                        legend: {
                            position: "bottom",
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyFontColor: "#858796",
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                        }
                    }
                });
            }

            // 2. Modern Bar Chart
            if (chartData.statistik_pendapatan && document.getElementById("barChart")) {
                const barCtx = document.getElementById("barChart").getContext("2d");

                // Bikin gradient untuk batang chart
                let gradient = barCtx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(118, 75, 162, 1)'); // Purple top
                gradient.addColorStop(1, 'rgba(102, 126, 234, 0.6)'); // Blue bottom

                new Chart(barCtx, {
                    type: "bar",
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep",
                            "Oct", "Nov", "Dec"
                        ],
                        datasets: [{
                            label: "Pendapatan",
                            backgroundColor: gradient,
                            hoverBackgroundColor: "#4e73df",
                            borderColor: "#4e73df",
                            data: chartData.statistik_pendapatan,
                            barPercentage: 0.6, // Batang lebih ramping
                            categoryPercentage: 0.8,
                            maxBarThickness: 30, // Maksimal lebar batang
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    maxTicksLimit: 12
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    maxTicksLimit: 5,
                                    padding: 10,
                                    callback: function(value) {
                                        return 'Rp ' + (value / 1000)
                                            .toLocaleString('id-ID') +
                                            'k'; // Singkat angka
                                    }
                                },
                                gridLines: {
                                    color: "rgb(234, 236, 244)",
                                    zeroLineColor: "rgb(234, 236, 244)",
                                    drawBorder: false,
                                    borderDash: [2],
                                    zeroLineBorderDash: [2]
                                }
                            }],
                        },
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyFontColor: "#858796",
                            titleFontColor: '#6e707e',
                            titleMarginBottom: 10,
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            intersect: false,
                            mode: 'index',
                            caretPadding: 10,
                            callbacks: {
                                label: function(tooltipItem, chart) {
                                    return 'Pendapatan: Rp ' + tooltipItem.yLabel
                                        .toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                });
            }
        };

        // --- Fetch Data ---
        try {
            const response = await fetch('../../api/api_dashboard_admin.php');
            const result = await response.json();

            if (result.status === 'success' && result.data) {
                const data = result.data;
                updateElement('siswa', data.jumlah_siswa ?? 0);
                updateElement('guru', data.jumlah_guru || 0);
                updateElement('pesan', data.pesan_masuk || 0);
                updateElement('pendapatan', data.total_pendapatan_formatted || 'Rp 0');
                initCharts(data);
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Dashboard Error:', error);
            Object.keys(elements).forEach(key => updateElement(key, 'Err'));
        }
    });
</script>
