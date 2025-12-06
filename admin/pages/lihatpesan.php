<?php
include '../config/koneksi.php';
$id = $_GET['id'] ?? 0;

if ($id == 0) {
    $_SESSION['gagal'] = "ID Pesan tidak valid.";
    header("Location: ./?hal=kontak");
    exit();
}

// Ambil data pesan
$query = mysqli_query($koneksi, "SELECT * FROM kontak WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    $_SESSION['gagal'] = "Pesan tidak ditemukan.";
    header("Location: ./?hal=kontak");
    exit();
}

// Jika status pesan 'Baru', update menjadi 'Sudah Dibaca'
if ($data['status'] == 'Baru') {
    mysqli_query($koneksi, "UPDATE kontak SET status = 'Sudah Dibaca' WHERE id = '$id'");
    // Refresh data untuk mendapatkan status terbaru (opsional, karena tidak ditampilkan di halaman ini)
    $data['status'] = 'Sudah Dibaca';
}

?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Manajemen Pesan</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href=".?hal=beranda">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href=".?hal=kontak">Daftar Pesan Masuk</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Lihat Pesan</a>
            </li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Detail Pesan Masuk</h4>
                        <a href=".?hal=kontak" class="btn btn-secondary btn-round ms-auto">
                            <i class="fa fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <div class="message-info mb-3">
                        <p><strong>Tanggal:</strong> <?= date('d M Y, H:i', strtotime($data['tanggal_kirim'])); ?></p>
                        <p><strong>Pengirim:</strong> <?= htmlspecialchars($data['nama']); ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($data['email']); ?></p>
                        <p><strong>Subjek:</strong> <?= htmlspecialchars($data['subjek']); ?></p>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label class="fw-bold">Isi Pesan:</label>
                        <div class="message-display-field ps-2">
                            <?= nl2br(htmlspecialchars($data['pesan'])); ?>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <a href="mailto:<?= htmlspecialchars($data['email']); ?>?subject=Re: <?= htmlspecialchars($data['subjek']); ?>" class="btn btn-primary">
                            <i class="fa fa-reply"></i> Balas via Email
                        </a>
                        <a href=".?hal=proses_update_status_pesan&id=<?= $data['id']; ?>&status=Sudah Dibalas" class="btn btn-info" onclick="return confirm('Anda yakin ingin menandai pesan ini sudah dibalas?');">
                            Tandai Sudah Dibalas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
