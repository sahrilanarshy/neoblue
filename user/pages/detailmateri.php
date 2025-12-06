<?php
// 1. Ambil ID materi dari URL dan validasi
$materi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($materi_id <= 0) {
    echo "<p class='text-center text-danger'>ID Materi tidak valid.</p>";
    return; // Hentikan eksekusi jika ID tidak valid
}

// 2. Ambil data materi dari database menggunakan prepared statement
// Variabel $koneksi sudah tersedia dari index.php
$stmt = mysqli_prepare($koneksi, "SELECT judul, link, deskripsi, tipe FROM materi WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $materi_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$materi = mysqli_fetch_assoc($result);

if (!$materi) {
    echo "<p class='text-center text-danger'>Materi tidak ditemukan.</p>";
    return; // Hentikan eksekusi jika materi tidak ditemukan
}

// 3. Cek hak akses pengguna
$is_premium_user = (isset($_SESSION['tipe_user']) && $_SESSION['tipe_user'] == 'premium');
if ($materi['tipe'] == 'premium' && !$is_premium_user) {
    // Jika materi premium dan user bukan premium, blokir akses
    echo "<script>
            alert('Akses ditolak. Silakan upgrade ke akun Premium untuk mengakses materi ini.');
            window.location.href = '.?hal=premium';
          </script>";
    return; // Hentikan eksekusi
}

/**
 * Mengubah URL YouTube biasa menjadi URL embed.
 * @param string $url URL YouTube.
 * @return string|null URL embed atau null jika tidak valid.
 */
function getYouTubeEmbedUrl($url) {
    $embedUrl = null;
    $regex = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
    preg_match($regex, $url, $matches);

    if (isset($matches[1]) && !empty($matches[1])) {
        $videoId = $matches[1];
        $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
    }
    return $embedUrl;
}

$video_embed_url = getYouTubeEmbedUrl($materi['link']);
$is_direct_video = !$video_embed_url && filter_var($materi['link'], FILTER_VALIDATE_URL) && in_array(pathinfo($materi['link'], PATHINFO_EXTENSION), ['mp4', 'webm', 'ogg']);

?>
<main class="dashboard-content">
    <div class="habit-detail-container">
        
        <h1 class="page-title" style="font-size: 24px; margin-bottom: 1.5rem;"><?= htmlspecialchars($materi['judul']); ?></h1>

        <div class="video-player-wrapper" style="aspect-ratio: 16 / 9; background-color: #000; border-radius: 0.75rem; overflow: hidden;">
            <?php if ($video_embed_url): ?>
                <iframe src="<?= htmlspecialchars($video_embed_url); ?>" style="width:100%; height:100%; border:0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <?php elseif ($is_direct_video): ?>
                <video controls style="width: 100%; border-radius: 0.75rem;">
                    <source src="<?= htmlspecialchars($materi['link']); ?>" type="video/mp4">
                    Browser Anda tidak mendukung tag video.
                </video>
            <?php else: ?>
                <p class="text-center text-muted">Video untuk materi ini tidak tersedia.</p>
            <?php endif; ?>
        </div>

        <article class="reading-content" style="margin-top: 1.5rem;">
            <?= $materi['deskripsi']; // Tidak menggunakan htmlspecialchars karena konten ini dari editor TinyMCE ?>
        </article>

    </div>
</main>