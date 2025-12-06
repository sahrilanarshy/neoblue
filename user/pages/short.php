<?php
// 1. Ambil data shorts dari database
// Variabel $koneksi sudah tersedia dari index.php
$query_shorts = mysqli_query($koneksi, "SELECT * FROM shorts ORDER BY id DESC");
$shorts_list = mysqli_fetch_all($query_shorts, MYSQLI_ASSOC);

// 2. Dapatkan status langganan pengguna dari sesi
$is_premium = (isset($_SESSION['tipe_user']) && $_SESSION['tipe_user'] == 'Premium');

?>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-EHWNSZCDXT"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-EHWNSZCDXT');
</script>

<body style="background-color:rgb(245, 245, 245);">

    <div class="start">
        <div class="shorts-outer">
            <div class="shorts-inner" id="shorts-inner">
                <?php if (empty($shorts_list)): ?>
                    <div class="short-item active">
                        <div class="short-video-frame" style="display:flex; flex-direction:column; gap:22px; color:#fff; text-align:center; padding:48px 28px; font-size:16px; line-height:1.4;">
                            Belum ada video short yang tersedia.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($shorts_list as $index => $short): ?>
                        <?php
                        // 3. Lewati video premium jika pengguna bukan premium
                        if ($short['tipe'] === 'Premium' && !$is_premium) {
                            continue;
                        }
                        ?>
                        <div class="short-item <?= $index === 0 ? 'active' : ''; ?>">
                            <div class="short-video-frame">
                                <div class="video-placeholder" id="placeholder-<?= $index; ?>">
                                    <div style="color: #555; font-size: 14px;">Memuat Video ...</div>
                                </div>
                                <video class="short-video" loop preload="none" playsinline webkit-playsinline
                                    disablepictureinpicture controlslist="nodownload noremoteplayback noplaybackrate" poster="" data-src="../<?= htmlspecialchars($short['video_path']); ?>">
                                </video>
                                <div class="short-progress-container">
                                    <div class="short-progress-bar">
                                        <div class="short-progress-handle"></div>
                                    </div>
                                    <div class="short-progress-time">0:00</div>
                                </div>
                                <div class="short-sound-btn" title="Suara">
                                    <i class="bi bi-volume-up-fill icon-up"></i>
                                    <i class="bi bi-volume-mute-fill icon-mute"></i>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (!$is_premium): ?>
                    <div class="short-item" data-upgrade="1">
                        <div class="short-video-frame"
                            style="display:flex;flex-direction:column;gap:22px;color:#fff;text-align:center;padding:48px 28px;font-size:16px;line-height:1.4;">
                            <div><strong>Upgrade ke Premium</strong> untuk menonton lebih banyak.</div>
                            <a href=".?hal=premium" class="btn fw-bold"
                                style="background:#0d6efd;color:#fff;border-radius:30px;padding:10px 26px;font-size:15px;">Upgrade
                                Sekarang</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Autoplay Notification -->
        <div class="autoplay-notification" id="autoplay-notification">
            Klik untuk memutar video
        </div>

        <script>
            const items = Array.from(document.querySelectorAll('.short-item'));
            const videos = Array.from(document.querySelectorAll('.short-item video'));
            const progressBars = Array.from(document.querySelectorAll('.short-progress-bar'));
            const progressContainers = Array.from(document.querySelectorAll('.short-progress-container'));
            const progressHandles = Array.from(document.querySelectorAll('.short-progress-handle'));
            const progressTimes = Array.from(document.querySelectorAll('.short-progress-time'));
            const placeholders = Array.from(document.querySelectorAll('.video-placeholder'));
            const autoplayNotification = document.getElementById('autoplay-notification');

            let currentIdx = 0;
            let lastPlaying = null;
            const isPremium = <?= json_encode($is_premium); ?>;
            let allowSound = true;
            let progressUpdateIntervals = [];
            let isDragging = false;
            let userInteracted = false;

            const upgradeIdx = (!isPremium) ? (items.findIndex(el => el.hasAttribute('data-upgrade')) !== -1 ? items.findIndex(
                el => el.hasAttribute('data-upgrade')) : (items.length - 1)) : -1;

            function isUpgradeGateActive() {
                return (!isPremium) && upgradeIdx >= 0 && currentIdx === upgradeIdx;
            }

            function updateSoundUI() {
                items.forEach((item, i) => {
                    const v = videos[i];
                    if (!v) return;
                    item.classList.toggle('muted', !!v.muted);
                    item.classList.toggle('unmuted', !v.muted);
                });
            }

            function formatTime(seconds) {
                const mins = Math.floor(seconds / 60);
                const secs = Math.floor(seconds % 60);
                return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
            }

            function updateProgressBar(video, progressBar, progressTime) {
                if (video.duration) {
                    const percentage = (video.currentTime / video.duration) * 100;
                    progressBar.style.width = percentage + '%';

                    if (progressTime && isDragging) {
                        progressTime.textContent = `${formatTime(video.currentTime)}`;
                    }
                }
            }

            function setupProgressBar(video, progressBar, progressContainer, progressHandle, progressTime) {
                const index = videos.indexOf(video);
                if (progressUpdateIntervals[index]) {
                    clearInterval(progressUpdateIntervals[index]);
                }

                progressUpdateIntervals[index] = setInterval(() => {
                    if (video.readyState > 0 && !isDragging) {
                        updateProgressBar(video, progressBar, progressTime);
                    }
                }, 100);

                const seekToPosition = (clientX, isFromDrag = false) => {
                    if (video.duration) {
                        const rect = progressContainer.getBoundingClientRect();
                        let clickPosition = (clientX - rect.left) / rect.width;
                        clickPosition = Math.max(0, Math.min(1, clickPosition));

                        video.currentTime = clickPosition * video.duration;
                        updateProgressBar(video, progressBar, progressTime);

                        const videoIndex = videos.indexOf(video);
                        if (videoIndex === currentIdx && (video.paused || isFromDrag)) {
                            playVideoWithSound(video);
                        }
                    }
                };

                progressContainer.addEventListener('mousedown', (e) => {
                    isDragging = true;
                    progressContainer.classList.add('dragging');
                    seekToPosition(e.clientX, true);
                    e.stopPropagation();
                });

                const handleMouseMove = (e) => {
                    if (isDragging) {
                        seekToPosition(e.clientX, true);
                    }
                };

                const handleMouseUp = () => {
                    if (isDragging) {
                        isDragging = false;
                        progressContainer.classList.remove('dragging');
                    }
                };

                document.addEventListener('mousemove', handleMouseMove);
                document.addEventListener('mouseup', handleMouseUp);

                progressContainer.addEventListener('touchstart', (e) => {
                    if (e.touches.length === 1) {
                        isDragging = true;
                        progressContainer.classList.add('dragging');
                        seekToPosition(e.touches[0].clientX, true);
                        e.stopPropagation();
                    }
                }, {
                    passive: false
                });

                const handleTouchMove = (e) => {
                    if (isDragging && e.touches.length === 1) {
                        seekToPosition(e.touches[0].clientX, true);
                    }
                };

                const handleTouchEnd = () => {
                    if (isDragging) {
                        isDragging = false;
                        progressContainer.classList.remove('dragging');
                    }
                };

                document.addEventListener('touchmove', handleTouchMove, {
                    passive: false
                });
                document.addEventListener('touchend', handleTouchEnd);

                progressContainer.addEventListener('click', (e) => {
                    if (!isDragging) {
                        seekToPosition(e.clientX, false);
                        e.stopPropagation();
                    }
                });
            }

            function setViewportBounds() {
                const outer = document.querySelector('.shorts-outer');
                if (!outer) return;
                const navbar = document.querySelector('.navbar');
                const top = navbar ? navbar.offsetHeight : 0;
                let bottom = 0;
                const bb = document.querySelector('.bottombar');
                const bbStyle = bb ? getComputedStyle(bb) : null;
                if (bb && bbStyle && bbStyle.display !== 'none') {
                    bottom = bb.offsetHeight || 0;
                }
                outer.style.setProperty('--shorts-top', top + 'px');
                outer.style.setProperty('--shorts-bottom', bottom + 'px');
            }

            function preloadNextVideo(currentIdx) {
                const nextIdx = (currentIdx + 1) % videos.length;
                const nextVideo = videos[nextIdx];
                if (nextVideo && !nextVideo.getAttribute('src')) {
                    const dataSrc = nextVideo.getAttribute('data-src');
                    if (dataSrc && dataSrc !== '../') { // Pastikan data-src tidak kosong
                        nextVideo.setAttribute('src', dataSrc);
                        // Browser akan mulai memuat metadata karena preload="none"
                    }
                }
            }

            function playVideoWithSound(video) {
                if (!video) return;

                video.muted = false;
                allowSound = true;

                const playPromise = video.play();
                userInteracted = true; // Tandai bahwa interaksi telah terjadi

                if (playPromise !== undefined) {
                    playPromise.then(() => {
                        if (autoplayNotification) {
                            autoplayNotification.style.display = 'none';
                        }
                    }).catch(error => {
                        console.warn('Autoplay with sound blocked, trying muted:', error);
                        video.muted = true;
                        allowSound = false;
                        video.play().then(() => {
                            if (autoplayNotification) {
                                autoplayNotification.style.display = 'block';
                                setTimeout(() => {
                                    autoplayNotification.style.display = 'none';
                                }, 3000);
                            }
                        }).catch(e => {
                            console.error('Autoplay completely blocked:', e);
                        });
                    });
                }

                updateSoundUI();
            }

            function showVideo(idx) {
                videos.forEach((video, i) => {
                    if (i !== idx) {
                        video.pause();
                        video.currentTime = 0;
                        if (progressUpdateIntervals[i]) {
                            clearInterval(progressUpdateIntervals[i]);
                            progressUpdateIntervals[i] = null;
                        }
                        if (placeholders[i]) {
                            placeholders[i].classList.add('hidden');
                        }
                    }
                });

                items.forEach((item, i) => {
                    item.classList.remove('active', 'above', 'below');
                    if (i === idx) item.classList.add('active');
                    else if (i < idx) item.classList.add('above');
                    else if (i > idx) item.classList.add('below');
                });

                const video = videos[idx];
                if (video) {
                    if (placeholders[idx]) {
                        placeholders[idx].classList.remove('hidden');
                    }

                    if (!video.getAttribute('src')) {
                        const dataSrc = video.getAttribute('data-src');
                        if (dataSrc) {
                            video.setAttribute('src', dataSrc);
                            video.load();
                        }
                    }

                    video.currentTime = 0;

                    // Coba putar video. Browser akan menangani apakah akan memutar dengan suara atau tidak.
                    video.muted = !allowSound; // Set status mute berdasarkan interaksi sebelumnya
                    const playPromise = video.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(error => {
                            console.warn("Autoplay was prevented. Muting and trying again.", error);
                            // Jika gagal, coba lagi dengan suara dimatikan
                            video.muted = true;
                            allowSound = false;
                            video.play().catch(e => console.error("Could not play video even when muted.", e));
                            
                            // Tampilkan notifikasi jika belum ada interaksi
                            if (!userInteracted && autoplayNotification) {
                                autoplayNotification.style.display = 'block';
                            }
                        });
                    }

                    lastPlaying = video;
                    updateSoundUI();

                    if (progressBars[idx] && progressContainers[idx] && progressHandles[idx] && progressTimes[idx]) {
                        setupProgressBar(video, progressBars[idx], progressContainers[idx], progressHandles[idx], progressTimes[
                            idx]);
                    }

                    preloadNextVideo(idx);
                }
            }

            function nextVideo() {
                if (items.length === 0) return;
                if (isUpgradeGateActive()) return;
                currentIdx = (currentIdx + 1) % items.length;
                showVideo(currentIdx);
            }

            function prevVideo() {
                if (items.length === 0) return;
                currentIdx = (currentIdx - 1 + items.length) % items.length;
                showVideo(currentIdx);
            }

            let startY = null;
            let moved = false;
            const inner = document.getElementById('shorts-inner');

            inner.addEventListener('touchstart', e => {
                userInteracted = true;
                if (e.touches.length === 1) {
                    startY = e.touches[0].clientY;
                    moved = false;
                }

                // Handle first interaction to enable sound
                if (!userInteracted) {
                    const currentVideo = videos[currentIdx];
                    if (currentVideo && currentVideo.muted) {
                        currentVideo.muted = false;
                        allowSound = true;
                    }
                }
            });

            inner.addEventListener('touchmove', e => {
                if (startY !== null && e.touches.length === 1) {
                    const dy = e.touches[0].clientY - startY;
                    if (Math.abs(dy) > 50 && !moved) {
                        if (dy < 0) {
                            if (!isUpgradeGateActive()) nextVideo();
                        } else if (dy > 0) {
                            prevVideo();
                        }
                        moved = true;
                    }
                }
            }, {
                passive: true
            });

            inner.addEventListener('touchend', () => {
                startY = null;
                moved = false;
            });

            let wheelTimeout = null;
            let lastWheel = 0;
            inner.addEventListener('wheel', e => {
                userInteracted = true;

                // Handle first interaction to enable sound
                if (!userInteracted) {
                    const currentVideo = videos[currentIdx];
                    if (currentVideo && currentVideo.muted) {
                        currentVideo.muted = false;
                        allowSound = true;
                    }
                }

                const now = Date.now();
                if (wheelTimeout) clearTimeout(wheelTimeout);
                wheelTimeout = setTimeout(() => {
                    lastWheel = 0;
                }, 300);
                if (now - lastWheel < 300) return;

                if (e.deltaY > 40) {
                    if (!isUpgradeGateActive()) nextVideo();
                    lastWheel = now;
                } else if (e.deltaY < -40) {
                    prevVideo();
                    lastWheel = now;
                }
            }, {
                passive: true
            });

            window.addEventListener('keydown', e => {
                userInteracted = true;

                // Handle first interaction to enable sound
                if (!userInteracted) {
                    const currentVideo = videos[currentIdx];
                    if (currentVideo && currentVideo.muted) {
                        currentVideo.muted = false;
                        allowSound = true;
                    }
                }

                if (e.key === 'ArrowDown') {
                    if (!isUpgradeGateActive()) nextVideo();
                }
                if (e.key === 'ArrowUp') {
                    prevVideo();
                }
            });

            videos.forEach(video => {
                video.addEventListener('click', function() {
                    userInteracted = true;
                    if (this.paused) {
                        // Jika video dijeda, putar lagi.
                        // Jika sebelumnya dimute, coba unmute.
                        if (this.muted) {
                            this.muted = false;
                        }
                        this.play();
                    } else {
                        this.pause();
                    }
                });

                video.addEventListener('volumechange', updateSoundUI);

                video.addEventListener('loadstart', function() {
                    const index = videos.indexOf(this);
                    if (placeholders[index] && index === currentIdx) {
                        placeholders[index].classList.remove('hidden');
                    }
                });

                video.addEventListener('canplay', function() {
                    const index = videos.indexOf(this);
                    if (placeholders[index]) {
                        placeholders[index].classList.add('hidden');
                    }
                });

                video.addEventListener('waiting', function() {
                    const index = videos.indexOf(this);
                    if (placeholders[index] && index === currentIdx) {
                        placeholders[index].classList.remove('hidden');
                    }
                });

                video.addEventListener('playing', function() {
                    const index = videos.indexOf(this);
                    if (placeholders[index]) {
                        placeholders[index].classList.add('hidden');
                    }
                });

                video.addEventListener('ended', function() {
                    this.currentTime = 0;
                    this.play().catch(() => {});
                });
            });

            document.querySelectorAll('.short-item').forEach((item, i) => {
                const btn = item.querySelector('.short-sound-btn');
                const v = videos[i];
                if (btn && v) {
                    btn.addEventListener('click', (e) => {
                        userInteracted = true;
                        e.stopPropagation();
                        if (v.muted) {
                            v.muted = false;
                            allowSound = true;                           
                        } else {
                            v.muted = true;
                            allowSound = false;
                        }
                        updateSoundUI();

                        if (autoplayNotification) {
                            autoplayNotification.style.display = 'none';
                        }
                    });
                }
            });

            document.addEventListener('DOMContentLoaded', () => {
                setViewportBounds();
                showVideo(0);

                videos.forEach((video, i) => {
                    if (progressBars[i] && progressContainers[i] && progressHandles[i] && progressTimes[i]) {
                        video.addEventListener('loadedmetadata', () => {
                            updateProgressBar(video, progressBars[i], progressTimes[i]);
                        });

                        video.addEventListener('timeupdate', () => {
                            if (!isDragging) {
                                updateProgressBar(video, progressBars[i], progressTimes[i]);
                            }
                        });
                    }
                });
            });

            window.addEventListener('resize', () => {
                setViewportBounds();
                showVideo(currentIdx);
            });

            window.addEventListener('beforeunload', () => {
                progressUpdateIntervals.forEach(interval => {
                    if (interval) clearInterval(interval);
                });
            });
        </script>
    </div>
