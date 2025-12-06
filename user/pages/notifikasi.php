<?php
// Set waktu saat ini sebagai waktu terakhir melihat notifikasi
$_SESSION['last_notification_view'] = date('Y-m-d H:i:s');
?>
<main class="dashboard-content">
    <h1 class="page-title">Notifikasi</h1>

    <div class="notification-list" id="notificationList">
        <!-- Notifikasi akan dimuat di sini oleh JavaScript -->
        <p id="loading-message" class="text-center text-muted">Memuat notifikasi...</p>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationList = document.getElementById('notificationList');
    const loadingMessage = document.getElementById('loading-message');

    async function loadNotifications() {
        try {
            // Panggil API notifikasi khusus user (kirim cookie session agar server tahu user yang login)
            const response = await fetch('../api/api_notifikasi.php', { credentials: 'same-origin' });
            if (!response.ok) {
                throw new Error('Gagal memuat data notifikasi.');
            }
            const result = await response.json();

            loadingMessage.style.display = 'none';

            if (result.status === 'success' && result.data.length > 0) {
                result.data.forEach(item => {
                    const date = new Date(item.created_at);
                    const formattedDate = `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`;

                    const notificationItem = document.createElement('div');
                    notificationItem.className = 'notification-item';

                    let content = `
                        <p class="notification-date">${formattedDate}</p>
                        <h2 class="notification-title">${item.title}</h2>
                        <p class="notification-body">${item.message}</p>
                    `;

                    // Tambahkan tombol hapus kecil (icon trash) untuk semua notifikasi jika ada id
                    let deleteButtonHtml = '';
                    const isGlobal = (item.is_global && item.is_global == 1) || (typeof item.id === 'string' && item.id.startsWith('peng_'));
                    if (item.id !== undefined && item.id !== null && String(item.id).length > 0) {
                        // Gunakan icon-only (tanpa elemen <button>) agar tidak ada kotak
                        deleteButtonHtml = `<i class="bi bi-trash btn-delete-icon" data-id="${item.id}" data-global="${isGlobal?1:0}" title="Hapus notifikasi"></i>`;
                    }

                    // Tampilkan hanya tanggal, judul, dan isi serta tombol hapus
                    notificationItem.innerHTML = content + deleteButtonHtml;
                    notificationList.appendChild(notificationItem);

                    // Saat user mengklik item notifikasi, tandai pengumuman global sebagai dibaca
                    notificationItem.addEventListener('click', async function(ev) {
                        // jika klik diarahkan pada tombol delete, biarkan handler delete menangani
                        if (ev.target && (ev.target.classList.contains('btn-delete-icon') || ev.target.closest('.btn-delete-icon'))) return;
                        try {
                            const nid = item.id;
                            const isG = (item.is_global && item.is_global == 1) || (typeof item.id === 'string' && item.id.startsWith('peng_'));
                            if (isG && nid) {
                                // panggil API mark read (server-side)
                                await fetch('../api/api_mark_pengumuman_read.php', {
                                    method: 'POST',
                                    credentials: 'same-origin',
                                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                                    body: `peng_id=${encodeURIComponent(nid)}`
                                });
                                // opsional: ubah tampilan sementara
                                notificationItem.classList.add('notif-read');
                                // update dot
                                fetch('../api/api_check_new_notifications.php', { credentials: 'same-origin' });
                            }
                        } catch (err) {
                            console.error('Gagal mark read pengumuman', err);
                        }
                    });

                    // Tombol hapus (jika ada) — behaviour berbeda untuk personal vs global
                    if (deleteButtonHtml) {
                        const btn = notificationItem.querySelector('.btn-delete-icon');
                        if (btn) {
                            btn.addEventListener('click', function(ev) {
                                ev.stopPropagation();
                                const nid = this.getAttribute('data-id');
                                const isG = this.getAttribute('data-global') === '1';
                                // Simpan info target untuk modal
                                window._notifToDelete = { nid: nid, isGlobal: isG, item: notificationItem };
                                const modalEl = document.getElementById('confirmDeleteModal');
                                if (modalEl) {
                                    const msg = isG ? 'Sembunyikan pengumuman ini secara permanen untuk akun Anda?' : 'Hapus notifikasi ini?';
                                    modalEl.querySelector('#confirmDeleteMessage').textContent = msg;
                                    const modal = new bootstrap.Modal(modalEl);
                                    modal.show();
                                } else {
                                    // fallback ke behaviour lama bila modal tidak ditemukan
                                    (async function(){
                                        if (isG) {
                                            if (!confirm('Sembunyikan pengumuman ini secara permanen untuk akun Anda?')) return;
                                            try {
                                                const resp = await fetch('../api/api_hide_pengumuman.php', { method: 'POST', credentials: 'same-origin', headers: {'Content-Type':'application/x-www-form-urlencoded'}, body: `peng_id=${encodeURIComponent(nid)}` });
                                                const jr = await resp.json();
                                                if (jr.status === 'success') { notificationItem.remove(); fetch('../api/api_check_new_notifications.php', { credentials: 'same-origin' }); }
                                                else { alert(jr.message || 'Gagal menyembunyikan pengumuman.'); }
                                            } catch (err) { console.error(err); alert('Terjadi kesalahan koneksi saat menyembunyikan pengumuman.'); }
                                        } else {
                                            if (!confirm('Hapus notifikasi ini?')) return;
                                            try {
                                                const resp = await fetch('../api/api_delete_notification.php', { method: 'POST', credentials: 'same-origin', headers: {'Content-Type':'application/x-www-form-urlencoded'}, body: `notif_id=${encodeURIComponent(nid)}` });
                                                const r = await resp.json();
                                                if (r.status === 'success') { notificationItem.remove(); fetch('../api/api_check_new_notifications.php', { credentials: 'same-origin' }); }
                                                else { alert('Gagal menghapus notifikasi.'); }
                                            } catch (err) { console.error(err); alert('Terjadi kesalahan koneksi.'); }
                                        }
                                    })();
                                }
                            });
                        }
                    }
                });
            } else {
                notificationList.innerHTML = '<p class="text-center text-muted">Belum ada notifikasi saat ini.</p>';
            }
        } catch (error) {
            loadingMessage.textContent = 'Gagal memuat notifikasi. Silakan coba lagi nanti.';
        }
    }

    loadNotifications();

    // Handler untuk tombol Konfirmasi Hapus pada modal
    const confirmBtn = document.getElementById('confirmDeleteButton');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', async function() {
            const info = window._notifToDelete;
            if (!info) return;
            const { nid, isGlobal, item } = info;
            this.disabled = true;
            try {
                if (isGlobal) {
                    const resp = await fetch('../api/api_hide_pengumuman.php', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {'Content-Type':'application/x-www-form-urlencoded'},
                        body: `peng_id=${encodeURIComponent(nid)}`
                    });
                    const jr = await resp.json();
                    if (jr.status === 'success') {
                        if (item && item.remove) item.remove();
                        fetch('../api/api_check_new_notifications.php');
                    } else {
                        alert(jr.message || 'Gagal menyembunyikan pengumuman.');
                    }
                } else {
                    const resp = await fetch('../api/api_delete_notification.php', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {'Content-Type':'application/x-www-form-urlencoded'},
                        body: `notif_id=${encodeURIComponent(nid)}`
                    });
                    const r = await resp.json();
                    if (r.status === 'success') {
                        if (item && item.remove) item.remove();
                        fetch('../api/api_check_new_notifications.php');
                    } else {
                        alert('Gagal menghapus notifikasi.');
                    }
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan koneksi.');
            } finally {
                this.disabled = false;
                window._notifToDelete = null;
                const modalEl = document.getElementById('confirmDeleteModal');
                if (modalEl) {
                    const bs = bootstrap.Modal.getInstance(modalEl);
                    if (bs) bs.hide();
                }
            }
        });
    }
});
</script>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <p id="confirmDeleteMessage">Apakah Anda yakin ingin menghapus?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteButton">Hapus</button>
      </div>
    </div>
  </div>
</div>
</script>