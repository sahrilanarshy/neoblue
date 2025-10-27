<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>NeoBlue | Lihat Pesan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Style tambahan untuk menampilkan pesan */
        .message-display-field {
            background-color: #f8f9fa;
            border: 1px solid #ebedf2;
            padding: 1rem;
            border-radius: 4px;
            min-height: 150px;
            white-space: pre-wrap; /* Ini penting agar format teks (enter) tetap terjaga */
        }
        .message-info p {
            margin-bottom: 0.5rem;
        }
        .message-info strong {
            display: inline-block;
            width: 80px; /* Merapikan tampilan label */
        }
    </style>
</head>

<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Manajemen Pesan</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href=".?hal=pesan">Daftar Pesan Masuk</a>
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
                         <a href=".?hal=pesan" class="btn btn-secondary btn-round ms-auto">
                            <i class="fa fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    
                    <div class="message-info mb-3">
                        <p><strong>Tanggal:</strong> 26/10/2025</p>
                        <p><strong>Pengirim:</strong> Budi Santoso</p>
                        <p><strong>Email:</strong> budi.s@example.com</p>
                        <p><strong>Subjek:</strong> Pertanyaan tentang paket</p>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label class="fw-bold">Isi Pesan:</label>
                        <div class="message-display-field">
Halo Admin,

Saya mau bertanya, apakah untuk Paket Premium fiturnya akan ditambah lagi ke depannya? 
Saya tertarik untuk mendaftar.

Terima kasih.
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <a href="mailto:budi.s@example.com" class="btn btn-primary">
                            <i class="fa fa-reply"></i> Balas via Email
                        </a>
                        <a href=".?hal=tandaisudahdibaca" class="btn btn-info">
                            Tandai Sudah Dibalas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>