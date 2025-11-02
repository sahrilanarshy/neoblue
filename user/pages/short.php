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
        <style>
            html,
            body {
                height: 100%;
                margin: 0;
                padding: 0;
                background: #000;
                overflow: hidden;
            }

            .shorts-outer {
                position: fixed;
                left: 0;
                right: 0;
                top: var(--shorts-top, 105px);
                bottom: var(--shorts-bottom, 0px);
                background: #000;
                overflow: hidden;
                z-index: 1000;
            }

            .shorts-inner {
                height: 100%;
                width: 100%;
                overflow: hidden;
                position: relative;
                touch-action: pan-y;
            }

            .short-item {
                height: 100%;
                width: 100%;
                position: absolute;
                top: 0;
                left: 0;
                transition: transform 0.35s cubic-bezier(.4, 1, .5, 1), opacity 0.25s;
                will-change: transform, opacity;
                opacity: 0;
                pointer-events: none;
                background: #000 !important;
            }

            .short-item.active {
                opacity: 1;
                pointer-events: auto;
                z-index: 2;
                transform: translateY(0);
            }

            .short-item.above {
                opacity: 0;
                z-index: 1;
                transform: translateY(-100%);
            }

            .short-item.below {
                opacity: 0;
                z-index: 1;
                transform: translateY(100%);
            }

            .short-video-frame {
                width: 100%;
                height: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
                position: relative;
                background: #000 !important;
            }

            .short-loading {
                display: none !important;
            }

            video {
                width: 100%;
                height: 100%;
                object-fit: contain;
                aspect-ratio: 9/16;
                background: #000 !important;
                border: none;
                display: block;
                margin: 0 auto;
                max-width: 100%;
                max-height: 100%;
                box-shadow: 0 0 24px #000a;
                border-radius: 0 !important;
            }

            @media (min-width: 768px) {
                .short-video-frame {
                    width: 420px;
                    height: 100%;
                    margin: 0 auto;
                    border-radius: 0;
                    background: #000 !important;
                    box-shadow: 0 0 32px #000a;
                }

                video {
                    width: 100%;
                    height: 100%;
                    max-width: 100%;
                    max-height: 100%;
                    border-radius: 0 !important;
                    background: #000 !important;
                }
            }

            .upgrade-alert-overlay {
                display: none;
            }

            .short-sound-btn {
                position: absolute;
                right: 12px;
                bottom: 12px;
                width: auto;
                height: auto;
                padding: 8px 10px;
                border-radius: 999px;
                background: rgba(0, 0, 0, .5);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 5;
                backdrop-filter: saturate(120%) blur(2px);
            }

            .short-sound-btn i {
                font-size: 18px;
                line-height: 1;
            }

            .short-item.muted .short-sound-btn .icon-up {
                display: none;
            }

            .short-item.muted .short-sound-btn .icon-mute {
                display: inline;
            }

            .short-item.unmuted .short-sound-btn .icon-up {
                display: inline;
            }

            .short-item.unmuted .short-sound-btn .icon-mute {
                display: none;
            }

            .short-progress-container {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: rgba(255, 255, 255, 0.3);
                cursor: pointer;
                z-index: 10;
                transition: height 0.15s ease;
            }

            .short-progress-bar {
                height: 100%;
                width: 0%;
                background: #fff;
                transition: width 0.1s linear;
                position: relative;
            }

            .short-progress-handle {
                position: absolute;
                right: -6px;
                top: 50%;
                transform: translateY(-50%);
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: #fff;
                opacity: 0;
                transition: opacity 0.2s ease, transform 0.2s ease;
            }

            .short-progress-container:hover,
            .short-progress-container.dragging {
                height: 6px;
            }

            .short-progress-container:hover .short-progress-handle,
            .short-progress-container.dragging .short-progress-handle {
                opacity: 1;
                transform: translateY(-50%) scale(1.2);
            }

            .short-progress-time {
                position: absolute;
                bottom: 100%;
                right: 0;
                margin-bottom: 8px;
                padding: 4px 8px;
                background: rgba(0, 0, 0, 0.7);
                color: white;
                font-size: 12px;
                border-radius: 4px;
                opacity: 0;
                transform: translateY(10px);
                transition: opacity 0.2s ease, transform 0.2s ease;
                pointer-events: none;
                display: none;
            }

            .short-progress-container.dragging .short-progress-time {
                opacity: 1;
                transform: translateY(0);
            }

            .video-placeholder {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: #000;
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1;
            }

            .video-placeholder.hidden {
                display: none;
            }

            /* Autoplay notification */
            .autoplay-notification {
                position: absolute;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 10px 20px;
                border-radius: 20px;
                font-size: 14px;
                z-index: 20;
                display: none;
            }
        </style>
        <div class="shorts-outer">
            <div class="shorts-inner" id="shorts-inner">
                <div class="short-item active">
                    <div class="short-video-frame">
                        <div class="video-placeholder" id="placeholder-0">
                            <div style="color: #555; font-size: 14px;">Memuat Video ...</div>
                        </div>

                        <video class="short-video" loop preload="metadata" playsinline webkit-playsinline
                            disablepictureinpicture controlslist="nodownload noremoteplayback noplaybackrate"
                            poster="" data-src="../assets/short/vid_565036_1757642696.mp4">
                            <source data-src="../assets/user/short/video1.mp4" type="video/mp4">
                            Browser Anda tidak mendukung video.
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
                <div class="short-item" data-upgrade="1">
                    <div class="short-video-frame"
                        style="display:flex;flex-direction:column;gap:22px;color:#fff;text-align:center;padding:48px 28px;font-size:16px;line-height:1.4;">
                        <div><strong>Upgrade ke Premium</strong> untuk menonton lebih banyak.</div>
                        <a href="pembayaran.php" class="btn fw-bold"
                            style="background:#0d6efd;color:#fff;border-radius:30px;padding:10px 26px;font-size:15px;">Upgrade
                            Sekarang</a>
                    </div>
                </div>
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
            const isPremium = false;
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
                if (nextVideo) {
                    const nextSource = nextVideo.querySelector('source');
                    if (nextSource && !nextSource.getAttribute('src')) {
                        const dataSrc = nextSource.getAttribute('data-src') || nextVideo.getAttribute('data-src');
                        if (dataSrc) {
                            nextSource.setAttribute('src', dataSrc);
                            try {
                                nextVideo.load();
                            } catch (e) {
                                console.error('Error preloading video:', e);
                            }
                        }
                    }
                }
            }

            function playVideoWithSound(video) {
                if (!video) return;

                video.currentTime = 0;

                video.muted = false;
                allowSound = true;

                const playPromise = video.play();

                if (playPromise !== undefined) {
                    playPromise.then(() => {
                        if (autoplayNotification) {
                            autoplayNotification.style.display = 'none';
                        }
                    }).catch(error => {
                        console.log('Autoplay with sound blocked, trying muted:', error);
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
                            console.log('Autoplay completely blocked:', e);
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

                    const srcEl = video.querySelector('source');
                    if (srcEl && !srcEl.getAttribute('src')) {
                        const ds = srcEl.getAttribute('data-src') || video.getAttribute('data-src');
                        if (ds) {
                            srcEl.setAttribute('src', ds);
                            try {
                                video.load();
                            } catch (_) {}
                        }
                    }

                    video.currentTime = 0;

                    if (userInteracted) {
                        playVideoWithSound(video);
                    } else {
                        video.muted = false;
                        allowSound = true;

                        const playPromise = video.play();
                        if (playPromise !== undefined) {
                            playPromise.then(() => {
                                if (placeholders[idx]) {
                                    placeholders[idx].classList.add('hidden');
                                }
                            }).catch(error => {
                                video.muted = true;
                                allowSound = false;
                                video.play().then(() => {
                                    if (placeholders[idx]) {
                                        placeholders[idx].classList.add('hidden');
                                    }
                                });
                            });
                        }
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

            document.addEventListener('click', function() {
                userInteracted = true;
                if (autoplayNotification) {
                    autoplayNotification.style.display = 'none';
                }

                const currentVideo = videos[currentIdx];
                if (currentVideo && currentVideo.paused) {
                    playVideoWithSound(currentVideo);
                }
            }, {
                once: true
            });

            let startY = null;
            let moved = false;
            const inner = document.getElementById('shorts-inner');

            inner.addEventListener('touchstart', e => {
                userInteracted = true;
                if (e.touches.length === 1) {
                    startY = e.touches[0].clientY;
                    moved = false;
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
                        playVideoWithSound(this);
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
                            if (i === currentIdx) {
                                playVideoWithSound(v);
                            }
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
