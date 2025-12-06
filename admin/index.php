<?php
session_start();

if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

ob_start();

// Logika routing dipindahkan ke atas sebelum output HTML
$hal = $_GET['hal'] ?? 'beranda';
$page_to_include = '';

switch ($hal) {
    case 'beranda':
        $page_to_include = 'pages/beranda.php';
        break;
    case 'editfitur':
        $page_to_include = 'pages/editfitur.php';
        break;
    case 'editpaket':
        $page_to_include = 'pages/editpaket.php';
        break;
    case 'edituser':
        $page_to_include = 'pages/edituser.php';
        break;
    case 'fitur':
        $page_to_include = 'pages/fitur.php';
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
    case 'paket':
        $page_to_include = 'pages/paket.php';
        break;
    case 'profile':
        $page_to_include = 'pages/profile.php';
        break;
    case 'artikel':
        $page_to_include = 'pages/artikel.php';
        break;
    case 'editartikel':
        $page_to_include = 'pages/editartikel.php';
        break;
    case 'tambahartikel':
        $page_to_include = 'pages/tambahartikel.php';
        break;
    case 'proses_hapus_artikel':
        $page_to_include = 'pages/proses_hapus_artikel.php';
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
    // Proses CRUD Fitur
    case 'proses_simpan_fitur':
        $page_to_include = 'pages/proses_simpan_fitur.php';
        break;
    // Proses CRUD
    case 'proses_update_fitur':
        $page_to_include = 'pages/proses_update_fitur.php';
        break;
    case 'proses_hapus_fitur':
        $page_to_include = 'pages/proses_hapus_fitur.php';
        break;
    // Proses CRUD Paket
    case 'proses_simpan_paket':
        $page_to_include = 'pages/proses_simpan_paket.php';
        break;
    case 'proses_update_paket':
        $page_to_include = 'pages/proses_update_paket.php';
        break;
    case 'proses_hapus_paket':
        $page_to_include = 'pages/proses_hapus_paket.php';
        break;
    case 'tambahuser':
        $page_to_include = 'pages/tambahuser.php';
        break;
    case 'user':
        $page_to_include = 'pages/user.php';
        break;
    case 'userpremium':
        $page_to_include = 'pages/userpremium.php';
        break;
    case 'proses_hapus_user':
        $page_to_include = 'pages/proses_hapus_user.php';
        break;
    case 'proses_update_langganan':
        $page_to_include = 'pages/proses_update_langganan.php';
        break;
    case 'proses_cek_kadaluarsa':
        $page_to_include = 'pages/proses_cek_kadaluarsa.php';
        break;
    case 'edituserpremium':
        $page_to_include = 'pages/edituserpremium.php';
        break;
    case 'guru':
        $page_to_include = 'pages/guru.php';
        break;
    case 'tambahguru':
        $page_to_include = 'pages/tambahguru.php';
        break;
    case 'proses_hapus_guru':
        $page_to_include = 'pages/proses_hapus_guru.php';
        break;
    case 'editguru':
        $page_to_include = 'pages/editguru.php';
        break;
    case 'proses_update_status_pesan':
        $page_to_include = 'pages/proses_update_status_pesan.php';
        break;
    case 'proses_hapus_pesan':
        $page_to_include = 'pages/proses_hapus_pesan.php';
        break;
    case 'proses_konfirmasi_pembayaran':
        $page_to_include = 'pages/proses_konfirmasi_pembayaran.php';
        break;
    case 'konfirmasi':
        $page_to_include = 'pages/konfirmasi.php';
        break;
    case 'riwayat':
        $page_to_include = 'pages/riwayat.php';
        break;
    case 'pendapatan':
        $page_to_include = 'pages/pendapatan.php';
        break;
    case 'metodepembayaran':
        $page_to_include = 'pages/metodepembayaran.php';
        break;
    case 'tambahmetodepembayaran':
        $page_to_include = 'pages/tambahmetodepembayaran.php';
        break;
    case 'editmetodepembayaran':
        $page_to_include = 'pages/editmetodepembayaran.php';
        break;
    case 'proses_hapus_metode':
        $page_to_include = 'pages/proses_hapus_metode.php';
        break;
    case 'export_pendapatan':
        $page_to_include = 'pages/export_pendapatan.php';
        break;
    case 'export_pendapatan_pdf':
        $page_to_include = 'pages/export_pendapatan_pdf.php';
        break;
    case 'proses_hapus_pembayaran':
        $page_to_include = 'pages/proses_hapus_pembayaran.php';
        break;
    default:
        $page_to_include = 'pages/beranda.php';
}

// Jika halaman adalah skrip ekspor, langsung include dan hentikan eksekusi
if ($hal == 'export_pendapatan' || $hal == 'export_pendapatan_pdf') {
    if (file_exists($page_to_include)) {
        include $page_to_include;
    } else {
        die("File ekspor tidak ditemukan.");
    }
    exit(); // Hentikan eksekusi agar tidak ada output HTML lain
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>NeoBlue</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="../assets/admin/img/logo/icon.png" type="image/x-icon" />
    <script src="../assets/admin/js/tinymce/js/tinymce/tinymce.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="wrapper">
        <?php include 'layout/sidebar.php'; ?>
        <div class="main-panel">
            <?php include 'layout/navbar.php'; ?>
            <div class="container">
                <?php
                if (file_exists($page_to_include)) {
                    include $page_to_include;
                } else {

                    echo "<h4>Error: Halaman tidak ditemukan.</h4>";
                    echo "<p>File <b>" . htmlspecialchars($page_to_include) . "</b> tidak ada di server.</p>";
                }
                ?>
            </div>
            <?php include 'layout/footer.php'; ?>
        </div>
    </div>

    <?php include 'layout/delete_modal.php'; ?>
    <?php include 'layout/action_confirm_modal.php'; ?>
    <?php include 'pages/sukses_gagal_modal.php'; ?>

    <script>
        // Fungsi untuk memeriksa status login dan role melalui API
        async function verifyUserRole() {
            try {
                const response = await fetch('../api/api_role.php');
                const result = await response.json();

                // Jika status bukan success atau role tidak sesuai, redirect ke login
                if (result.status !== 'success' || result.data.role !== 'admin') {
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
<?php ob_end_flush(); ?>