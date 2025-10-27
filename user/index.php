<?php
session_start();

if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true || $_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit();
}

$nama_user = $_SESSION['nama'];
$tipe_user = $_SESSION['tipe_user'];

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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link
        rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link href="../assets/user/css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/user/css/style.css">
</head>

<body style="background-color:rgb(245, 245, 245);">

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                <img src="../assets/user/img/header.png" alt="NeoBlue Logo" style="height: 32px; margin: 10px 0px;">
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
                <a href=".?hal=notifikasi" class="btn rounded-pill position-relative"
                    style="color: white; border: none;">
                    <i class="bi bi-bell-fill" style="font-size: 19px;"></i>
                </a>
                <a href=".?hal=profile" class="btn rounded-pill" style="color: white; border: none;">
                    <i class="bi bi-person-circle" style="font-size: 19px;"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="start">
        <?php
        $hal = @$_GET['hal'];
        $materi = 'pages/materi.php';
        $p = "pages/$hal.php";
        if (!empty($hal) && file_exists($p)) {
            include "$p";
        } else {
            include "$materi";
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
    <script>
        // Ambil elemen-elemen yang dibutuhkan
        const showListBtn = document.getElementById('showListBtn');
        const showCardBtn = document.getElementById('showCardBtn');
        const listView = document.getElementById('listView');
        const cardView = document.getElementById('cardView');

        // Event listener untuk tombol List View
        showListBtn.addEventListener('click', () => {
            // Tampilkan List View dan sembunyikan Card View
            listView.style.display = 'block';
            cardView.style.display = 'none';

            // Atur status 'active' pada tombol
            showListBtn.classList.add('active');
            showCardBtn.classList.remove('active');
        });

        // Event listener untuk tombol Card View
        showCardBtn.addEventListener('click', () => {
            // Tampilkan Card View dan sembunyikan List View
            cardView.style.display = 'block';
            listView.style.display = 'none';

            // Atur status 'active' pada tombol
            showCardBtn.classList.add('active');
            showListBtn.classList.remove('active');
        });
    </script>
</body>

</html>
