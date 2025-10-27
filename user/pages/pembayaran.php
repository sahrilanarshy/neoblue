<body style="background-color:rgb(245, 245, 245);">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            padding-top: 105px;
            padding-bottom: 104px;
        }

        .navbar {
            background-color: #007bff;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }

        .menu {
            color: #fff !important;
        }

        @media (max-width: 768px) {

            .navbar-nav {
                padding-left: 0;
                padding-right: 0;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .nav-link {
            font-size: 16px;
            font-weight: 700;
            padding: 16px !important;
        }

        .btn-outline-light {
            display: inline-block;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Nunito', sans-serif;
            background: transparent;
            border: none;
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
        }

        .btn-outline-light:hover {
            color: #007bff !important;
        }

        .navbar-brand {
            font-size: 35px;
            margin-right: 0px;
            color: #fff !important
        }

        .btn-outline-light i {
            margin-right: 8px;
        }

        @media (max-width: 991px) {
            .btn-white {
                font-size: 14px !important;
            }

            .nav-link {
                padding: 5px 0 5px 0 !important;
            }
        }

        @media (min-width: 992px) {
            .navbar-brand {
                margin-right: 0px;
            }

            body {
                padding-bottom: 26px !important;
            }
        }

        .container {
            padding-right: 16px !important;
            padding-left: 16px !important;
        }

        .btn-white {
            background-color: white;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 700;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .btn-white:hover {
            background-color: white !important;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            font-weight: 700;
            padding: 10px 20px;
            border-radius: 50px;
            color: white;
            text-align: center;
            text-decoration: none;
        }

        ul.list-unstyled li {
            margin-bottom: 10px;
        }

        @media (max-width: 575px) {
            .container {
                padding-left: 26px !important;
                padding-right: 26px !important;
            }
        }

        @media (min-width: 992px) {
            .start {
                max-width: 688px !important;
                margin-left: auto;
                margin-right: auto;
            }
        }

        .point-popup-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .4);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 12000;
        }

        .point-popup {
            background: #fff;
            border-radius: 12px;
            padding: 22px 20px;
            width: 320px;
            max-width: 92%;
            text-align: center;
            box-shadow: 0 8px 30px -5px rgba(0, 0, 0, .25);
        }
    </style>

    <div class="start">
        <div class="container" style="padding-top:16px; padding-bottom:30px;">

            <style>
                .paket-card-wrapper {
                    background: #fff;
                    border-radius: 12px;
                    border: none;
                    margin-bottom: 24px;
                    padding: 1rem;
                }

                .paket-select-section {
                    display: flex;
                    gap: 16px;
                    justify-content: flex-start;
                }

                .paket-card {
                    flex: 1 1 0;
                    background: #fff;
                    border: 2px solid #e5e7eb;
                    border-radius: 12px;
                    padding: 20px 18px 16px 18px;
                    cursor: pointer;
                    transition: border-color 0.2s;
                    min-width: 180px;
                    max-width: 260px;
                    position: relative;
                    box-shadow: none;
                }

                .paket-card.selected {
                    border-color: #2563eb;
                }

                .paket-card .paket-title {
                    font-weight: bold;
                    font-size: 16px;
                    margin-bottom: 6px;
                }

                .paket-card .paket-harga {
                    font-size: 18px;
                    font-weight: bold;
                    color: #2563eb;
                }

                .paket-card .paket-harga-bulanan {
                    font-size: 14px;
                    color: #6b7280;
                    margin-left: 6px;
                    text-decoration: none;
                }

                .paket-card .paket-info {
                    font-size: 13px;
                    color: #6b7280;
                    margin-top: 4px;
                }

                .paket-card .paket-badge {
                    position: absolute;
                    top: 12px;
                    right: 12px;
                    background: #2563eb;
                    color: #fff;
                    font-size: 11px;
                    padding: 2px 8px;
                    border-radius: 8px;
                    font-weight: 500;
                }

                @media (max-width: 600px) {
                    .paket-select-section {
                        flex-direction: column;
                        gap: 10px;
                    }

                    .paket-card {
                        max-width: 100%;
                    }
                }
            </style>

            <div class="paket-card-wrapper">
                <div style="margin-bottom:12px; font-weight:bold; font-size:17px; color:#222;">Paket Premium</div>
                <div class="paket-select-section" id="paketSelectSection">
                    <div class="paket-card" id="paketBulanan">
                        <div class="paket-title">Bulanan</div>
                        <div class="paket-harga">Rp 49.000<span class="paket-harga-bulanan">/bulan</span></div>
                        <div class="paket-info">Pembayaran setiap bulan</div>
                    </div>
                    <div class="paket-card selected" id="paket8Bulan">
                        <div class="paket-title">8 Bulan</div>
                        <div class="paket-harga">Rp 25.000 <span class="paket-harga-bulanan">/bulan</span></div>
                        <div class="paket-info">Pembayaran sekali Rp 199.000</div>
                        <div class="paket-badge">Rekomendasi</div>
                    </div>
                </div>
            </div>
            <div class="card" style="border:none; border-radius:10px;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3" style="font-size:16px;">Pembayaran</h5>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between" style="font-size:14px;">
                            <div>Nama Paket</div>
                            <div class="fw-bold">Premium (8 Bulan)</div>
                        </div>
                        <div class="d-flex justify-content-between" style="font-size:14px;">
                            <div>Harga Paket</div>
                            <div class="fw-bold" style="text-decoration:line-through;">Rp 1.000.000</div>
                        </div>
                        <div class="d-flex justify-content-between" style="font-size:14px;">
                            <div>Harga Promo</div>
                            <div class="fw-bold">Rp 199.000</div>
                        </div>
                        <div class="d-flex justify-content-between" style="font-size:14px;">
                            <div>Aktif Hingga</div>
                            <div class="fw-bold">27/06/2026</div>
                        </div>
                    </div>
                    <form method="post" autocomplete="off" class="mb-3">
                        <input type="hidden" name="_token"
                            value="80d0078dafc9dcc81448c67548f0d88f7750bac5992f0ff7f46048c7bdc6cfd9"> <input
                            type="hidden" name="action" value="apply" />
                        <label for="voucher" class="form-label fw-bold" style="font-size:14px;">Kode Voucher</label>
                        <div class="input-group">
                            <input type="text" id="voucher" name="voucher" class="form-control"
                                placeholder="Masukkan kode" value=""
                                style="text-transform:uppercase; border-top-left-radius:10px; border-bottom-left-radius:10px; font-size:14px;" />
                            <button class="btn btn-primary" type="submit"
                                style="font-size:13px; border-top-right-radius:10px; border-bottom-right-radius:10px;">Terapkan</button>
                        </div>
                    </form>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between" style="white-space:nowrap; font-size:14px;">
                            <div>Subtotal</div>
                            <div class="fw-bold">Rp 199.000</div>
                        </div>
                        <hr />
                        <div class="d-flex justify-content-between" style="white-space:nowrap; font-size:14px;">
                            <div>Total</div>
                            <div class="fw-bold">Rp 199.000</div>
                        </div>
                    </div>
                    <div>
                        <button id="btnCheckout" class="btn btn-primary w-100"
                            style="font-size:14px; border-radius:10px;">Langganan Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        .bottombar {
            display: none;
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: rgb(255, 255, 255);
            z-index: 1030;
        }

        .bottombar .nav-item {
            flex: 1;
            text-align: center;
            padding: 10px 0;
        }

        .bottombar .nav-link {
            color: #6c757d;
            font-size: 13px;
            font-weight: 700;
        }

        .bottombar .nav-link i {
            font-size: 20px;
            display: block;
            color: #6c757d;
        }

        @media (max-width: 991px) {
            .bottombar {
                display: flex;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<script src="https://app-prod.duitku.com/lib/js/duitku.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var bulanan = document.getElementById('paketBulanan');
        var delapan = document.getElementById('paket8Bulan');
        if (bulanan && delapan) {
            bulanan.addEventListener('click', function() {
                bulanan.classList.add('selected');
                delapan.classList.remove('selected');
                window.location.href = 'pembayaran-bulanan.php';
            });
            delapan.addEventListener('click', function() {
                delapan.classList.add('selected');
                bulanan.classList.remove('selected');
                window.scrollTo({
                    top: document.querySelector('.card').offsetTop - 40,
                    behavior: 'smooth'
                });
            });
        }
    });
</script>
<script>
    (async function() {
        const btn = document.getElementById('btnCheckout');
        if (!btn) return;
        btn.addEventListener('click', async function() {
            btn.disabled = true;
            btn.innerText = 'Memproses...';
            try {
                const tRes = await fetch('payment/csrf.php', {
                    credentials: 'same-origin'
                });
                const tJson = await tRes.json();
                const token = tJson && tJson.token ? tJson.token : null;
                if (!token) throw new Error('Gagal membuat token');
                const body = new URLSearchParams();
                body.set('_token', token);
                body.set('voucher_code', (document.getElementById('voucher').value || '')
                    .toUpperCase());
                const res = await fetch('payment/create.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data && data.error ? data.error :
                    'Gagal membuat pembayaran');
                const reference = data.reference;
                const paymentUrl = data.paymentUrl;
                if (window.checkout && window.checkout.process && reference) {
                    window.checkout.process(reference, {
                        successEvent: function() {
                            window.location.href = 'profil.php?paid=1';
                        },
                        pendingEvent: function() {
                            window.location.href = 'profil.php?pending=1';
                        },
                        errorEvent: function() {
                            alert('Pembayaran gagal/dibatalkan, silahkan coba lagi');
                            btn.disabled = false;
                            btn.innerText = 'Langganan Sekarang';
                        },
                        closeEvent: function() {
                            btn.disabled = false;
                            btn.innerText = 'Langganan Sekarang';
                        }
                    });
                } else if (paymentUrl) {
                    window.location.href = paymentUrl;
                } else {
                    throw new Error('Integrasi pembayaran tidak siap');
                }
            } catch (e) {
                alert(e.message || 'Terjadi kesalahan');
                btn.disabled = false;
                btn.innerText = 'Langganan Sekarang';
            }
        });
    })();
</script>
