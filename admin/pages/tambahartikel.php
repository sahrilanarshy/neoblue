<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../config/koneksi.php';

    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $tanggal_publikasi = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $konten = mysqli_real_escape_string($koneksi, $_POST['konten']);

    if (empty(trim($konten))) {
        $_SESSION['gagal'] = "Isi artikel tidak boleh kosong.";
        header("Location: ./?hal=tambahartikel");
        exit();
    }

    $gambar_path = '';

    // Proses upload gambar jika ada
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "uploads/artikel/";
        if (!file_exists('../' . $target_dir)) {
            mkdir('../' . $target_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES["gambar"]["name"]);
        $target_file = '../' . $target_dir . $file_name;

        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar_path = $target_dir . $file_name;
        } else {
            $_SESSION['gagal'] = "Gagal mengunggah gambar.";
            header("Location: ./?hal=tambahartikel");
            exit();
        }
    }

    $query = "INSERT INTO artikel (judul, kategori, tanggal_publikasi, gambar, konten) VALUES ('$judul', '$kategori', '$tanggal_publikasi', '$gambar_path', '$konten')";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['sukses'] = "Artikel berhasil ditambahkan.";
        header("Location: ./?hal=artikel");
        exit();
    } else {
        $_SESSION['gagal'] = "Gagal menambahkan artikel: " . mysqli_error($koneksi);
        if (!empty($gambar_path) && file_exists('../' . $gambar_path)) {
            unlink('../' . $gambar_path);
        }
        header("Location: ./?hal=tambahartikel");
        exit();
    }
}
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Artikel</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href=".?hal=artikel">Daftar Artikel</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Tambah Artikel</a></li>
        </ul>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Artikel</h4>
                </div>

                <div class="card-body">

                    <form action="?hal=tambahartikel" method="POST" enctype="multipart/form-data">

                        <div class="form-group mb-3">
                            <label for="judul">Judul Artikel</label>
                            <input type="text" class="form-control" id="judul" name="judul"
                                placeholder="Masukkan judul artikel" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="kategori">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Tips Belajar">Tips Belajar</option>
                                <option value="Informasi Kampus">Informasi Kampus</option>
                                <option value="Motivasi">Motivasi</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tanggal">Tanggal Publikasi</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="gambar">Gambar Artikel</label>
                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" />
                            <small class="form-text text-muted d-block">Gunakan format JPG/PNG, ukuran maks. 2MB</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="konten">Isi Artikel</label>
                            <textarea class="form-control" id="editor" name="konten" rows="6" placeholder="Tulis isi artikel di sini..."></textarea>
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
