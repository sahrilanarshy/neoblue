-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 01, 2025 at 12:51 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_neoblue`
--

-- --------------------------------------------------------

--
-- Table structure for table `artikel`
--

CREATE TABLE `artikel` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `tanggal_publikasi` date NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `konten` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `artikel`
--

INSERT INTO `artikel` (`id`, `judul`, `kategori`, `tanggal_publikasi`, `gambar`, `konten`, `created_at`) VALUES
(3, 'Tips Belajar Efektif untuk Meningkatkan Konsentrasi dan Hasil Belajar', 'Tips Belajar', '2025-11-21', 'uploads/artikel/1764131828_artikel1.jpg', '<p style=\"text-align: justify; line-height: 1.5;\">Belajar yang efektif tidak hanya bergantung pada lamanya waktu yang dihabiskan, tetapi juga pada strategi yang digunakan. Untuk mendapatkan hasil optimal, penting untuk memahami cara belajar yang sesuai dengan kebutuhan dan ritme pribadi. Pertama, buat jadwal belajar yang teratur agar otak terbiasa bekerja pada waktu tertentu dan tidak mudah terdistraksi. Kedua, fokuslah pada satu materi dalam satu sesi (single-tasking) karena berpindah-pindah tugas justru menurunkan kualitas pemahaman. Ketiga, gunakan metode aktif seperti membuat ringkasan, mengerjakan latihan soal, atau mengajarkan materi kepada orang lain agar otak lebih terlibat dalam proses belajar. Selain itu, pastikan lingkungan belajar mendukung&mdash;hindari suara bising, atur pencahayaan yang baik, dan jauhkan ponsel jika tidak diperlukan. Jangan lupa menjaga kesehatan fisik dengan istirahat cukup, makan teratur, serta melakukan peregangan agar otak tetap segar. Dengan menerapkan tips-tips tersebut secara konsisten, proses belajar akan menjadi lebih menyenangkan sekaligus menghasilkan peningkatan pemahaman dan prestasi.</p>', '2025-11-21 02:14:22');

-- --------------------------------------------------------

--
-- Table structure for table `fitur`
--

CREATE TABLE `fitur` (
  `id` int NOT NULL,
  `nama_fitur` varchar(255) NOT NULL,
  `urutan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `fitur`
--

INSERT INTO `fitur` (`id`, `nama_fitur`, `urutan`) VALUES
(2, 'Akses Liveclass & Rekaman', 20),
(3, 'Akses Smart Scrolling (100+)', 30),
(4, 'Akses Habit Harian (1000+ Soal)', 40),
(5, 'Akses Tryout Rutin (20x)', 50),
(6, 'Akses Pembahasan Tryout (20x)', 60),
(7, 'Akses Tracker Kemajuan', 70),
(8, 'Grup Komunitas (Jalur Langit & Discord)', 80),
(9, 'Akses Mentoring Persiapan', 90),
(10, 'Akses Sampai Mei 2026', 100);

-- --------------------------------------------------------

--
-- Table structure for table `habit_harian`
--

CREATE TABLE `habit_harian` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `subtest_id` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis` enum('bacaan','soal') COLLATE utf8mb4_general_ci NOT NULL,
  `isi_bacaan` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `habit_harian`
--

INSERT INTO `habit_harian` (`id`, `tanggal`, `subtest_id`, `judul`, `jenis`, `isi_bacaan`, `created_at`, `updated_at`) VALUES
(71, '2025-11-29', 7, 'Transformasi Pendidikan di Era Kecerdasan Buatan: Peluang dan Tantangan', 'bacaan', '<p style=\"text-align: justify;\" data-path-to-node=\"8\">Kehadiran kecerdasan buatan atau <em>Artificial Intelligence</em> (AI) telah merambah berbagai sektor, termasuk dunia pendidikan. Fenomena ini bukan lagi sekadar wacana futuristik, melainkan realitas yang sedang dihadapi oleh guru dan siswa saat ini. Integrasi AI dalam pembelajaran menawarkan janji personalisasi yang belum pernah terjadi sebelumnya. Sistem pembelajaran adaptif berbasis AI mampu menganalisis kecepatan belajar, kekuatan, dan kelemahan setiap siswa secara <em>real-time</em>, sehingga materi dapat disesuaikan dengan kebutuhan individu, bukan lagi menggunakan pendekatan \"satu ukuran untuk semua\".</p>\r\n<p style=\"text-align: justify;\" data-path-to-node=\"9\">Di satu sisi, pemanfaatan teknologi ini membawa angin segar bagi efisiensi administrasi sekolah. Tugas-tugas repetitif seperti penilaian soal pilihan ganda atau penjadwalan kelas dapat diotomatisasi, memberikan ruang lebih bagi pendidik untuk fokus pada aspek pedagogis dan emosional siswa. Selain itu, AI dapat menjadi asisten belajar virtual yang tersedia 24 jam bagi siswa, membantu menjawab pertanyaan dasar atau memberikan latihan tambahan tanpa harus menunggu jam sekolah bermula.</p>\r\n<p style=\"text-align: justify;\" data-path-to-node=\"10\">Namun, integrasi AI bukannya tanpa tantangan yang serius. Isu etika akademik menjadi sorotan utama, terutama berkaitan dengan orisinalitas karya siswa. Kemudahan menghasilkan esai atau jawaban tugas melalui <em>Generative AI</em> memicu kekhawatiran akan menumpulnya kemampuan berpikir kritis dan kreativitas manusia. Belum lagi masalah kesenjangan digital; sekolah di daerah terpencil mungkin akan semakin tertinggal jika infrastruktur teknologi tidak merata. Selain itu, ketergantungan berlebihan pada algoritma dikhawatirkan dapat mengikis interaksi sosial autentik antara guru dan murid yang merupakan fondasi pendidikan karakter.</p>\r\n<p style=\"text-align: justify;\" data-path-to-node=\"11\">Oleh karena itu, adopsi AI dalam pendidikan harus dilakukan dengan pendekatan yang bijak dan terukur. Teknologi harus diposisikan sebagai alat bantu (katalisator), bukan pengganti peran guru. Literasi digital dan etika penggunaan AI perlu dimasukkan ke dalam kurikulum agar siswa tidak hanya menjadi konsumen teknologi yang pasif, tetapi juga pengguna yang cerdas dan bertanggung jawab. Masa depan pendidikan tidak terletak pada kecanggihan mesin semata, melainkan pada kolaborasi harmonis antara kecerdasan buatan dan kearifan manusia.</p>', '2025-11-29 07:22:52', '2025-11-29 07:33:48'),
(72, '2025-11-29', 7, 'Latihan Soal Literasi - AI dalam Pendidikan', 'soal', NULL, '2025-11-29 07:32:56', '2025-11-29 07:32:56'),
(74, '2025-12-04', 7, 'Literasi Bahasa Indonesia', 'bacaan', '<p>p</p>', '2025-12-01 04:14:04', '2025-12-01 04:14:04');

-- --------------------------------------------------------

--
-- Table structure for table `habit_soal`
--

CREATE TABLE `habit_soal` (
  `id` int NOT NULL,
  `habit_id` int NOT NULL,
  `pertanyaan` text COLLATE utf8mb4_general_ci NOT NULL,
  `pilihan_a` text COLLATE utf8mb4_general_ci NOT NULL,
  `pilihan_b` text COLLATE utf8mb4_general_ci NOT NULL,
  `pilihan_c` text COLLATE utf8mb4_general_ci NOT NULL,
  `pilihan_d` text COLLATE utf8mb4_general_ci NOT NULL,
  `pilihan_e` text COLLATE utf8mb4_general_ci NOT NULL,
  `kunci_jawaban` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `pembahasan` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `habit_soal`
--

INSERT INTO `habit_soal` (`id`, `habit_id`, `pertanyaan`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `pilihan_e`, `kunci_jawaban`, `pembahasan`) VALUES
(12, 72, 'Apa gagasan utama yang ingin disampaikan penulis dalam teks tersebut?', 'Keunggulan teknologi AI dalam menggantikan peran guru di sekolah.', 'Dampak negatif kecerdasan buatan terhadap karakter siswa.', 'Peluang dan tantangan integrasi AI dalam dunia pendidikan.', 'Pentingnya literasi digital bagi siswa di daerah terpencil.', 'Sejarah perkembangan kecerdasan buatan dalam kurikulum sekolah.', 'C', 'Teks membahas dua sisi mata uang: peluang (personalisasi, efisiensi) di paragraf 1-2 dan tantangan (etika, ketergantungan) di paragraf 3. Maka, gagasan utamanya adalah gabungan keduanya (Peluang dan Tantangan).'),
(13, 72, 'Dalam kalimat \"...fokus pada aspek pedagogis dan emosional siswa\", makna kata yang dicetak tebal adalah....', 'Berkaitan dengan administrasi sekolah.', 'Bersifat mendidik atau ilmu pengajaran.', 'Berhubungan dengan teknologi canggih.', 'Berkaitan dengan nilai moral semata.', 'Bersifat psikologis dan mental.', 'E', 'Pedagogis menurut KBBI berkaitan dengan ilmu pendidikan atau pengajaran. Dalam konteks kalimat, guru bisa fokus mengajar (pedagogis) daripada mengurusi administrasi.'),
(14, 72, 'Manakah pernyataan berikut yang TIDAK SESUAI dengan isi bacaan?', 'AI memungkinkan pembelajaran yang disesuaikan dengan kecepatan belajar siswa.', 'Tugas koreksi soal pilihan ganda dapat diambil alih oleh sistem AI.', 'Kesenjangan digital dapat memperparah ketertinggalan sekolah di daerah terpencil', 'Penulis menyarankan agar peran guru digantikan sepenuhnya oleh mesin canggih.', 'Generative AI memunculkan kekhawatiran terkait orisinalitas karya siswa.', 'D', 'Pernyataan D salah. Penulis justru menegaskan di paragraf terakhir bahwa teknologi harus diposisikan sebagai alat bantu (katalisator), bukan pengganti peran guru.'),
(15, 72, 'Simpulan yang paling tepat berdasarkan paragraf terakhir adalah....', 'Pendidikan karakter tidak lagi diperlukan karena AI sudah canggih.', 'Sekolah harus melarang penggunaan AI agar siswa tetap kreatif.', 'Penggunaan AI dalam pendidikan memerlukan kolaborasi bijak antara teknologi dan peran manusia.', 'Kurikulum pendidikan harus diubah total menyesuaikan algoritma AI.', 'Siswa adalah konsumen teknologi yang harus menerima segala bentuk kemajuan.', 'C', 'Paragraf terakhir menekankan pada \"pendekatan yang bijak\", \"tidak mengganti peran guru\", dan \"kolaborasi harmonis\". Opsi C merangkum poin-poin tersebut dengan tepat.'),
(16, 72, 'Frasa \"Fenomena ini\" pada kalimat kedua paragraf pertama merujuk pada....', 'Wacana futuristik tentang masa depan', 'Kesulitan guru dalam mengajar siswa.', 'Sistem pembelajaran satu ukuran untuk semua.', 'Kehadiran kecerdasan buatan di berbagai sektor termasuk pendidikan.', 'Realitas yang dihadapi siswa saat ujian.', 'D', 'Kalimat sebelumnya berbunyi \"Kehadiran kecerdasan buatan atau Artificial Intelligence (AI) telah merambah berbagai sektor...\". Maka \"Fenomena ini\" merujuk pada kehadiran AI tersebut.'),
(17, 72, 'Bagaimana hubungan antara paragraf ke-2 dan paragraf ke-3?', 'Paragraf ke-3 memberikan contoh detail dari ide di paragraf ke-2.', 'Paragraf ke-3 menjelaskan akibat dari pernyataan di paragraf ke-2.', 'Paragraf ke-3 mempertentangkan gagasan yang ada di paragraf ke-2.', 'Paragraf ke-3 menegaskan kembali ide utama paragraf ke-2.', 'Paragraf ke-3 tidak memiliki hubungan logis dengan paragraf ke-2.', 'C', 'Paragraf 2 membahas sisi positif (angin segar, efisiensi). Paragraf 3 dimulai dengan kata \"Namun\" dan membahas tantangan/negatif. Hubungannya adalah pertentangan (kontradiksi).'),
(18, 72, 'Pernyataan mana yang dapat memperlemah argumen bahwa \"AI menawarkan personalisasi pembelajaran\"?', 'Siswa merasa lebih senang belajar dengan tablet daripada buku.', 'Algoritma AI seringkali bias dan tidak akurat dalam menilai konteks budaya siswa tertentu.', 'Guru merasa terbantu dengan adanya otomatisasi nilai.', 'Biaya pengadaan teknologi AI sangat mahal bagi sekolah swasta.', 'Siswa dapat mengakses materi pelajaran kapan saja.', 'B', 'Argumen \"personalisasi\" bergantung pada kemampuan AI menganalisis siswa. Jika algoritma bias atau tidak akurat (Opsi B), maka janji personalisasi tersebut gagal atau lemah.'),
(19, 72, 'Tujuan penulis menulis teks tersebut adalah untuk....', 'Mempromosikan produk AI tertentu kepada sekolah-sekolah.', 'Mengkritik keras guru yang tidak mau menggunakan teknologi.', 'Memberikan pandangan berimbang mengenai dampak AI dalam pendidikan.', 'Menjelaskan cara kerja teknis algoritma kecerdasan buatan.', 'Menghibur pembaca dengan cerita fiksi ilmiah tentang sekolah masa depan.', 'C', 'Penulis memaparkan sisi positif dan negatif secara proporsional, lalu memberikan saran di akhir. Tujuannya memberikan wawasan yang objektif/berimbang.'),
(20, 72, 'Kata katalisator dalam kalimat \"...Teknologi harus diposisikan sebagai alat bantu (katalisator)...\" dimaknai sebagai....', 'Penghambat terjadinya perubahan.', 'Sesuatu yang mempercepat atau mempermudah proses.', 'Alat utama yang menggantikan fungsi asli.', 'Sumber masalah baru dalam sistem.', 'Penentu hasil akhir yang mutlak.', 'B', 'Katalisator (katalis) adalah zat/hal yang mempercepat laju reaksi/proses. Dalam konteks ini, teknologi mempercepat/mempermudah proses belajar, bukan menggantikannya.'),
(21, 72, 'Berdasarkan teks, apa yang kemungkinan terjadi jika sekolah mengabaikan literasi digital dan etika penggunaan AI?', 'Nilai siswa akan meningkat drastis secara alami.', 'Guru akan kehilangan pekerjaan mereka sepenuhnya.', 'Siswa menjadi konsumen pasif yang kehilangan kemampuan berpikir kritis.', 'Sekolah akan mendapatkan bantuan dana lebih besar.', 'Kesenjangan digital antara desa dan kota akan menghilang.', 'C', 'Di paragraf terakhir disebutkan perlunya literasi digital agar siswa \"tidak hanya menjadi konsumen teknologi yang pasif\". Jika diabaikan, maka kebalikannya yang terjadi: siswa jadi pasif dan tidak kritis (sesuai kekhawatiran di paragraf 3).');

-- --------------------------------------------------------

--
-- Table structure for table `hasil_tryout`
--

CREATE TABLE `hasil_tryout` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `tryout_id` int NOT NULL,
  `subtest_id` int NOT NULL,
  `skor` decimal(6,2) DEFAULT NULL,
  `jumlah_benar` int DEFAULT '0',
  `jumlah_salah` int DEFAULT '0',
  `jumlah_kosong` int DEFAULT '0',
  `status` enum('sedang_dikerjakan','selesai') NOT NULL DEFAULT 'sedang_dikerjakan',
  `waktu_mulai` datetime NOT NULL,
  `waktu_selesai` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_belajar`
--

CREATE TABLE `jadwal_belajar` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `subtest_id` int NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') COLLATE utf8mb4_general_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_belajar`
--

INSERT INTO `jadwal_belajar` (`id`, `user_id`, `subtest_id`, `hari`, `jam_mulai`, `jam_selesai`, `created_at`) VALUES
(15, 2, 3, 'Senin', '02:00:00', '03:30:00', '2025-11-30 09:02:50'),
(17, 48, 4, 'Selasa', '07:30:00', '08:30:00', '2025-11-30 14:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `jawaban_user`
--

CREATE TABLE `jawaban_user` (
  `id` int NOT NULL,
  `hasil_tryout_id` int NOT NULL,
  `soal_id` int NOT NULL,
  `jawaban_dipilih` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kontak`
--

CREATE TABLE `kontak` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subjek` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `tanggal_kirim` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Baru','Sudah Dibaca','Sudah Dibalas') NOT NULL DEFAULT 'Baru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kontak`
--

INSERT INTO `kontak` (`id`, `nama`, `email`, `subjek`, `pesan`, `tanggal_kirim`, `status`) VALUES
(2, 'Sahril Sidik', 'sahrilwfc@gmail.com', 'Pembelian Paket', 'bagaimana caranya membeli paket premium?', '2025-11-18 15:08:53', 'Sudah Dibalas'),
(11, 'SAHRIL SIDIK', 'sahrilwfc@gmail.com', 'Pembelian Paket Premium', 'tes', '2025-11-28 14:34:26', 'Sudah Dibaca'),
(12, 'SAHRIL SIDIK', 'sahrilwfc@gmail.com', 'Pembelian Paket Premium', 'd', '2025-12-01 12:11:19', 'Baru');

-- --------------------------------------------------------

--
-- Table structure for table `materi`
--

CREATE TABLE `materi` (
  `id` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `subtest_id` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `tipe` enum('Free','Premium') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Free',
  `link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'default.jpg',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materi`
--

INSERT INTO `materi` (`id`, `judul`, `deskripsi`, `subtest_id`, `tanggal`, `tipe`, `link`, `thumbnail`, `created_at`) VALUES
(12, 'Strategi Cepat Menentukan Gagasan Utama & Ide Pokok', '<p data-path-to-node=\"14\"><strong>A. Pengertian Gagasan Utama</strong> Gagasan utama (ide pokok) adalah inti atau pokok pembahasan yang mendasari terbentuknya sebuah paragraf. Gagasan utama inilah yang menjadi \"nyawa\" dari sebuah teks.</p>\r\n<p data-path-to-node=\"15\"><strong>B. Letak Gagasan Utama</strong></p>\r\n<ol start=\"1\" data-path-to-node=\"16\">\r\n<li>\r\n<p data-path-to-node=\"16,0,0\"><strong>Deduktif:</strong> Terletak di <strong>awal</strong> paragraf.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"16,1,0\"><strong>Induktif:</strong> Terletak di <strong>akhir</strong> paragraf (biasanya ditandai konjungsi penyimpul: <em>oleh karena itu, jadi, dengan demikian</em>).</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"16,2,0\"><strong>Campuran:</strong> Terletak di awal dan ditegaskan kembali di akhir.</p>\r\n</li>\r\n</ol>\r\n<p data-path-to-node=\"17\"><strong>C. Trik Cepat Menemukan Gagasan Utama (SNBT)</strong></p>\r\n<ol start=\"1\" data-path-to-node=\"18\">\r\n<li>\r\n<p data-path-to-node=\"18,0,0\"><strong>Baca Kalimat Pertama &amp; Terakhir:</strong> Cek di mana kalimat utamanya berada. Apakah di awal atau simpulan di akhir?</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"18,1,0\"><strong>Cari Kata Kunci yang Diulang:</strong> Jika ada kata yang sering muncul (repetisi), biasanya itu adalah topiknya.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"18,2,0\"><strong>Teknik Eliminasi:</strong> Hapus kalimat penjelas (kalimat yang berisi data angka, contoh, atau rincian). Sisanya adalah inti kalimat.</p>\r\n</li>\r\n</ol>\r\n<p data-path-to-node=\"19\"><strong>D. Contoh Penerapan</strong> <em>Teks:</em> \"Banjir di Jakarta disebabkan oleh banyak faktor. Pertama, curah hujan yang tinggi. Kedua, kurangnya daerah resapan air. Ketiga, kebiasaan membuang sampah sembarangan.\"</p>\r\n<ul data-path-to-node=\"20\">\r\n<li>\r\n<p data-path-to-node=\"20,0,0\"><strong>Kalimat Utama:</strong> Banjir di Jakarta disebabkan oleh banyak faktor. (Ada di awal/Deduktif).</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"20,1,0\"><strong>Gagasan Utama:</strong> Faktor penyebab banjir di Jakarta.</p>\r\n</li>\r\n</ul>', 7, '2025-11-29', 'Free', 'https://www.youtube.com/watch?v=lH9c2WyeVtY', 'default.jpg', '2025-11-29 07:11:30'),
(13, 'Trik Menentukan Makna Kata & Istilah Sulit (Semantik)', '<p data-path-to-node=\"4,4,0\"><strong>A. Definisi Makna Kata</strong> Dalam soal SNBT, Anda sering diminta mencari makna sebuah kata atau istilah. Ingat, makna yang dicari adalah <strong>Makna Kontekstual</strong>, bukan sekadar makna leksikal (kamus). Artinya, makna kata tersebut bisa berubah tergantung kalimat yang mengiringinya.</p>\r\n<p data-path-to-node=\"4,4,1\"><strong>B. Jenis Makna Kata</strong></p>\r\n<ol start=\"1\" data-path-to-node=\"4,4,2\">\r\n<li>\r\n<p data-path-to-node=\"4,4,2,0,0\"><strong>Makna Denotasi:</strong> Makna sebenarnya/lugas. (Contoh: \"Ia makan nasi\" -&gt; memasukkan makanan ke mulut).</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"4,4,2,1,0\"><strong>Makna Konotasi:</strong> Makna kiasan/tambahan. (Contoh: \"Ia makan garam kehidupan\" -&gt; berpengalaman).</p>\r\n</li>\r\n</ol>\r\n<p data-path-to-node=\"4,4,3\"><strong>C. Trik Menjawab Soal Makna Kata</strong></p>\r\n<ol start=\"1\" data-path-to-node=\"4,4,4\">\r\n<li>\r\n<p data-path-to-node=\"4,4,4,0,0\"><strong>Jangan Terpaku pada Kamus:</strong> Fokus pada kalimat tempat kata itu berada.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"4,4,4,1,0\"><strong>Substitusi (Ganti Kata):</strong> Coba ganti kata yang ditanyakan dengan opsi jawaban. Jika kalimatnya tetap logis dan enak dibaca, itulah jawabannya.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"4,4,4,2,0\"><strong>Lihat Kalimat Tetangga:</strong> Baca satu kalimat sebelum dan sesudahnya untuk menangkap nuansa positif atau negatifnya.</p>\r\n</li>\r\n</ol>', 7, '2025-11-29', 'Premium', 'https://www.youtube.com/watch?v=kYJvFVvOQO8', 'default.jpg', '2025-11-29 07:14:02'),
(14, 'Membedakan Fakta dan Opini Penulis', '<p data-path-to-node=\"10,4,0\"><strong>A. Definisi Fakta</strong> Keadaan atau peristiwa yang merupakan kenyataan dan benar-benar terjadi.</p>\r\n<ul data-path-to-node=\"10,4,1\">\r\n<li>\r\n<p data-path-to-node=\"10,4,1,0,0\"><strong>Ciri-ciri Fakta:</strong></p>\r\n<ol start=\"1\" data-path-to-node=\"10,4,1,0,1\">\r\n<li>\r\n<p data-path-to-node=\"10,4,1,0,1,0,0\">Bersifat objektif.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"10,4,1,0,1,1,0\">Terdapat data akurat (angka, tanggal, waktu, tempat).</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"10,4,1,0,1,2,0\">Sudah terjadi (Past Tense secara logika).</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"10,4,1,0,1,3,0\">Narasumber terpercaya.</p>\r\n</li>\r\n</ol>\r\n</li>\r\n</ul>\r\n<p data-path-to-node=\"10,4,2\"><strong>B. Definisi Opini</strong> Pendapat, pikiran, atau pendirian seseorang terhadap suatu peristiwa.</p>\r\n<ul data-path-to-node=\"10,4,3\">\r\n<li>\r\n<p data-path-to-node=\"10,4,3,0,0\"><strong>Ciri-ciri Opini:</strong></p>\r\n<ol start=\"1\" data-path-to-node=\"10,4,3,0,1\">\r\n<li>\r\n<p data-path-to-node=\"10,4,3,0,1,0,0\">Bersifat subjektif.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"10,4,3,0,1,1,0\">Mengandung kata sifat relatif (sangat, mungkin, sebaiknya, kira-kira).</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"10,4,3,0,1,2,0\">Berupa prediksi atau saran.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"10,4,3,0,1,3,0\">Belum tentu terjadi (Future Tense).</p>\r\n</li>\r\n</ol>\r\n</li>\r\n</ul>\r\n<p data-path-to-node=\"10,4,4\"><strong>C. Contoh Analisis</strong></p>\r\n<ul data-path-to-node=\"10,4,5\">\r\n<li>\r\n<p data-path-to-node=\"10,4,5,0,0\"><em>Fakta:</em> \"Gunung Semeru meletus pada tanggal 4 Desember 2021.\" (Ada data tanggal).</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"10,4,5,1,0\"><em>Opini:</em> \"Pemandangan letusan Gunung Semeru sangat mengerikan namun menakjubkan.\" (Ada kata sifat subjektif: mengerikan, menakjubkan).</p>\r\n</li>\r\n</ul>', 7, '2025-11-29', 'Premium', 'https://www.youtube.com/watch?v=J_jT1KkYJ2E', 'default.jpg', '2025-11-29 07:14:44'),
(15, 'Memahami Kata Rujukan (Ini, Itu, Tersebut)', '<ul data-path-to-node=\"13\">\r\n<li>\r\n<p data-path-to-node=\"13,4,0\"><strong>A. Pengertian Kata Rujukan</strong> Kata rujukan adalah kata yang digunakan untuk mengacu pada kata lain yang sudah disebutkan sebelumnya (anafora) atau yang akan disebutkan (katafora). Ini penting untuk menjaga kohesi (kepaduan) teks.</p>\r\n<p data-path-to-node=\"13,4,1\"><strong>B. Jenis Kata Rujukan</strong></p>\r\n<ol start=\"1\" data-path-to-node=\"13,4,2\">\r\n<li>\r\n<p data-path-to-node=\"13,4,2,0,0\"><strong>Rujukan Benda:</strong> Ini, itu, tersebut.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"13,4,2,1,0\"><strong>Rujukan Tempat:</strong> Di sana, di sini, di situ.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"13,4,2,2,0\"><strong>Rujukan Orang (Pronomina):</strong> Dia, ia, mereka, beliau, -nya.</p>\r\n</li>\r\n</ol>\r\n<p data-path-to-node=\"13,4,3\"><strong>C. Trik Menjawab Soal \"Kata \'ini\' merujuk pada...\"</strong></p>\r\n<ol start=\"1\" data-path-to-node=\"13,4,4\">\r\n<li>\r\n<p data-path-to-node=\"13,4,4,0,0\"><strong>Mundur Satu Kalimat:</strong> Jawabannya 99% ada di kalimat <em>sebelum</em> kata rujukan itu muncul.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"13,4,4,1,0\"><strong>Substitusi:</strong> Ganti kata \"ini/itu\" dengan frasa yang Anda curigai sebagai jawaban. Jika nyambung, itu jawabannya.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"13,4,4,2,0\"><strong>Perhatikan Frasa Nomina:</strong> Biasanya rujukan mengarah pada Subjek atau Objek dari kalimat sebelumnya.</p>\r\n</li>\r\n</ol>\r\n</li>\r\n</ul>', 7, '2025-11-29', 'Premium', 'https://www.youtube.com/watch?v=2rE5L2g7Xg0', 'default.jpg', '2025-11-29 07:16:19'),
(16, 'Analisis Tujuan Penulis dan Nada Bacaan (Tone)', '<p data-path-to-node=\"16,4,0\"><strong>A. Mengapa Materi Ini Penting?</strong> Soal tipe <em>High Order Thinking Skill</em> (HOTS) ini meminta siswa menyelami pikiran penulis. \"Mengapa penulis menulis teks ini?\" dan \"Bagaimana perasaan penulis?\".</p>\r\n<p data-path-to-node=\"16,4,1\"><strong>B. Jenis Tujuan Penulis</strong></p>\r\n<ol start=\"1\" data-path-to-node=\"16,4,2\">\r\n<li>\r\n<p data-path-to-node=\"16,4,2,0,0\"><strong>Informatif:</strong> Sekadar memberi info (biasanya teks berita/eksposisi).</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"16,4,2,1,0\"><strong>Persuasif:</strong> Mengajak/membujuk pembaca (iklan/editorial).</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"16,4,2,2,0\"><strong>Argumentatif:</strong> Meyakinkan pembaca akan suatu pendapat (esai/opini).</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"16,4,2,3,0\"><strong>Rekreatif:</strong> Menghibur (cerpen/novel).</p>\r\n</li>\r\n</ol>\r\n<p data-path-to-node=\"16,4,3\"><strong>C. Menentukan Sikap Penulis (Tone)</strong> Perhatikan penggunaan kata sifat (adjektiva) dalam teks:</p>\r\n<ul data-path-to-node=\"16,4,4\">\r\n<li>\r\n<p data-path-to-node=\"16,4,4,0,0\"><strong>Positif:</strong> Optimis, mendukung, memuji, bangga.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"16,4,4,1,0\"><strong>Negatif:</strong> Pesimis, mencela, prihatin, kecewa, sinis.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"16,4,4,2,0\"><strong>Netral:</strong> Objektif, hanya memaparkan data tanpa emosi.</p>\r\n</li>\r\n</ul>', 7, '2025-11-29', 'Premium', 'https://www.youtube.com/watch?v=LqWXeJjD0Yg', 'default.jpg', '2025-11-29 07:16:53'),
(17, 'Strategi Meringkas Teks Panjang (Ikhtisar)', '<p data-path-to-node=\"19,4,0\"><strong>A. Perbedaan Ringkasan dan Ikhtisar</strong></p>\r\n<ul data-path-to-node=\"19,4,1\">\r\n<li>\r\n<p data-path-to-node=\"19,4,1,0,0\"><strong>Ringkasan:</strong> Memendekkan teks dengan <em>mempertahankan urutan</em> isi dan sudut pandang asli.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"19,4,1,1,0\"><strong>Ikhtisar:</strong> Memendekkan teks dengan mengambil intinya saja <em>tanpa terikat urutan</em>. (Di SNBT, keduanya sering dianggap sama dalam opsi jawaban).</p>\r\n</li>\r\n</ul>\r\n<p data-path-to-node=\"19,4,2\"><strong>B. Rumus Cepat Meringkas</strong> Ringkasan = <strong>Gagasan Utama Paragraf 1 + Gagasan Utama Paragraf 2 + ... + Konjungsi Penghubung.</strong></p>\r\n<p data-path-to-node=\"19,4,3\"><strong>C. Langkah Kerja</strong></p>\r\n<ol start=\"1\" data-path-to-node=\"19,4,4\">\r\n<li>\r\n<p data-path-to-node=\"19,4,4,0,0\">Scanning setiap paragraf untuk menemukan Kalimat Utama.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"19,4,4,1,0\">Coret/abaikan detail, contoh, dan data angka.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"19,4,4,2,0\">Gabungkan inti kalimat utama menjadi satu paragraf yang padu.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"19,4,4,3,0\">Cari opsi jawaban yang paling mewakili gabungan tersebut.</p>\r\n</li>\r\n</ol>', 7, '2025-11-29', 'Premium', 'https://youtu.be/_dpTRLO0EAc?si=iw0pVLwGO8yNHKQN', 'default.jpg', '2025-11-29 07:17:26');

-- --------------------------------------------------------

--
-- Table structure for table `metode_pembayaran`
--

CREATE TABLE `metode_pembayaran` (
  `id` int NOT NULL,
  `nama_metode` varchar(100) NOT NULL,
  `nomor_rekening` varchar(50) NOT NULL,
  `atas_nama` varchar(100) NOT NULL,
  `status` enum('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `metode_pembayaran`
--

INSERT INTO `metode_pembayaran` (`id`, `nama_metode`, `nomor_rekening`, `atas_nama`, `status`) VALUES
(1, 'DANA', '083119897273', 'SAHRIL SIDIK', 'Aktif'),
(2, 'Shopeepay', '083119897273', 'SAHRIL SIDIK', 'Aktif'),
(3, 'BCA', '2222222222', 'SAHRIL SDIK', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `id` int NOT NULL,
  `nama_paket` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `is_unggulan` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`id`, `nama_paket`, `harga`, `is_unggulan`) VALUES
(3, 'Gratis', '0.00', 0),
(7, 'Paket Premium ', '199000.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `paket_fitur`
--

CREATE TABLE `paket_fitur` (
  `paket_id` int NOT NULL,
  `fitur_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket_fitur`
--

INSERT INTO `paket_fitur` (`paket_id`, `fitur_id`) VALUES
(7, 2),
(7, 3),
(7, 4),
(7, 5),
(7, 6),
(7, 7),
(7, 8),
(7, 9),
(7, 10);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `paket_id` int NOT NULL,
  `metode_pembayaran_id` int NOT NULL,
  `tanggal_pembayaran` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `catatan` text,
  `bukti_pembayaran` varchar(255) NOT NULL,
  `status_pembayaran` enum('Menunggu','Diterima','Ditolak') NOT NULL DEFAULT 'Menunggu',
  `tanggal_konfirmasi` datetime DEFAULT NULL,
  `admin_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `user_id`, `paket_id`, `metode_pembayaran_id`, `tanggal_pembayaran`, `catatan`, `bukti_pembayaran`, `status_pembayaran`, `tanggal_konfirmasi`, `admin_id`) VALUES
(1, 13, 3, 2, '2025-11-19 09:00:32', 'coba', 'uploads/bukti_pembayaran/13_1763542832.png', 'Diterima', '2025-11-19 16:44:52', 3),
(4, 48, 7, 1, '2025-11-29 15:32:01', 'j', 'uploads/bukti_pembayaran/48_1764430321.png', 'Diterima', '2025-11-29 22:32:55', 3),
(5, 48, 7, 2, '2025-11-30 12:39:29', 'd', 'uploads/bukti_pembayaran/48_1764506369.png', 'Ditolak', '2025-11-30 20:55:21', 3),
(21, 48, 7, 3, '2025-11-30 14:33:31', 'g', 'uploads/bukti_pembayaran/48_1764513211.jpg', 'Menunggu', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text COLLATE utf8mb4_general_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_terbit` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` enum('Published','Draft') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Draft',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `isi`, `link`, `tanggal_terbit`, `tanggal_selesai`, `status`, `created_at`, `updated_at`) VALUES
(6, 'NeoCommunity', 'Pssst... semua pejuang SNBT udah nongkrong di NeoCommunity ? Jangan sampai ketinggalan diskusi + seru-seruan bareng mereka! Join yuk ✨', 'https:wa.me/083119897273', '2025-11-26', '2025-12-03', 'Published', '2025-11-18 03:39:52', '2025-11-29 07:19:17'),
(7, 'Dunia hanya sementara, akhirat selamanya.', 'Jangan terlalu membebani hati dengan hal-hal dunia yang fana. Ambil seperlunya, tinggalkan yang membuatmu jauh dari Allah.', '', '2025-11-29', '2026-01-31', 'Published', '2025-11-29 07:48:33', '2025-11-30 13:04:29');

-- --------------------------------------------------------

--
-- Table structure for table `shorts`
--

CREATE TABLE `shorts` (
  `id` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_upload` date NOT NULL,
  `video_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tipe` enum('Free','Premium') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Free',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shorts`
--

INSERT INTO `shorts` (`id`, `judul`, `tanggal_upload`, `video_path`, `tipe`, `created_at`, `updated_at`) VALUES
(10, 'Persiapan UTBK', '2025-11-29', 'uploads/shorts/692aa4219e770-1.mp4', 'Free', '2025-11-29 07:41:29', '2025-11-29 07:43:29'),
(11, 'Persiapan UTBK', '2025-11-28', 'uploads/shorts/692aa4f054270-2.mp4', 'Free', '2025-11-29 07:46:56', '2025-11-29 07:46:56');

-- --------------------------------------------------------

--
-- Table structure for table `soal_tryout`
--

CREATE TABLE `soal_tryout` (
  `id` int NOT NULL,
  `tryout_id` int NOT NULL,
  `subtest_id` int NOT NULL,
  `konteks_soal` text COLLATE utf8mb4_general_ci,
  `pertanyaan` text COLLATE utf8mb4_general_ci NOT NULL,
  `pilihan_a` text COLLATE utf8mb4_general_ci NOT NULL,
  `pilihan_b` text COLLATE utf8mb4_general_ci NOT NULL,
  `pilihan_c` text COLLATE utf8mb4_general_ci NOT NULL,
  `pilihan_d` text COLLATE utf8mb4_general_ci NOT NULL,
  `pilihan_e` text COLLATE utf8mb4_general_ci NOT NULL,
  `kunci_jawaban` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `pembahasan` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `soal_tryout`
--

INSERT INTO `soal_tryout` (`id`, `tryout_id`, `subtest_id`, `konteks_soal`, `pertanyaan`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `pilihan_e`, `kunci_jawaban`, `pembahasan`) VALUES
(40, 5, 5, '', 'percobaan', 'a', 'v', 'b', 'ee', 'e', 'C', 'd'),
(41, 5, 5, '', 's', 's', 's', 's', 's', 's', 'A', 's'),
(42, 7, 7, '4', '4', 'Tindakan yang keras terhadap diri sendiri', 'v', 'Sikap mudah menyerah pada peraturan', 'Orang lebih malas bekerja di rumah', 'Pekerjaan dari rumah tidak efisien', 'B', 'x');

-- --------------------------------------------------------

--
-- Table structure for table `subtest`
--

CREATE TABLE `subtest` (
  `id` int NOT NULL,
  `nama_subtest` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `singkatan` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subtest`
--

INSERT INTO `subtest` (`id`, `nama_subtest`, `singkatan`) VALUES
(1, 'Penalaran Umum', 'PA'),
(2, 'Pemahaman Bacaan dan Menulis', 'PBM'),
(3, 'Pengetahuan dan Pemahaman Umum', 'PBU'),
(4, 'Literasi Bahasa Inggris', 'LBI'),
(5, 'Penalaran Kuantitatif', 'PK'),
(6, 'Penalaran Matematika', 'PM'),
(7, 'Literasi Bahasa Indonesia', 'LBI');

-- --------------------------------------------------------

--
-- Table structure for table `tryout`
--

CREATE TABLE `tryout` (
  `id` int NOT NULL,
  `nama_tryout` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `waktu_pengerjaan` int NOT NULL COMMENT 'Dalam menit',
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` enum('Mendatang','Aktif','Selesai') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Mendatang',
  `tipe` enum('Free','Premium') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Free'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tryout`
--

INSERT INTO `tryout` (`id`, `nama_tryout`, `waktu_pengerjaan`, `tanggal_mulai`, `tanggal_selesai`, `status`, `tipe`) VALUES
(5, 'Tryout UTBK - SNBT #3', 40, '2025-11-29', '2025-12-06', 'Mendatang', 'Free'),
(7, 'Tryout UTBK - SNBT #4', 30, '2025-12-02', '2025-12-04', 'Mendatang', 'Free'),
(8, 'Tryout UTBK - SNBT #2', 0, '2025-12-06', '2026-01-02', 'Mendatang', 'Premium'),
(9, 'Tryout UTBK - SNBT #1', 0, '2025-12-01', '2025-12-10', 'Mendatang', 'Premium');

-- --------------------------------------------------------

--
-- Table structure for table `tryout_subtest`
--

CREATE TABLE `tryout_subtest` (
  `id` int NOT NULL,
  `tryout_id` int NOT NULL,
  `subtest_id` int NOT NULL,
  `waktu_pengerjaan` int NOT NULL COMMENT 'Dalam menit'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tryout_subtest`
--

INSERT INTO `tryout_subtest` (`id`, `tryout_id`, `subtest_id`, `waktu_pengerjaan`) VALUES
(1, 5, 5, 33),
(5, 7, 7, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('siswa','guru','admin') NOT NULL DEFAULT 'siswa',
  `tipe_user` enum('Free','Premium') NOT NULL DEFAULT 'Free',
  `masa_aktif` date DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `telepon`, `password`, `role`, `tipe_user`, `masa_aktif`, `foto_profil`, `created_at`, `reset_token`, `reset_token_expires`) VALUES
(1, 'SAHRIL SIDIK', 'guru@gmail.com', '081234567890', '$2y$10$TO3P6K3NsdXWNWIi.BO89.nUPhYnNrQwK0yb5QMZOVujfMvGEqW6.', 'guru', 'Free', NULL, 'uploads/profile/6926a230cad5c-ic_profile_guru.png', '2025-10-29 12:40:04', 'f1095b0247d3d644d45f43a860a80b0554a8a764ec42e0a9fd7c02ae082518ec', '2025-11-28 15:31:44'),
(2, 'Sahril Anarshy', 'siswa@gmail.com', '083119897273', '$2y$10$oSIJrnK6JfyAFvh.2QRSM.ztmuzs0EG6pdWpxe5X6B4Fz8jjjGrUu', 'siswa', 'Premium', '2025-11-29', 'uploads/profile/692c3ff879ed7.jpg', '2025-10-29 12:40:42', '32c834988380614678b964ce9956afbfe16ffcf4b9d9396de573d692b222b1e0', '2025-11-29 16:27:51'),
(3, 'Sahril Sidik', 'admin@gmail.com', '083119892773', '$2y$10$XuCibDUGfdf2qjcoAdZLSekTgF/33jbhdm9HYrokWaxdh6XSkXo.G', 'admin', 'Free', NULL, 'uploads/profile/692c3e58d6c12.png', '2025-10-29 12:41:15', NULL, NULL),
(13, 'Nafisah Lutfiah Latifah ', 'nafisah@gmail.com', '083119897234', '$2y$10$MB/3kZ.G2WoyIYFQpNNES.E694xQf7vdm3.0FR5GfhmGc36pA5cZa', 'siswa', 'Free', NULL, NULL, '2025-11-18 14:02:25', NULL, NULL),
(14, 'Fatimah Al habsyi', 'fatimah@gmail.com', '083119897234', '$2y$10$E5lYvM7g.o7qMbvS1e2M6eNhU9sa3/wuLu4zdAHT7fQyLc1F7.n3q', 'siswa', 'Premium', '2025-11-30', NULL, '2025-11-18 14:04:45', NULL, NULL),
(16, 'Admin Satu', 'admin1@sekolah.com', '0812000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Free', NULL, NULL, '2025-11-18 14:17:26', NULL, NULL),
(17, 'Admin Dua', 'admin2@sekolah.com', '0812000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Free', NULL, NULL, '2025-11-18 14:17:26', NULL, NULL),
(18, 'Admin Tiga', 'admin3@sekolah.com', '0812000003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Free', NULL, NULL, '2025-11-18 14:17:26', NULL, NULL),
(19, 'Admin Empat', 'admin4@sekolah.com', '0812000004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Free', NULL, NULL, '2025-11-18 14:17:26', NULL, NULL),
(20, 'Admin Lima', 'admin5@sekolah.com', '0812000005', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Free', NULL, NULL, '2025-11-18 14:17:26', NULL, NULL),
(21, 'Admin Enam', 'admin6@sekolah.com', '0812000006', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Free', NULL, NULL, '2025-11-18 14:17:26', NULL, NULL),
(22, 'Admin Tujuh', 'admin7@sekolah.com', '0812000007', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Free', NULL, NULL, '2025-11-18 14:17:26', NULL, NULL),
(23, 'Admin Delapan', 'admin8@sekolah.com', '0812000008', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Free', NULL, NULL, '2025-11-18 14:17:26', NULL, NULL),
(24, 'Admin Sembilan', 'admin9@sekolah.com', '0812000009', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Free', NULL, NULL, '2025-11-18 14:17:26', NULL, NULL),
(25, 'Admin Sepuluh', 'admin10@sekolah.com', '0812000010', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Free', NULL, NULL, '2025-11-18 14:17:26', NULL, NULL),
(48, 'Sahril wfc', 'sahrilwfc@gmail.com', '083119897273', '$2y$10$8S1Scucky4bwZO4drAgl3ey5tpdWqY3fhfQzSB0UtBGaCSYJYpXqS', 'siswa', 'Free', NULL, 'uploads/profile/692c3b2ebba48.jpg', '2025-11-26 05:10:04', 'e8a7675eee36145902cd1f437ce618bfff4b3f80e44f4a5ef2eced998a9c23aa', '2025-11-30 16:33:02'),
(49, 'Nayla Syarifah', 'nayla@gmail.com', '083119897273', '$2y$10$Z2KiGIwlk7c2Pju3xJ6ei.4wkLjmrsFjAACzBk2SCGpcdPLUKIhSK', 'guru', 'Free', NULL, 'uploads/guru/1764335029_ic_profile_guru.png', '2025-11-28 13:03:49', NULL, NULL),
(53, 'sahril', '200@gmail.com', '8311989', '$2y$10$FPXnlwhuaO8dY1yNmgkrp.YvmacQJrwK6L.WHU37w715AW9RWkD0O', 'siswa', 'Free', NULL, NULL, '2025-11-30 15:52:20', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_tryout_sessions`
--

CREATE TABLE `user_tryout_sessions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `tryout_id` int NOT NULL,
  `subtest_id` int NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `correct_count` int NOT NULL,
  `incorrect_count` int NOT NULL,
  `unanswered_count` int NOT NULL,
  `total_questions` int NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fitur`
--
ALTER TABLE `fitur`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `habit_harian`
--
ALTER TABLE `habit_harian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subtest_id` (`subtest_id`);

--
-- Indexes for table `habit_soal`
--
ALTER TABLE `habit_soal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habit_id` (`habit_id`);

--
-- Indexes for table `hasil_tryout`
--
ALTER TABLE `hasil_tryout`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tryout_id` (`tryout_id`),
  ADD KEY `subtest_id` (`subtest_id`);

--
-- Indexes for table `jadwal_belajar`
--
ALTER TABLE `jadwal_belajar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subtest_id` (`subtest_id`);

--
-- Indexes for table `jawaban_user`
--
ALTER TABLE `jawaban_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hasil_tryout_id` (`hasil_tryout_id`),
  ADD KEY `soal_id` (`soal_id`);

--
-- Indexes for table `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subtest_id` (`subtest_id`);

--
-- Indexes for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paket_fitur`
--
ALTER TABLE `paket_fitur`
  ADD PRIMARY KEY (`paket_id`,`fitur_id`),
  ADD KEY `fitur_id` (`fitur_id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `paket_id` (`paket_id`),
  ADD KEY `metode_pembayaran_id` (`metode_pembayaran_id`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shorts`
--
ALTER TABLE `shorts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `soal_tryout`
--
ALTER TABLE `soal_tryout`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tryout_id` (`tryout_id`),
  ADD KEY `subtest_id` (`subtest_id`);

--
-- Indexes for table `subtest`
--
ALTER TABLE `subtest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tryout`
--
ALTER TABLE `tryout`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tryout_subtest`
--
ALTER TABLE `tryout_subtest`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tryout_subtest_unique` (`tryout_id`,`subtest_id`),
  ADD KEY `tryout_id` (`tryout_id`),
  ADD KEY `subtest_id` (`subtest_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_tryout_sessions`
--
ALTER TABLE `user_tryout_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tryout_id` (`tryout_id`),
  ADD KEY `subtest_id` (`subtest_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artikel`
--
ALTER TABLE `artikel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fitur`
--
ALTER TABLE `fitur`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `habit_harian`
--
ALTER TABLE `habit_harian`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `habit_soal`
--
ALTER TABLE `habit_soal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `hasil_tryout`
--
ALTER TABLE `hasil_tryout`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_belajar`
--
ALTER TABLE `jadwal_belajar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `jawaban_user`
--
ALTER TABLE `jawaban_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kontak`
--
ALTER TABLE `kontak`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `materi`
--
ALTER TABLE `materi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shorts`
--
ALTER TABLE `shorts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `soal_tryout`
--
ALTER TABLE `soal_tryout`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `subtest`
--
ALTER TABLE `subtest`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tryout`
--
ALTER TABLE `tryout`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tryout_subtest`
--
ALTER TABLE `tryout_subtest`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `user_tryout_sessions`
--
ALTER TABLE `user_tryout_sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `habit_harian`
--
ALTER TABLE `habit_harian`
  ADD CONSTRAINT `habit_harian_ibfk_1` FOREIGN KEY (`subtest_id`) REFERENCES `subtest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `habit_soal`
--
ALTER TABLE `habit_soal`
  ADD CONSTRAINT `habit_soal_ibfk_1` FOREIGN KEY (`habit_id`) REFERENCES `habit_harian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hasil_tryout`
--
ALTER TABLE `hasil_tryout`
  ADD CONSTRAINT `hasil_tryout_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_tryout_ibfk_2` FOREIGN KEY (`tryout_id`) REFERENCES `tryout` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_tryout_ibfk_3` FOREIGN KEY (`subtest_id`) REFERENCES `subtest` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_belajar`
--
ALTER TABLE `jadwal_belajar`
  ADD CONSTRAINT `jadwal_belajar_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_belajar_ibfk_2` FOREIGN KEY (`subtest_id`) REFERENCES `subtest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jawaban_user`
--
ALTER TABLE `jawaban_user`
  ADD CONSTRAINT `jawaban_user_ibfk_1` FOREIGN KEY (`hasil_tryout_id`) REFERENCES `hasil_tryout` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jawaban_user_ibfk_2` FOREIGN KEY (`soal_id`) REFERENCES `soal_tryout` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `materi`
--
ALTER TABLE `materi`
  ADD CONSTRAINT `materi_ibfk_1` FOREIGN KEY (`subtest_id`) REFERENCES `subtest` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `paket_fitur`
--
ALTER TABLE `paket_fitur`
  ADD CONSTRAINT `paket_fitur_ibfk_1` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paket_fitur_ibfk_2` FOREIGN KEY (`fitur_id`) REFERENCES `fitur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_ibfk_3` FOREIGN KEY (`metode_pembayaran_id`) REFERENCES `metode_pembayaran` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `soal_tryout`
--
ALTER TABLE `soal_tryout`
  ADD CONSTRAINT `fk_soal_tryout_tryout` FOREIGN KEY (`tryout_id`) REFERENCES `tryout` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `soal_tryout_ibfk_2` FOREIGN KEY (`subtest_id`) REFERENCES `subtest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tryout_subtest`
--
ALTER TABLE `tryout_subtest`
  ADD CONSTRAINT `fk_tryout_subtest_subtest` FOREIGN KEY (`subtest_id`) REFERENCES `subtest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tryout_subtest_tryout` FOREIGN KEY (`tryout_id`) REFERENCES `tryout` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_tryout_sessions`
--
ALTER TABLE `user_tryout_sessions`
  ADD CONSTRAINT `fk_user_tryout_sessions_subtest` FOREIGN KEY (`subtest_id`) REFERENCES `subtest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_tryout_sessions_tryout` FOREIGN KEY (`tryout_id`) REFERENCES `tryout` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_tryout_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
