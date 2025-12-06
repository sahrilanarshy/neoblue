<?php
session_start();
// 1. Validasi sesi: Jika tidak login atau role bukan siswa, redirect ke halaman login.
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true || $_SESSION['role'] !== 'siswa') {
    header('Location: ../login.php');
    exit();
}
// 2. Tentukan halaman yang akan ditampilkan. Default ke 'materi'.
$hal = $_GET['hal'] ?? 'materi';

// Sertakan file koneksi database agar variabel $koneksi tersedia
include '../config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<script async src="https://www.googletagmanager.com/gtag/js?id=G-EHWNSZCDXT"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'G-EHWNSZCDXT');
</script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/user/img/profile_neoblue.png" type="image/x-icon">
    <title>NeoBlue</title>
    <!-- 3. Memperbaiki link stylesheet yang rusak dan merapikan pemanggilan aset -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link href="../assets/user/css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/user/css/style.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                <img src="../assets/user/img/logo1.png" style="height: 32px; margin: 10px 0px;">
            </a>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="menu nav-link fw-bold" href=".?hal=materi">Materi</a></li>
                    <li class="nav-item"><a class="menu nav-link fw-bold" href=".?hal=habit">Habit Harian</a></li>
                    <li class="nav-item"><a class="menu nav-link fw-bold" href=".?hal=short">Short</a></li>
                    <li class="nav-item"><a class="menu nav-link fw-bold" href=".?hal=tryout">Tryout</a></li>
                    <li class="nav-item"><a class="menu nav-link fw-bold" href=".?hal=jadwal">Jadwal</a></li>
                </ul>
            </div>
            <div>
                <a href=".?hal=notifikasi" class="btn btn-icon-only rounded-pill position-relative">
                    <i class="bi bi-bell-fill"></i>
                    <span id="notification-dot" class="notification-dot"></span>
                </a>
                <a href=".?hal=profile" class="btn btn-icon-only rounded-pill">
                    <i class="bi bi-person-circle"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="start">
        <?php
        // 4. Menyederhanakan logika inklusi file halaman
        $p = "pages/$hal.php";
        if (file_exists($p)) {
            include $p;
        }
        ?>
    </div>

    <div class="bottombar">
        <ul class="navbar-nav d-flex flex-row justify-content-between w-100">
            <li class="nav-item"><a class="nav-link" href=".?hal=materi"><i
                        class="bi bi-file-earmark-text"></i>Materi</a></li>
            <li class="nav-item"><a class="nav-link" href=".?hal=habit"><i class="bi bi-calendar-check"></i>Habit</a>
            </li>
            <li class="nav-item"><a class="nav-link" href=".?hal=short"><i class="bi bi-play-btn"></i>Short</a></li>
            <li class="nav-item"><a class="nav-link" href=".?hal=tryout"><i class="bi bi-clipboard"></i>Tryout</a></li>
            <li class="nav-item"><a class="nav-link" href=".?hal=jadwal"><i class="bi bi-calendar-event"></i>Jadwal</a>
            </li>
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/user/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationDot = document.getElementById('notification-dot');

            async function checkNewNotifications() {
                try {
                    const response = await fetch('../api/api_check_new_notifications.php');
                    const result = await response.json();

                    if (result.new_notification) {
                        notificationDot.classList.add('show');
                    } else {
                        notificationDot.classList.remove('show');
                    }
                } catch (error) {
                    console.error('Gagal memeriksa notifikasi baru:', error);
                }
            }

            // Cek notifikasi saat halaman dimuat
            checkNewNotifications();
        });
    </script>
</body>

</html>
