<div class="container mt-4">
  <h2 class="fw-bold mb-4 text-dark">Dashboard</h2>

  <div class="row g-4">
    <!-- Jumlah Siswa -->
    <div class="col-md-3 col-sm-6">
      <div class="stat-card bg-primary text-white shadow rounded-4">
        <div class="stat-content">
          <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
          <div>
            <h3 class="stat-number">120</h3>
            <p class="stat-label">Jumlah Siswa</p>
          </div>
        </div>
        <div class="stat-footer text-center">
          <a href=".?hal=user" class="text-white text-decoration-none fw-semibold">
            Lihat semua <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>

    <!-- Jumlah Admin -->
    <div class="col-md-3 col-sm-6">
      <div class="stat-card bg-warning text-white shadow rounded-4">
        <div class="stat-content">
          <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
          <div>
            <h3 class="stat-number">8</h3>
            <p class="stat-label">Jumlah Guru</p>
          </div>
        </div>
        <div class="stat-footer text-center">
          <a href=".?hal=guru" class="text-white text-decoration-none fw-semibold">
            Lihat semua <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>

    <!-- Pesan Masuk -->
    <div class="col-md-3 col-sm-6">
      <div class="stat-card bg-success text-white shadow rounded-4">
        <div class="stat-content">
          <div class="stat-icon"><i class="fas fa-envelope"></i></div>
          <div>
            <h3 class="stat-number">45</h3>
            <p class="stat-label">Pesan Masuk</p>
          </div>
        </div>
        <div class="stat-footer text-center">
          <a href=".?hal=kontak" class="text-white text-decoration-none fw-semibold">
            Lihat semua <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>
    <!-- Pendapatan -->
    <div class="col-md-3 col-sm-6">
      <div class="stat-card bg-info text-white shadow rounded-4">
        <div class="stat-content">
          <div class="stat-icon"><i class="fas fa-coins"></i></div>
          <div>
            <h3 class="stat-number">Rp 5.250.000</h3>
            <p class="stat-label">Total Pendapatan</p>
          </div>
        </div>
        <div class="stat-footer text-center">
          <a href=".?hal=pendapatan" class="text-white text-decoration-none fw-semibold">
            Lihat semua <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.stat-card {
  padding: 20px;
  transition: all 0.3s ease-in-out;
}
.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.stat-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.stat-icon {
  font-size: 2.5rem;
  opacity: 0.9;
}

.stat-number {
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
}

.stat-label {
  font-size: 0.95rem;
  margin: 0;
  opacity: 0.9;
}

.stat-footer {
  margin-top: 15px;
  background: rgba(255, 255, 255, 0.15);
  padding: 8px;
  border-radius: 10px;
  transition: 0.3s;
}

.stat-footer:hover {
  background: rgba(255, 255, 255, 0.25);
}

</style>