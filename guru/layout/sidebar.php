<!-- Sidebar -->
<div class="sidebar" data-background-color="blue">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="blue">
            <a href="index.php" class="logo">
                <img src="../assets/admin/img/logo/logo 1.png" alt="navbar brand" class="navbar-brand" height="40" />
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
                <li class="nav-item">
                    <a href=".?hal=beranda">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">MENU UTAMA</h4>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#materi">
                        <i class="fas fa-book"></i>
                        <p>Materi</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="materi">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href=".?hal=subtest">
                                    <span class="sub-item">Subtest</span>
                                </a>
                            </li>
                            <li>
                                <a href=".?hal=materi">
                                    <span class="sub-item">Materi</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#tryout">
                        <i class="fas fa-file-alt"></i>
                        <p>Tryout</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="tryout">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href=".?hal=tryout">
                                    <span class="sub-item">Tryout</span>
                                </a>
                            </li>
                            <li>
                                <a href=".?hal=soaltryout">
                                    <span class="sub-item">Soal Tryout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href=".?hal=habit">
                        <i class="fas fa-calendar-check"></i>
                        <p>Habit</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href=".?hal=short">
                        <i class="fas fa-video"></i>
                        <p>Short</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">LAIN - LAIN</h4>
                </li>
                <li class="nav-item">
                    <a href=".?hal=pengumuman">
                        <i class="fas fa-bullhorn"></i>
                        <p>Pengumuman</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
