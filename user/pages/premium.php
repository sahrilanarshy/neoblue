<main class="dashboard-content">
    <div class="premium-header">
        <h1>Upgrade ke Premium</h1>
        <p>Maksimalkan persiapan UTBK - SNBT dengan fitur Premium Lengkap</p>
        <a href="#" class="btn-promo">Promo Terbatas!</a>
    </div>

    <div class="premium-layout">
        <div class="paket-premium-card">
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

<div id="manualPaymentModal" class="modal-overlay">
    <div class="modal-content-manual">
        <div class="modal-header">
            <h3>Konfirmasi Pembayaran Manual</h3>
            <button id="closeManualModal" class="modal-close-button">&times;</button>
        </div>

        <form id="manualPaymentForm" class="modal-body-manual">

            <div id="form-content">
                <p>Silakan lakukan transfer sebesar <strong>Rp 199.000</strong> ke salah satu rekening di bawah ini:
                </p>

                <ul class="rekening-list">
                    <li><strong>BNI:</strong> 123456789 (a/n Sahril Sidik)</li>
                    <li><strong>BRI:</strong> 987654321 (a/n Sahril Sidik)</li>
                    <li><strong>ShopeePay:</strong> 08123456789 (a/n Sahril Sidik)</li>
                    <li><strong>DANA:</strong> 08123456789 (a/n Sahril Sidik)</li>
                </ul>

                <hr class="divider">

                <div class="form-grup">
                    <label for="paymentMethod">Metode yang Digunakan</label>
                    <select id="paymentMethod" name="metode" required>
                        <option value="">-- Pilih Metode --</option>
                        <option value="bni">BNI</option>
                        <option value="bri">BRI</option>
                        <option value="shopeepay">ShopeePay</option>
                        <option value="dana">DANA</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="form-grup">
                    <label for="proofOfPayment">Upload Bukti Pembayaran</label>
                    <input type="file" id="proofOfPayment" name="bukti" accept="image/*" required>
                    <small>Hanya file gambar (jpg, png, dll).</small>
                </div>

                <div class="form-grup">
                    <label for="notes">Catatan (Opsional)</label>
                    <textarea id="notes" name="catatan" rows="3" placeholder="Misal: Nama pengirim, dll."></textarea>
                </div>

                <button type="submit" class="btn-kirim-konfirmasi">Saya Sudah Transfer</button>
            </div>

            <div id="success-message">
                <i class="bi bi-check-circle-fill"></i>
                <h4 style="margin-top: 1rem;">Konfirmasi Terkirim!</h4>
                <p>Terima kasih. Pembayaran Anda akan segera kami verifikasi dalam 1x24 jam.</p>
                <button type="button" id="closeAfterSuccess" class="btn-kirim-konfirmasi">Tutup</button>
            </div>

        </form>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil elemen-elemen modal baru
        const manualPaymentModal = document.getElementById('manualPaymentModal');
        const subscribeButton = document.getElementById('subscribeButton');
        const closeManualModal = document.getElementById('closeManualModal');

        // Form dan elemen di dalamnya
        const manualPaymentForm = document.getElementById('manualPaymentForm');
        const formContent = document.getElementById('form-content');
        const successMessage = document.getElementById('success-message');
        const closeAfterSuccess = document.getElementById('closeAfterSuccess');

        // Fungsi untuk menampilkan modal
        function showModal() {
            if (manualPaymentModal) {
                manualPaymentModal.style.display = 'flex';
                // Reset form jika modal ditutup lalu dibuka lagi
                formContent.style.display = 'block';
                successMessage.style.display = 'none';
                manualPaymentForm.reset();
            }
        }

        // Fungsi untuk menyembunyikan modal
        function hideModal() {
            if (manualPaymentModal) {
                manualPaymentModal.style.display = 'none';
            }
        }

        // --- Event Listeners ---

        // Tampilkan modal saat tombol "Langganan Sekarang" diklik
        if (subscribeButton) {
            subscribeButton.addEventListener('click', showModal);
        }

        // Sembunyikan modal saat tombol 'X' diklik
        if (closeManualModal) {
            closeManualModal.addEventListener('click', hideModal);
        }

        // Sembunyikan modal saat tombol 'Tutup' di pesan sukses diklik
        if (closeAfterSuccess) {
            closeAfterSuccess.addEventListener('click', hideModal);
        }

        // Sembunyikan modal saat klik di luar area konten modal
        if (manualPaymentModal) {
            manualPaymentModal.addEventListener('click', (event) => {
                if (event.target === manualPaymentModal) {
                    hideModal();
                }
            });
        }

        // --- Penanganan Submit Form ---
        if (manualPaymentForm) {
            manualPaymentForm.addEventListener('submit', function(event) {
                // Hentikan pengiriman form bawaan
                event.preventDefault();

                //
                // !! PENTING: Di sinilah Anda seharusnya mengirim data 
                //    form (termasuk file) ke server (backend) Anda.
                //

                // Untuk sekarang, kita hanya simulasikan sukses di frontend:
                // 1. Sembunyikan form
                formContent.style.display = 'none';

                // 2. Tampilkan pesan sukses
                successMessage.style.display = 'block';
            });
        }
    });
</script>
