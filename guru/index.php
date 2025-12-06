<?php
session_start();

include '../config/koneksi.php';
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true || $_SESSION['role'] !== 'guru') {
    header('Location: ../login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>NeoBlue</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="../assets/guru/img/logo/icon.png" type="image/x-icon" />
    <script src="../assets/guru/js/tinymce/js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#editor',
            license_key: 'gpl',
            plugins: 'lists link image table code',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code',
            menubar: true,
            height: 400
        });
    </script>
    <script src="../assets/guru/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["../assets/guru/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <link rel="stylesheet" href="../assets/guru/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/guru/css/plugins.min.css" />
    <link rel="stylesheet" href="../assets/guru/css/kaiadmin.min.css" />

    <link rel="stylesheet" href="../assets/guru/css/demo.css" />
</head>

<body>
    <div class="wrapper">
        <?php include 'layout/sidebar.php'; ?>
        <div class="main-panel">
            <?php include 'layout/navbar.php'; ?>
            <div class="container">
                <?php
                $hal = $_GET['hal'] ?? 'beranda';
                switch ($hal) {
                    case 'beranda':
                        $page_to_include = 'pages/beranda.php';
                        break;
                    case 'editbacaan':
                        $page_to_include = 'pages/editbacaan.php';
                        break;
                    case 'editfitur':
                        $page_to_include = 'pages/editfitur.php';
                        break;
                    case 'editmateri':
                        $page_to_include = 'pages/editmateri.php';
                        break;
                    case 'editshort':
                        $page_to_include = 'pages/editshort.php';
                        break;
                    case 'editsoal':
                        $page_to_include = 'pages/editsoal.php';
                        break;
                    case 'editsubtest':
                        $page_to_include = 'pages/editsubtest.php';
                        break;
                    case 'edittryout':
                        $page_to_include = 'pages/edittryout.php';
                        break;
                    case 'edituser':
                        $page_to_include = 'pages/edituser.php';
                        break;
                    case 'habit':
                        $page_to_include = 'pages/habit.php';
                        break;
                    case 'proses_habit':
                        $page_to_include = 'pages/proses_habit.php';
                        break;
                    case 'materi':
                        $page_to_include = 'pages/materi.php';
                        break;
                    case 'profile':
                        $page_to_include = 'pages/profile.php';
                        break;
                    case 'proses_profile':
                        $page_to_include = 'pages/proses_profile.php';
                        break;
                    case 'short':
                        $page_to_include = 'pages/short.php';
                        break;
                    case 'proses_short':
                        $page_to_include = 'pages/proses_short.php';
                        break;
                    case 'soaltryout':
                        $page_to_include = 'pages/soaltryout.php';
                        break;
                    case 'subtest':
                        $page_to_include = 'pages/subtest.php';
                        break;
                    case 'tambahbacaan':
                        $page_to_include = 'pages/tambahbacaan.php';
                        break;
                    case 'tambahmateri':
                        $page_to_include = 'pages/tambahmateri.php';
                        break;
                    case 'proses_materi':
                        $page_to_include = 'pages/proses_materi.php';
                        break;
                    case 'tambahshort':
                        $page_to_include = 'pages/tambahshort.php';
                        break;
                    case 'proses_subtest':
                        $page_to_include = 'pages/proses_subtest.php';
                        break;
                    case 'tambahsoal':
                        $page_to_include = 'pages/tambahsoal.php';
                        break;
                    case 'tambahsoaltryout':
                        $page_to_include = 'pages/tambahsoaltryout.php';
                        break;
                    case 'proses_soal_tryout':
                        $page_to_include = 'pages/proses_soal_tryout.php';
                        break;
                    case 'api_soaltryout':
                        $page_to_include = '../api/api_soaltryout.php';
                        break;
                    case 'editsoaltryout':
                        $page_to_include = 'pages/editsoaltryout.php';
                        break;
                    case 'tambahsubtest':
                        $page_to_include = 'pages/tambahsubtest.php';
                        break;
                    case 'tambahtryout':
                        $page_to_include = 'pages/tambahtryout.php';
                        break;
                    case 'tambahuser':
                        $page_to_include = 'pages/tambahuser.php';
                        break;
                    case 'proses_tryout':
                        $page_to_include = 'pages/proses_tryout.php';
                        break;
                    case 'tryout':
                        $page_to_include = 'pages/tryout.php';
                        break;
                    case 'user':
                        $page_to_include = 'pages/user.php';
                        break;
                    case 'pengumuman':
                        $page_to_include = 'pages/pengumuman.php';
                        break;
                    case 'tambahpengumuman':
                        $page_to_include = 'pages/tambahpengumuman.php';
                        break;
                    case 'editpengumuman':
                        $page_to_include = 'pages/editpengumuman.php';
                        break;
                    case 'proses_pengumuman':
                        $page_to_include = 'pages/proses_pengumuman.php';
                        break;
                    case 'riwayat_tryout':
                        $page_to_include = 'pages/riwayat_tryout.php';
                        break;
                    case 'hapusriwayattryout':
                        $page_to_include = 'pages/hapusriwayattryout.php';

                    default:
                        $page_to_include = 'pages/beranda.php';
                }
                
                // 3. Include file HANYA jika file-nya ada
                if (file_exists($page_to_include)) {
                    include $page_to_include;
                } else {
                    // Tampilkan pesan error jika file tidak ditemukan
                    echo '<h4>Error: Halaman tidak ditemukan.</h4>';
                    echo '<p>File <b>' . htmlspecialchars($page_to_include) . '</b> tidak ada di server.</p>';
                }
                ?>
            </div>
            <?php include 'layout/footer.php'; ?>
        </div>
    </div>

    <script>
        // Fungsi untuk memeriksa status login dan role melalui API
        async function verifyUserRole() {
            try {
                const response = await fetch('../api/api_role.php');
                const result = await response.json();

                // Jika status bukan success atau role tidak sesuai, redirect ke login
                if (result.status !== 'success' || result.data.role !== 'guru') {
                    window.location.href = '../login.php';
                }
            } catch (error) {
                // Jika ada error koneksi, redirect juga untuk keamanan
                console.error('API call failed:', error);
                window.location.href = '../login.php';
            }
        }

        // Panggil fungsi ini saat halaman dimuat
        document.addEventListener('DOMContentLoaded', verifyUserRole);
    </script>
</body>

</html>
