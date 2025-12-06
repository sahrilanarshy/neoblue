<?php
include '../config/koneksi.php';
$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $tanggal_publikasi = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $konten = mysqli_real_escape_string($koneksi, $_POST['konten']);
    $gambar_lama = $_POST['gambar_lama'];
    $gambar_path = $gambar_lama;

    // Proses upload gambar baru jika ada
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "uploads/artikel/";
        if (!file_exists('../' . $target_dir)) {
            mkdir('../' . $target_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES["gambar"]["name"]);
        $target_file = '../' . $target_dir . $file_name;

        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            // Hapus gambar lama jika ada
            if (!empty($gambar_lama) && file_exists('../' . $gambar_lama)) {
                unlink('../' . $gambar_lama);
            }
            $gambar_path = $target_dir . $file_name;
        }
    }

    $query = "UPDATE artikel SET 
                judul = '$judul', 
                kategori = '$kategori', 
                tanggal_publikasi = '$tanggal_publikasi', 
                gambar = '$gambar_path', 
                konten = '$konten' 
              WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['sukses'] = "Artikel berhasil diperbarui.";
    } else {
        $_SESSION['gagal'] = "Gagal memperbarui artikel: " . mysqli_error($koneksi);
    }
    header("Location: ./?hal=artikel");
    exit();
}

// Ambil data artikel untuk ditampilkan di form
if ($id > 0) {
    $result = mysqli_query($koneksi, "SELECT * FROM artikel WHERE id = '$id'");
    $data = mysqli_fetch_assoc($result);
    if (!$data) {
        $_SESSION['gagal'] = "Artikel tidak ditemukan.";
        header("Location: ./?hal=artikel");
        exit();
    }
} else {
    $_SESSION['gagal'] = "ID Artikel tidak valid.";
    header("Location: ./?hal=artikel");
    exit();
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Artikel</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href=".?hal=beranda"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=artikel">Daftar Artikel</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Edit Artikel</a></li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Artikel</h4>
                </div>

                <div class="card-body">
                    <form action="?hal=editartikel" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $data['id']; ?>">
                        <input type="hidden" name="gambar_lama" value="<?= $data['gambar']; ?>">
                        <div class="form-group mb-3">
                            <label for="judul">Judul Artikel</label>
                            <input type="text" class="form-control" id="judul" name="judul"
                                value="<?= htmlspecialchars($data['judul']); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="kategori">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Tips Belajar" <?= $data['kategori'] == 'Tips Belajar' ? 'selected' : ''; ?>>Tips Belajar</option>
                                <option value="Informasi Kampus" <?= $data['kategori'] == 'Informasi Kampus' ? 'selected' : ''; ?>>Informasi Kampus</option>
                                <option value="Motivasi" <?= $data['kategori'] == 'Motivasi' ? 'selected' : ''; ?>>Motivasi</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tanggal">Tanggal Publikasi</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $data['tanggal_publikasi']; ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="gambar">Gambar Artikel</label>
                            <?php if (!empty($data['gambar'])) : ?>
                                <div class="mb-2">
                                    <img src="../<?= $data['gambar']; ?>" alt="Gambar saat ini" height="100" class="rounded">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                            <small class="form-text text-muted d-block">Unggah gambar baru untuk mengganti. Kosongkan jika tidak ingin diubah.</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="konten">Isi Artikel</label>
                            <textarea class="form-control" id="editor" name="konten" rows="10"><?= htmlspecialchars($data['konten']); ?></textarea>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href=".?hal=artikel" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Batal
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
