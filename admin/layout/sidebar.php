<!-- Sidebar -->
<div class="sidebar" data-background-color="blue">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="blue">
            <a href="index.php" class="logo">
                <img src="../assets/admin/img/logo/logo.png" alt="navbar brand" class="navbar-brand" height="34" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href=".?hal=beranda" class="active">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Landing Page -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Landing Page</h4>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#landingPage">
                        <i class="fas fa-globe"></i>
                        <p>Halaman Publik</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="landingPage">
                        <ul class="nav nav-collapse">
                            <li><a href=".?hal=artikel"><span class="sub-item">Artikel</span></a></li>
                            <li><a href=".?hal=kontak"><span class="sub-item">Manajemen Pesan</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- User Management -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">User Management</h4>
                </li>
                <li class="nav-item">
                    <a href=".?hal=user">
                        <i class="fas fa-user-graduate"></i>
                        <p>Siswa</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href=".?hal=guru">
                        <i class="fas fa-user-tie"></i>
                        <p>Guru</p>
                    </a>
                </li>

                <!-- Subscription Management -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Subscription Management</h4>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#subscription">
                        <i class="fas fa-calendar-check"></i>
                        <p>Paket Langganan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="subscription">
                        <ul class="nav nav-collapse">
                            <li><a href=".?hal=paket"><span class="sub-item">Daftar Paket</span></a></li>
                            <li><a href=".?hal=fitur"><span class="sub-item">Fitur Langganan</span></a></li>
                            <li><a href=".?hal=langganan"><span class="sub-item">Manajemen Langganan</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Payment Management -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Payment Management</h4>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#payment">
                        <i class="fas fa-credit-card"></i>
                        <p>Manajemen Pembayaran</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="payment">
                        <ul class="nav nav-collapse">
                            <li><a href=".?hal=metodepembayaran"><span class="sub-item">Metode Pembayaran</span></a></li>
                            <li><a href=".?hal=konfirmasi"><span class="sub-item">Konfirmasi Pembayaran</span></a></li>
                            <li><a href=".?hal=riwayat"><span class="sub-item">Riwayat Pembayaran</span></a></li>
                            <li><a href=".?hal=pendapatan"><span class="sub-item">Pendapatan Bulanan</span></a></li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
