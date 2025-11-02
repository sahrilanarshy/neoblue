<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-3 mb-3 border-bottom">
        <div class="col-md-12">
            <h3 class="fw-bold text-dark mb-1">Dashboard Guru</h3>
            <h6 class="text-muted">Kelola pembelajaran dan pantau progres siswa</h6>
        </div>
    </div>

    <div class="row g-3">
        <!-- Materi -->
        <div class="col-sm-6 col-md-3">
            <div class="info-box bg-primary text-white shadow-sm">
                <div class="inner">
                    <h3>6</h3>
                    <p>Total Materi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href=".?hal=materi" class="info-link text-white-50 text-decoration-none">
                    Lihat semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Habit -->
        <div class="col-sm-6 col-md-3">
            <div class="info-box bg-info text-white shadow-sm">
                <div class="inner">
                    <h3>9</h3>
                    <p>Total Habit</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <a href=".?hal=habit" class="info-link text-white-50 text-decoration-none">
                    Lihat semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Tryout -->
        <div class="col-sm-6 col-md-3">
            <div class="info-box bg-success text-white shadow-sm">
                <div class="inner">
                    <h3>8</h3>
                    <p>Total Tryout</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <a href=".?hal=tryout" class="info-link text-white-50 text-decoration-none">
                    Lihat semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Short -->
        <div class="col-sm-6 col-md-3">
            <div class="info-box bg-secondary text-white shadow-sm">
                <div class="inner">
                    <h3>3</h3>
                    <p>Total Short</p>
                </div>
                <div class="icon">
                    <i class="fas fa-video"></i>
                </div>
                <a href=".?hal=short" class="info-link text-white-50 text-decoration-none">
                    Lihat semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Style -->
<style>
    .info-box {
        position: relative;
        overflow: hidden;
        border-radius: 0.5rem;
        padding: 20px 15px 10px;
        transition: all 0.3s ease;
    }

    .info-box .inner {
        padding-right: 60px;
    }

    .info-box h3 {
        font-size: 2rem;
        font-weight: bold;
        margin: 0;
    }

    .info-box p {
        font-size: 1rem;
        margin: 0;
        font-weight: 500;
    }

    .info-box .icon {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 3rem;
        opacity: 0.2;
    }

    .info-box .info-link {
        display: block;
        margin-top: 10px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .info-box:hover {
        transform: translateY(-3px);
        filter: brightness(1.05);
    }

    .bg-primary {
        background-color: #007bff !important;
    }

    .bg-info {
        background-color: #17a2b8 !important;
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-secondary {
        background-color: #6c757d !important;
    }

    @media (max-width: 767px) {
        .info-box {
            text-align: center;
        }

        .info-box .icon {
            position: static;
            opacity: 0.3;
            font-size: 2.5rem;
            margin-top: 10px;
        }

        .info-box .inner {
            padding-right: 0;
        }
    }
</style>
