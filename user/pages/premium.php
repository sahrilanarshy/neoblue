<main class="dashboard-content">
    <div class="premium-header">
        <h1>Upgrade ke Premium</h1>
        <p>Maksimalkan persiapan UTBK - SNBT dengan fitur Premium Lengkap</p>
        <a href="#" class="btn-promo">Promo Terbatas!</a>
    </div>

    <div class="premium-layout">
        <div class="paket-premium-card">
            <h2 id="paket-nama">Memuat...</h2>
            <div class="harga">
                <span class="harga-dicoret" id="paket-harga-normal" style="display: none;"></span>
                <span class="harga-promo" id="paket-harga-promo">Rp...</span>
            </div>
            <p class="info-berlaku" id="paket-info-berlaku">Memuat detail...</p>

            <ul class="fitur-list" id="paket-fitur-list">
                <li><i class="bi bi-arrow-repeat"></i> Memuat fitur...</li>
            </ul>
            <!-- Hidden input to store package id -->
            <input type="hidden" id="selected-paket-id" value="">
        </div>

        <div class="pembayaran-card">
            <h3>Detail Pembayaran</h3>

            <div class="ringkasan-harga">
                <div class="item-harga">
                    <span>Paket Premium</span>
                    <span id="summary-harga-normal">Rp...</span>
                </div>
                <div class="item-harga">
                    <span>Promo</span>
                    <span id="summary-promo">Rp...</span>
                </div>
                <div class="total-harga">
                    <span>Total</span>
                    <span class="harga-final" id="summary-total">Rp...</span>
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
            <h3>Konfirmasi Pembayaran</h3>
            <button id="closeManualModal" class="modal-close-button">&times;</button>
        </div>

        <form id="manualPaymentForm" class="modal-body-manual">

            <div id="form-content">
                <p>Silakan lakukan transfer sebesar <strong id="modal-total-harga">Rp...</strong> ke salah satu rekening di bawah ini:
                </p>

                <ul class="rekening-list" id="rekening-list">
                    <li>Memuat metode pembayaran...</li>
                </ul>

                <hr class="divider">

                <div class="form-grup">
                    <label for="paymentMethod">Metode yang Digunakan</label>
                    <select id="paymentMethod" name="metode_pembayaran_id" required>
                        <option value="">-- Pilih Metode --</option>
                    </select>
                </div>

                <div class="form-grup">
                    <label for="proofOfPayment">Upload Bukti Pembayaran</label>
                    <input type="file" id="proofOfPayment" name="bukti_pembayaran" accept="image/jpeg,image/png,application/pdf" required>
                    <small>Hanya file JPG, PNG, atau PDF (Maks 2MB).</small>
                </div>

                <div class="form-grup">
                    <label for="notes">Catatan (Opsional)</label>
                    <textarea id="notes" name="catatan" rows="3" placeholder="Misal: Nama pengirim, dll."></textarea>
                </div>

                <button type="submit" id="submit-payment-btn" class="btn-kirim-konfirmasi">Saya Sudah Transfer</button>
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
    document.addEventListener('DOMContentLoaded', async function() {
        // --- Elemen UI ---
        const manualPaymentModal = document.getElementById('manualPaymentModal');
        const subscribeButton = document.getElementById('subscribeButton');
        const closeManualModal = document.getElementById('closeManualModal');
        const rekeningList = document.getElementById('rekening-list');
        const paymentMethodSelect = document.getElementById('paymentMethod');
        const modalTotalHarga = document.getElementById('modal-total-harga');
        const selectedPaketIdInput = document.getElementById('selected-paket-id');
        const submitPaymentBtn = document.getElementById('submit-payment-btn');

        let selectedPaket = null; // Untuk menyimpan data paket yang dipilih

        // Form dan elemen di dalamnya
        const manualPaymentForm = document.getElementById('manualPaymentForm');
        const formContent = document.getElementById('form-content');
        const successMessage = document.getElementById('success-message');
        const closeAfterSuccess = document.getElementById('closeAfterSuccess');

        // --- Fungsi Helper ---
        const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);

        // --- Fungsi Load Data ---
        // Gabungkan pemanggilan paket + metode pembayaran menjadi 1 request (lebih efisien untuk mobile)
        async function loadPremiumData() {
            try {
                const response = await fetch('../api/api_premium_data.php');
                const result = await response.json();
                if (result.status === 'success') {
                    const paketList = result.data.paket || [];
                    const metodeList = result.data.metode_pembayaran || [];

                    if (paketList.length > 0) {
                        selectedPaket = paketList.find(p => p.is_unggulan == '1' || p.is_unggulan === 1) || paketList[0];
                        if (selectedPaket) updatePaketUI(selectedPaket);
                    } else {
                        console.error('Tidak ada data paket yang ditemukan.');
                    }

                    if (metodeList.length > 0) {
                        updateMetodePembayaranUI(metodeList);
                    } else {
                        rekeningList.innerHTML = '<li>Metode pembayaran tidak tersedia saat ini.</li>';
                    }
                } else {
                    console.error('Gagal memuat data premium:', result.message || 'Unknown');
                }
            } catch (error) {
                console.error('Gagal memuat data premium:', error);
            }
        }

        // --- Fungsi Update UI ---
        function updatePaketUI(paket) {
            const hargaNormal = parseFloat(paket.harga_normal || paket.harga); // Asumsi ada harga normal
            const hargaPromo = parseFloat(paket.harga);
            const diskon = hargaNormal - hargaPromo;

            document.getElementById('paket-nama').textContent = paket.nama_paket;
            document.getElementById('paket-harga-promo').textContent = formatRupiah(hargaPromo);
            if (hargaNormal > hargaPromo) {
                const hargaNormalEl = document.getElementById('paket-harga-normal');
                hargaNormalEl.textContent = formatRupiah(hargaNormal);
                hargaNormalEl.style.display = 'inline';
            }
            document.getElementById('paket-info-berlaku').textContent = `Berlaku untuk ${paket.nama_paket}`;
            
            // Update fitur list
            const fiturList = document.getElementById('paket-fitur-list');
            fiturList.innerHTML = '';
            paket.fitur.forEach(fitur => {
                fiturList.innerHTML += `<li><i class="bi bi-check-circle-fill"></i> ${fitur.nama_fitur}</li>`;
            });

            // Update ringkasan pembayaran
            document.getElementById('summary-harga-normal').textContent = formatRupiah(hargaNormal);
            document.getElementById('summary-promo').textContent = `- ${formatRupiah(diskon)}`;
            document.getElementById('summary-total').textContent = formatRupiah(hargaPromo);
            
            // Update total di modal
            modalTotalHarga.textContent = formatRupiah(hargaPromo);

            // Simpan ID paket yang dipilih
            selectedPaketIdInput.value = paket.id;
        }

        function updateMetodePembayaranUI(metodeList) {
            rekeningList.innerHTML = '';
            paymentMethodSelect.innerHTML = '<option value="">-- Pilih Metode --</option>';

            metodeList.forEach(metode => {
                // Tambahkan ke daftar rekening di modal
                rekeningList.innerHTML += `<li><strong>${metode.nama_metode}:</strong> ${metode.nomor_rekening} (a/n ${metode.atas_nama})</li>`;
                
                // Tambahkan ke dropdown select
                const option = document.createElement('option');
                option.value = metode.id;
                option.textContent = `${metode.nama_metode} - ${metode.nomor_rekening}`;
                paymentMethodSelect.appendChild(option);
            });
        }

        function showToast(message, isSuccess = true) {
            const toast = document.createElement('div');
            toast.className = `toast-notification ${isSuccess ? 'success' : 'error'}`;
            toast.innerHTML = `<i class="bi ${isSuccess ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'}"></i> ${message}`;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => document.body.removeChild(toast), 500);
            }, 3000);
        }

        // Fungsi untuk menampilkan modal
        function showModal() {
            if (manualPaymentModal) {
                manualPaymentModal.style.display = 'flex';
                // Reset form jika modal ditutup lalu dibuka lagi
                formContent.style.display = 'flex';
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
            manualPaymentForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                const originalButtonText = submitPaymentBtn.innerHTML;
                submitPaymentBtn.disabled = true;
                submitPaymentBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Mengirim...';

                const formData = new FormData(manualPaymentForm);
                formData.append('paket_id', selectedPaketIdInput.value);

                try {
                    const response = await fetch('../api/api_submit_pembayaran.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (response.ok && result.status === 'success') {
                        formContent.style.display = 'none';
                        successMessage.style.display = 'block';
                    } else {
                        // Tampilkan pesan error dari API
                        showToast(result.message || 'Terjadi kesalahan.', false);
                    }

                } catch (error) {
                    console.error('Submit error:', error);
                    showToast('Gagal terhubung ke server. Silakan coba lagi.', false);
                } finally {
                    submitPaymentBtn.disabled = false;
                    submitPaymentBtn.innerHTML = originalButtonText;
                }
            });
        }

        // --- Inisialisasi ---
        // Panggil fungsi untuk memuat semua data yang diperlukan
        loadPremiumData();
        loadPaymentMethods();
    });
</script>
<style>
    /* Toast Notification Style */
    .toast-notification.success { background-color: #22c55e; }
    .toast-notification.error { background-color: #ef4444; }
    #form-content { flex-direction: column; }
</style>
