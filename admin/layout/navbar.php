        <div class="main-header">
            <div class="main-header-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="blue">
                    <a href="index.php" class="logo">
                        <img src="../assets/admin/img/logo/logo.png" alt="navbar brand" class="navbar-brand"
                            height="20" />
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
                                <div class="avatar-sm">
                                    <img src="<?= $_SESSION['foto_profil'] ? '../' . htmlspecialchars($_SESSION['foto_profil']) : '../assets/admin/img/logo/icon profile.png' ?>" alt="..."
                                        class="avatar-img rounded-circle" />
                                </div>
                                <span class="profile-username">
                                    <span class="op-7">Hi,</span>
                                    <span class="fw-bold"><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin'); ?></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="avatar-lg">
                                                <img src="<?= $_SESSION['foto_profil'] ? '../' . htmlspecialchars($_SESSION['foto_profil']) : '../assets/admin/img/logo/icon profile.png' ?>" alt="image profile"
                                                    class="avatar-img rounded" />
                                            </div>
                                            <div class="u-text">
                                                <h4><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin'); ?></h4>
                                                <p class="text-muted"><?= htmlspecialchars($_SESSION['email'] ?? 'email@example.com'); ?></p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href=".?hal=profile">My Profile</a>
                                        <a class="dropdown-item" href="../index.php">Logout</a>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
<?php
// To ensure the profile picture is always up-to-date in the session without modifying the login API,
// we can fetch it once if it's not set.
if (!isset($_SESSION['foto_profil'])) {
    include_once '../config/koneksi.php';
    $user_id_for_photo = $_SESSION['user_id'] ?? 0;
    if ($user_id_for_photo > 0 && isset($koneksi)) {
        $stmt = mysqli_prepare($koneksi, "SELECT foto_profil FROM users WHERE id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $user_id_for_photo);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($user_photo_data = mysqli_fetch_assoc($result)) {
                $_SESSION['foto_profil'] = $user_photo_data['foto_profil'];
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>