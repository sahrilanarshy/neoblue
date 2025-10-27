<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Neoblue</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/landingpage/img/logo/profile_neoblue.png" rel="icon">
    <link href="assets/landingpage/img/logo/profile_neoblue.png" type="image/pngrel=" apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/landingpage/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/landingpage/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/landingpage/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/landingpage/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/landingpage/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/landingpage/css/main.css" rel="stylesheet">

</head>
<?php include 'landingpage/layout/header.php'; ?>

<body class="index-page">
    <main class="main">
        <?php
        $hal = @$_GET['hal'];
        $beranda = 'landingpage/beranda.php';
        $p = "landingpage/$hal.php";
        if (!empty($hal) && file_exists($p)) {
            include "$p";
        } else {
            include "$beranda";
        }
        ?>
    </main>
</body>
<?php include 'landingpage/layout/fouter.php'; ?>

</html>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="assets/landingpage/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/landingpage/vendor/php-email-form/validate.js"></script>
<script src="assets/landingpage/vendor/aos/aos.js"></script>
<script src="assets/landingpage/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/landingpage/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/landingpage/vendor/purecounter/purecounter_vanilla.js"></script>

<!-- Main JS File -->
<script src="assets/landingpage/js/main.js"></script>