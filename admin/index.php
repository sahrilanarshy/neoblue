<?php
session_start();

if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="../assets/admin/img/logo/logo neoblue.png" type="image/x-icon" />
    <script src="../assets/admin/js/tinymce/js/tinymce/tinymce.min.js"></script>
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
    <script src="../assets/admin/js/plugin/webfont/webfont.min.js"></script>
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
                urls: ["../assets/admin/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <link rel="stylesheet" href="../assets/admin/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/admin/css/plugins.min.css" />
    <link rel="stylesheet" href="../assets/admin/css/kaiadmin.min.css" />

    <link rel="stylesheet" href="../assets/admin/css/demo.css" />
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
                    case 'coba':
                        $page_to_include = 'pages/coba.php';
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
                    case 'editpaket':
                        $page_to_include = 'pages/editpaket.php';
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
                    case 'fitur':
                        $page_to_include = 'pages/fitur.php';
                        break;
                    case 'habit':
                        $page_to_include = 'pages/habit.php';
                        break;
                    case 'hapusmateri':
                        $page_to_include = 'pages/hapusmateri.php';
                        break;
                    case 'kontak':
                        $page_to_include = 'pages/kontak.php';
                        break;
                    case 'langganan':
                        $page_to_include = 'pages/langganan.php';
                        break;
                    case 'lihatpesan':
                        $page_to_include = 'pages/lihatpesan.php';
                        break;
                    case 'materi':
                        $page_to_include = 'pages/materi.php';
                        break;
                    case 'paket':
                        $page_to_include = 'pages/paket.php';
                        break;
                    case 'profile':
                        $page_to_include = 'pages/profile.php';
                        break;
                    case 'short':
                        $page_to_include = 'pages/short.php';
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
                    case 'tambahfitur':
                        $page_to_include = 'pages/tambahfitur.php';
                        break;
                    case 'tambahmateri':
                        $page_to_include = 'pages/tambahmateri.php';
                        break;
                    case 'tambahpaket':
                        $page_to_include = 'pages/tambahpaket.php';
                        break;
                    case 'tambahshort':
                        $page_to_include = 'pages/tambahshort.php';
                        break;
                    case 'tambahsoal':
                        $page_to_include = 'pages/tambahsoal.php';
                        break;
                    case 'tambahsoaltryout':
                        $page_to_include = 'pages/tambahsoaltryout.php';
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
                    case 'tryout':
                        $page_to_include = 'pages/tryout.php';
                        break;
                    case 'user':
                        $page_to_include = 'pages/user.php';
                        break;

                    default:
                        $page_to_include = 'pages/beranda.php';
                }

                // 3. Include file HANYA jika file-nya ada
                if (file_exists($page_to_include)) {
                    include $page_to_include;
                } else {
                    // Tampilkan pesan error jika file tidak ditemukan
                    echo "<h4>Error: Halaman tidak ditemukan.</h4>";
                    echo "<p>File <b>" . htmlspecialchars($page_to_include) . "</b> tidak ada di server.</p>";
                }
                ?>
            </div>
            <?php include 'layout/footer.php'; ?>
        </div>
    </div>
    </div>
</body>

</html>
<?php ob_end_flush(); ?>