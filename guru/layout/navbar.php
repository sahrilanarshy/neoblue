<?php
$user_id = $_SESSION['user_id'] ?? 0;
$user_data = [
    'nama' => 'Guest',
    'email' => 'guest@example.com',
    'role' => 'guest',
    'foto_profil' => '../assets/admin/img/logo/icon profile.png' // Default image
];

if ($user_id > 0) {
    // Menggunakan prepared statement untuk keamanan
    $stmt = mysqli_prepare($koneksi, "SELECT nama, email, role, foto_profil FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $user_data = $row;
        $user_data['foto_profil'] = (!empty($row['foto_profil']) && file_exists('../' . $row['foto_profil'])) ? '../' . $row['foto_profil'] : '../assets/admin/img/logo/icon profile.png';
    }
}
?>
<div class="main-header">
    <div class="main-header-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="blue">
            <a href="index.php" class="logo">
                <img src="../assets/guru/img/logo/logo.png" alt="navbar brand" class="navbar-brand" height="20" />
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
    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
        data-background-color="blue">
        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                        aria-expanded="false" aria-haspopup="true">
                        <i class="fa fa-search"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-search animated fadeIn">
                        <form class="navbar-left navbar-form nav-search">
                            <div class="input-group">
                                <input type="text" placeholder="Search ..." class="form-control" />
                            </div>
                        </form>
                    </ul>
                </li>
                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                        aria-expanded="false">
                        <div class="avatar-sm avatar-img rounded-circle">
                            <img src="<?= htmlspecialchars($user_data['foto_profil']); ?>" alt="Foto Profil"
                                class="avatar-img rounded-circle" />
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hi,</span>
                            <span class="fw-bold"><?= htmlspecialchars($user_data['nama']); ?></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg avatar-img rounded">
                                        <img src="<?= htmlspecialchars($user_data['foto_profil']); ?>" alt="Foto Profil"
                                            class="avatar-img rounded" />
                                    </div>
                                    <div class="u-text">
                                        <h4><?= htmlspecialchars($user_data['nama']); ?></h4>
                                        <p class="text-muted"><?= htmlspecialchars($user_data['email']); ?></p>
                                    </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href=".?hal=profile">Profil</a>
                                <a class="dropdown-item" href="../index.php">Keluar</a>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>
