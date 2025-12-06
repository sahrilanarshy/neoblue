<?php
// Halaman detail artikel: tampilkan judul, kategori, tanggal, gambar, dan konten penuh
include 'config/koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
	http_response_code(404);
	echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Artikel tidak ditemukan</title></head><body><h1>Artikel tidak ditemukan</h1><p>ID artikel tidak valid.</p></body></html>';
	exit;
}

$sql = "SELECT id, judul, kategori, tanggal_publikasi, gambar, konten FROM artikel WHERE id = '$id' LIMIT 1";
$res = mysqli_query($koneksi, $sql);
if (! $res || mysqli_num_rows($res) === 0) {
	http_response_code(404);
	echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Artikel tidak ditemukan</title></head><body><h1>Artikel tidak ditemukan</h1><p>Artikel dengan ID tersebut tidak ada.</p></body></html>';
	exit;
}

$row = mysqli_fetch_assoc($res);
$judul = htmlspecialchars($row['judul']);
$kategori = htmlspecialchars($row['kategori']);
$tanggal = !empty($row['tanggal_publikasi']) ? date('d M Y', strtotime($row['tanggal_publikasi'])) : '';
$gambar = !empty($row['gambar']) ? $row['gambar'] : 'assets/landingpage/img/artikel/default.jpg';

// Konten: tampilkan HTML yang disimpan di DB. Untuk keamanan ringan,
// izinkan tag HTML umum saja (paragraf, heading, link, bold, img, list, br).
$allowed_tags = '<p><a><strong><b><em><i><ul><ol><li><br><img><h1><h2><h3><h4><blockquote>';
$konten = strip_tags($row['konten'], $allowed_tags);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $judul ?> - Neoblue</title>
	<link href="assets/landingpage/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/landingpage/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="assets/landingpage/css/main.css" rel="stylesheet">
</head>
<body style="padding-top:80px;">
	<div class="container">
		<a href="index.php#artikel" class="btn btn-sm btn-secondary mb-4">&larr; Kembali ke daftar artikel</a>
		<article>
			<h1 class="mb-2"><?= $judul ?></h1>
			<div class="mb-3 text-muted">
				<span class="me-3"><i class="bi bi-folder"></i> <?= $kategori ?></span>
				<span><i class="bi bi-calendar"></i> <?= $tanggal ?></span>
			</div>
			<div class="mb-4">
				<img src="<?= $gambar ?>" alt="<?= $judul ?>" class="img-fluid rounded">
			</div>
			<div class="article-content">
				<?= $konten ?>
			</div>
		</article>
	</div>

	<script src="assets/landingpage/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/landingpage/js/main.js"></script>
</body>
</html>
