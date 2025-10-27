<main class="dashboard-content">
    <div class="premium-header">
        <h1>Upgrade ke Premium</h1>
        <p>Maksimalkan persiapan UTBK - SNBT dengan fitur Premium Lengkap</p>
        <a href="#" class="btn-promo">Promo Terbatas!</a>
    </div>

    <div class="premium-layout">
        <div class="paket-premium-card">
            <span class="badge-populer">Terpopuler</span>
            <h2>Paket Premium</h2>
            <div class="harga">
                <span class="harga-dicoret">Rp1.000.000</span>
                <span class="harga-promo">Rp199.000</span>
            </div>
            <p class="info-berlaku">Berlaku sampai 7 Oktober 2025</p>

            <ul class="fitur-list">
                <li><i class="bi bi-check-circle-fill"></i> Akses ke semua Materi Pembelajaran</li>
                <li><i class="bi bi-check-circle-fill"></i> Tryout Unlimited dengan pembahasan detail</li>
                <li><i class="bi bi-check-circle-fill"></i> Komunitas Premium Whatsapps</li>
                <li><i class="bi bi-check-circle-fill"></i> Download Materi Offline</li>
                <li><i class="bi bi-check-circle-fill"></i> Priority Support 24/7</li>
                <li><i class="bi bi-check-circle-fill"></i> Sertifikat Kelulusan</li>
            </ul>
        </div>

        <div class="pembayaran-card">
            <h3>Detail Pembayaran</h3>
            <div class="form-voucher">
                <label for="kode-voucher">Kode Voucher (Opsional)</label>
                <div class="input-grup">
                    <input type="text" id="kode-voucher" placeholder="Masukkan Kode Voucher">
                    <button>Terapkan</button>
                </div>
            </div>

            <div class="ringkasan-harga">
                <div class="item-harga">
                    <span>Paket Premium</span>
                    <span>Rp1.000.000</span>
                </div>
                <div class="item-harga">
                    <span>Promo</span>
                    <span>-Rp801.000</span>
                </div>
                <div class="total-harga">
                    <span>Total</span>
                    <span class="harga-final">Rp199.000</span>
                </div>
            </div>

            <!-- ID ditambahkan di sini untuk dihubungkan ke JavaScript -->
            <button class="btn-langganan" id="subscribeButton">Langganan Sekarang</button>

            <p class="syarat-ketentuan">
                Dengan melakukan pembayaran, anda menyetujui syarat dan ketentuan yang berlaku.
            </p>
        </div>
    </div>

    <div class="perbandingan-fitur-card">
        <h2>Perbandingan Fitur</h2>
        <div class="tabel-wrapper">
            <table class="tabel-fitur">
                <thead>
                    <tr>
                        <th>Fitur</th>
                        <th>Gratis</th>
                        <th>Premium</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Materi Pembelajaran</td>
                        <td>3 Subtest</td>
                        <td class="premium-highlight">Semua Subtest</td>
                    </tr>
                    <tr>
                        <td>Tryout</td>
                        <td>1 Per Bulan</td>
                        <td class="premium-highlight">Unlimited</td>
                    </tr>
                    <tr>
                        <td>Pembahasan Soal</td>
                        <td>Terbatas</td>
                        <td class="premium-highlight">Lengkap</td>
                    </tr>
                    <tr>
                        <td>Komunitas</td>
                        <td>Whatsapp reguler</td>
                        <td class="premium-highlight">Whatsapp Premium</td>
                    </tr>
                    <tr>
                        <td>Support</td>
                        <td>Email</td>
                        <td class="premium-highlight">24/7 Chat/ Call</td>
                    </tr>
                    <tr>
                        <td>Download Offline</td>
                        <td><i class="bi bi-x-circle-fill icon-merah"></i></td>
                        <td class="premium-highlight"><i class="bi bi-check-circle-fill icon-hijau"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div id="paymentModal">
    <div class="modal-content">

        <div id="modalStep1" class="modal-step active">
            <div class="modal-header">
                <h3>Rincian Belanja</h3>
                <img src="https://placehold.co/120x30/f0f0f0/666?text=Secured+by+Duitku" alt="Secured by Duitku">
                <button id="closeModalButton" class="modal-close-button">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-tabs">
                    <button class="active">PESANAN</button>
                    <button>PELANGGAN</button>
                </div>
                <div class="order-details">
                    <div><span>Id Pemesanan</span> <span>1761575989-3349-5777</span></div>
                    <div><span>Catatan</span> <span>Langganan Zypia Premium</span></div>
                    <div><span>Daftar Barang</span> <span>Langganan Zypi... x 1</span></div>
                    <div><span>Total</span> <span>Rp 199.000</span></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="total-payment-display">
                    <span class="total-label">Total Pembayaran</span>
                    <span class="total-amount">Rp 199.000</span>
                </div>
                <button id="paymentButton">Pembayaran</button>
            </div>
        </div>

        <div id="modalStep2" class="modal-step">
            <div class="modal-header">
                <button id="backToStep1" class="modal-back-button"><i class="bi bi-chevron-left"></i> Kembali</button>
                <h3>Pilih Metode</h3>
                <img src="https://placehold.co/120x30/f0f0f0/666?text=Secured+by+Duitku" alt="Secured by Duitku">
            </div>
            <div class="modal-body">
                <h4>Bank Transfer</h4>
                <button class="payment-method-item">
                    <div>
                        <img src="https://placehold.co/60x20/ffffff/003366?text=mandiri" alt="Mandiri">
                        <div>
                            <span class="method-title">MANDIRI VA H2H</span>
                            <span class="method-fee">Biaya Rp 4.000</span>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </button>
                <button class="payment-method-item">
                    <div>
                        <img src="https://placehold.co/60x20/ffffff/ff6600?text=BNI" alt="BNI">
                        <div>
                            <span class="method-title">BNI VA</span>
                            <span class="method-fee">Biaya Rp 3.000</span>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </button>
                <button class="payment-method-item">
                    <div>
                        <img src="https://placehold.co/60x20/ffffff/00529B?text=BRI" alt="BRI">
                        <div>
                            <span class="method-title">BRI VA</span>
                            <span class="method-fee">Biaya Rp 3.000</span>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </button>

                <h4>QRIS</h4>
                <button class="payment-method-item">
                    <div>
                        <img src="https://placehold.co/60x20/ffffff/4B0082?text=NUSAPAY" alt="Nusapay">
                        <div>
                            <span class="method-title">NUSAPAY QRIS</span>
                            <span class="method-fee">Tanpa Biaya Layanan</span>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 3: Detail Pembayaran VA -->
        <div id="modalStep3" class="modal-step">
            <div class="modal-header">
                <button id="backToStep2" class="modal-back-button"><i class="bi bi-chevron-left"></i>
                    Kembali</button>
                <h3>MANDIRI VA H2H</h3>
                <img src="https://placehold.co/120x30/f0f0f0/666?text=Secured+by+Duitku" alt="Secured by Duitku">
            </div>
            <div class="modal-body">
                <p>Dapatkan <strong>nomor akun virtual MANDIRI H2H</strong> Anda setelah menekan tombol
                    <strong>pembayaran</strong> di bawah.</p>
                <p>Periksa kembali <strong>data pembayaran</strong> Anda pada menu <strong>detail transaksi</strong>
                    sebelum melanjutkan transaksi.</p>

                <h5 style="font-weight: 600; margin-bottom: 0.5rem; font-size: 0.875rem;"><i
                        class="bi bi-wallet2"></i> Cara Pembayaran</h5>
                <div class="payment-instructions-list">
                    <a href="#"><span>iBank Mandiri</span> <i class="bi bi-chevron-right"></i></a>
                    <a href="#"><span>Livin' by Mandiri</span> <i class="bi bi-chevron-right"></i></a>
                </div>
            </div>
            <div class="modal-footer">
                <div class="total-payment-display">
                    <span class="total-label">Total Pembayaran</span>
                    <span class="total-amount">Rp 203.000</span>
                </div>
                <button id="confirmPaymentButton">Pembayaran</button>
            </div>
        </div>

        <!-- STEP 4: Status Transaksi -->
        <div id="modalStep4" class="modal-step">
            <div class="modal-header">
                <button id="backToStep3" class="modal-back-button"><i class="bi bi-chevron-left"></i>
                    Kembali</button>
                <h3>MANDIRI VA H2H</h3>
                <img src="https://placehold.co/120x30/f0f0f0/666?text=Secured+by+Duitku" alt="Secured by Duitku">
            </div>
            <div class="modal-body">
                <div class="va-display">
                    <span class="va-label">Nomor Akun Virtual</span>
                    <h4 class="va-number">
                        8902 8019 3075 0827 <i class="bi bi-clipboard" title="Salin"></i>
                    </h4>
                    <span class="va-status">Transaction pending.</span>
                </div>

                <div class="pending-warning">
                    <i class="bi bi-clock-history"></i>
                    <div>
                        <strong>Mohon menyelesaikan pembayaran</strong>
                        <br>Anda sebelum tanggal 10/27/2025 10:41:58 PM.
                    </div>
                </div>

                <h5 style="font-weight: 600; margin-bottom: 0.5rem; font-size: 0.875rem; text-align: left;"><i
                        class="bi bi-wallet2"></i> Cara Pembayaran</h5>
                <div class="payment-instructions-list">
                    <a href="#"><span>iBank Mandiri</span> <i class="bi bi-chevron-right"></i></a>
                    <a href="#"><span>Livin' by Mandiri</span> <i class="bi bi-chevron-right"></i></a>
                </div>
            </div>
            <div class="modal-footer">
                <div class="total-payment-display">
                    <span class="total-label">Total Pembayaran</span>
                    <span class="total-amount">Rp 203.000</span>
                </div>
                <button id="checkTransactionButton">Cek Transaksi</button>
            </div>
        </div>

        <!-- STEP 5 (Final): Pending -->
        <div id="modalStep5" class="modal-step">
            <div class="modal-header">
                <button id="backToStep4" class="modal-back-button"><i class="bi bi-chevron-left"></i>
                    Kembali</button>
                <h3>ZYPIA</h3>
                <i class="bi bi-three-dots-vertical" style="color: #6b7280;"></i>
            </div>
            <div class="pending-final-status">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div class="amount">
                    <span>Rp</span>
                    <span>203.000</span>
                </div>
                <span class="status-label">Status Pembayaran</span>
                <h4 class="status-text">Pending</h4>

                <div class="pending-details">
                    <div>
                        <span>Nama</span>
                        <span>NEOBLUE</span>
                    </div>
                    <div>
                        <span>Nomor Invoice</span>
                        <span>1761576... <i class="bi bi-clipboard" title="Salin"></i></span>
                    </div>
                    <div>
                        <span>Tanggal Transaksi</span>
                        <span>27 Okt 2025, 21.42.25</span>
                    </div>
                </div>

                <p style="font-size: 0.875rem; color: #4b5563;">Transaksi Pending, Silahkan selesaikan pembayaran anda.
                </p>
                <img src="https://placehold.co/60x30/f0f0f0/00529B?text=duitku" alt="duitku logo"
                    class="duitku-logo">
            </div>
            <div class="modal-footer" style="padding: 1rem; background: #f1f5f9;">
                <button id="closeModalButtonFinal" class="modal-button-full">Tutup</button>
            </div>
        </div>

    </div>
</div>
<!-- [ AKHIR MODAL PEMBAYARAN ] -->


<!-- JavaScript untuk Modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil elemen-elemen modal
        const paymentModal = document.getElementById('paymentModal');
        const subscribeButton = document.getElementById('subscribeButton');
        const closeModalButton = document.getElementById('closeModalButton');
        const closeModalButtonFinal = document.getElementById('closeModalButtonFinal');

        // Ambil semua step modal
        const modalStep1 = document.getElementById('modalStep1');
        const modalStep2 = document.getElementById('modalStep2');
        const modalStep3 = document.getElementById('modalStep3');
        const modalStep4 = document.getElementById('modalStep4');
        const modalStep5 = document.getElementById('modalStep5');
        const allSteps = [modalStep1, modalStep2, modalStep3, modalStep4, modalStep5];

        // Fungsi helper untuk pindah step
        function goToStep(stepToShow) {
            allSteps.forEach(step => {
                if (step) step.style.display = 'none';
            });
            if (stepToShow) stepToShow.style.display = 'block';
        }

        // --- Tombol Navigasi ---
        if (subscribeButton) {
            subscribeButton.addEventListener('click', () => {
                goToStep(modalStep1); // Selalu mulai dari step 1
                paymentModal.style.display = 'flex';
            });
        }

        const paymentButton = document.getElementById('paymentButton');
        if (paymentButton) paymentButton.addEventListener('click', () => goToStep(modalStep2));

        document.querySelectorAll('.payment-method-item').forEach(button => {
            button.addEventListener('click', () => goToStep(modalStep3));
        });

        const confirmPaymentButton = document.getElementById('confirmPaymentButton');
        if (confirmPaymentButton) confirmPaymentButton.addEventListener('click', () => goToStep(modalStep4));

        const checkTransactionButton = document.getElementById('checkTransactionButton');
        if (checkTransactionButton) checkTransactionButton.addEventListener('click', () => goToStep(
        modalStep5));

        // --- Tombol Kembali ---
        const backToStep1 = document.getElementById('backToStep1');
        if (backToStep1) backToStep1.addEventListener('click', () => goToStep(modalStep1));

        const backToStep2 = document.getElementById('backToStep2');
        if (backToStep2) backToStep2.addEventListener('click', () => goToStep(modalStep2));

        const backToStep3 = document.getElementById('backToStep3');
        if (backToStep3) backToStep3.addEventListener('click', () => goToStep(modalStep3));

        const backToStep4 = document.getElementById('backToStep4');
        if (backToStep4) backToStep4.addEventListener('click', () => goToStep(modalStep4));

        // --- Tombol Tutup Modal (X) ---
        function hideModal() {
            paymentModal.style.display = 'none';
            setTimeout(() => goToStep(modalStep1), 200);
        }

        if (closeModalButton) closeModalButton.addEventListener('click', hideModal);
        if (closeModalButtonFinal) closeModalButtonFinal.addEventListener('click', hideModal);

        // Klik di luar modal untuk menutup
        paymentModal.addEventListener('click', (event) => {
            if (event.target === paymentModal) {
                hideModal();
            }
        });
    });
</script>
