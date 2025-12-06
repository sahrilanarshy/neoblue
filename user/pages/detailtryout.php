<?php
// Asumsi $koneksi sudah tersedia dari file utama (misalnya index.php)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple performance timers (enable with ?debug_perf=1)
$perf = ['start' => microtime(true), 'curl_total' => 0, 'db_subtests' => 0, 'db_history' => 0, 'merge_history' => 0];

$id_tryout = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$tryout_name = "Detail Tryout"; // Default title
$subtests_data = [];
$completed_sessions = [];

// Pastikan user login untuk melihat detail
if ($user_id === 0) { echo "<p class='text-center text-danger'>Anda harus login untuk mengakses halaman ini.</p>"; exit; }

if ($id_tryout > 0) {
    // 1. Ambil nama tryout
    $stmt_tryout = mysqli_prepare($koneksi, "SELECT nama_tryout FROM tryout WHERE id = ?");
    mysqli_stmt_bind_param($stmt_tryout, "i", $id_tryout);
    mysqli_stmt_execute($stmt_tryout);
    $result_tryout = mysqli_stmt_get_result($stmt_tryout);
    if ($row_tryout = mysqli_fetch_assoc($result_tryout)) {
        $tryout_name = htmlspecialchars($row_tryout['nama_tryout']);
    } else {
        echo "<p class='text-center text-danger'>Tryout tidak ditemukan.</p>";
        exit;
    }

    // 2. Coba ambil data lewat API terlebih dahulu; jika gagal fallback ke query DB
    $api_ok = false;
    $subtests_data = [];
    $completed_sessions = [];

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    // Bypass API cURL: use direct DB queries below (no HTTP calls)
    $paths = [];

    if (!$api_ok) {
        // Fallback: query DB seperti semula
        $db_sub_start = microtime(true);
        $query_subtests = "SELECT
                        s.id AS subtest_id,
                        s.nama_subtest,
                        ts.waktu_pengerjaan,
                        COUNT(st.id) AS jumlah_soal
                        FROM soal_tryout st
                        JOIN subtest s ON st.subtest_id = s.id
                        LEFT JOIN tryout_subtest ts ON st.tryout_id = ts.tryout_id AND st.subtest_id = ts.subtest_id
                        WHERE st.tryout_id = ?
                        GROUP BY s.id, s.nama_subtest, ts.waktu_pengerjaan
                        ORDER BY s.nama_subtest ASC";

        $stmt_subtests = mysqli_prepare($koneksi, $query_subtests);
        mysqli_stmt_bind_param($stmt_subtests, "i", $id_tryout);
        mysqli_stmt_execute($stmt_subtests);
        $result_subtests = mysqli_stmt_get_result($stmt_subtests);

        while ($row = mysqli_fetch_assoc($result_subtests)) {
            $subtests_data[] = $row;
        }
        $perf['db_subtests'] += (microtime(true) - $db_sub_start);

        // Ambil riwayat pengerjaan user untuk tryout ini
        $db_history_start = microtime(true);
        $stmt_history = mysqli_prepare($koneksi, "SELECT subtest_id, id as session_id, score FROM user_tryout_sessions WHERE user_id = ? AND tryout_id = ?");
        mysqli_stmt_bind_param($stmt_history, "ii", $user_id, $id_tryout);
        mysqli_stmt_execute($stmt_history);
        $result_history = mysqli_stmt_get_result($stmt_history);
        while ($row = mysqli_fetch_assoc($result_history)) {
            $entry = [
                'session_id' => $row['session_id'],
                'skor' => $row['score']
            ];
            // store both numeric and string keys for robustness
            $completed_sessions[$row['subtest_id']] = $entry;
            $completed_sessions[(string)$row['subtest_id']] = $entry;
        }
        $perf['db_history'] += (microtime(true) - $db_history_start);
    }


} else {
    // Friendly fallback when no valid ID provided
    ?>
    <div class="container py-5">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">ID Tryout tidak valid.</h4>
            <p>Permintaan tidak menyertakan ID tryout yang benar. Silakan kembali ke daftar tryout untuk memilih tryout yang tersedia.</p>
            <hr>
            <p class="mb-0"><a href=".?hal=tryout" class="btn btn-primary">Kembali ke Daftar Tryout</a></p>
        </div>
        <p class="text-muted small">Jika Anda mengakses halaman ini dari tautan internal, pastikan parameter <code>?hal=detailtryout&id=... </code> ada dan valid. Untuk debugging cepat, jalankan:</p>
        <pre><code>curl -i "<?= (isset($_SERVER['HTTP_HOST']) ? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] : 'http://localhost') ?><?= dirname($_SERVER['REQUEST_URI']) ?>/api/api_materi_tryout.php?id=1"</code></pre>
    </div>
    <?php
    exit;
}
?>
<style>
/* Yellow style for 'Lihat Hasil' buttons in detailtryout (match premium upgrade) */
.btn-tryout.lihat-hasil{
    background: #ffc107;
    color: #212529;
    border: 1px solid #ffc107;
}
.btn-tryout.lihat-hasil:hover{ background: #e0a800; color: #212529; }
/* small badge for completed subtest */
.subtest-badge{ display:none; }
</style>
<main class="dashboard-content">
    <?php if (isset($_GET['debug_ui']) && $_GET['debug_ui'] == '1'): ?>
        <div class="container mt-3">
            <div class="alert alert-info">
                <h5>DEBUG: completed_sessions</h5>
                <pre style="white-space:pre-wrap;"><?= htmlspecialchars(print_r($completed_sessions, true)); ?></pre>
                <h5>DEBUG: subtests_data</h5>
                <pre style="white-space:pre-wrap;"><?= htmlspecialchars(print_r($subtests_data, true)); ?></pre>
            </div>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['debug_perf']) && $_GET['debug_perf'] == '1'): ?>
        <?php $perf['total'] = microtime(true) - $perf['start']; ?>
        <div class="container mt-3">
            <div class="alert alert-warning small">
                <strong>PERF TIMINGS (seconds)</strong>
                <ul>
                    <li>total: <?= number_format($perf['total'], 4); ?></li>
                    <li>curl_total: <?= number_format($perf['curl_total'], 4); ?></li>
                    <li>db_subtests: <?= number_format($perf['db_subtests'], 4); ?></li>
                    <li>db_history (fallback): <?= number_format($perf['db_history'], 4); ?></li>
                    <li>merge_history (api merge): <?= number_format($perf['merge_history'], 4); ?></li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
    <section class="tryout-section">
        <div class="tryout-group mb-4">
            <h3 class="tryout-heading">Detail Tryout: <?= $tryout_name; ?></h3>
            <div class="tryout-box">
                <?php if (empty($subtests_data)): ?>
                    <p class="text-center text-muted" style="width: 100%;">Belum ada subtest untuk tryout ini.</p>
                <?php else: ?>
                    <?php foreach ($subtests_data as $subtest): ?>
                        <div class="tryout-item">
                            <div class="tryout-left">
                                <div class="tryout-icon">
                                    <i class="fa-regular fa-file-lines"></i>
                                </div>
                                <div class="tryout-info">
                                    <?php
                                        // determine subtest id field (support 'subtest_id' or 'id')
                                        $current_sid = isset($subtest['subtest_id']) ? $subtest['subtest_id'] : (isset($subtest['id']) ? $subtest['id'] : null);

                                        // robust check: consider multiple sources that indicate this subtest was completed:
                                        // - completed_sessions map (from API or DB)
                                        // - direct session id fields on the $subtest object
                                        // - boolean completion flags on the $subtest object (done/is_done/completed/...)
                                        $hasSession = false;
                                        $sessionIdValue = null;

                                        // 1) check completed_sessions array using safe key checks (array_key_exists handles nulls)
                                        if ($current_sid !== null) {
                                            $kstr = (string)$current_sid;
                                            if (array_key_exists($kstr, $completed_sessions)) {
                                                $hasSession = true;
                                                $sessionIdValue = isset($completed_sessions[$kstr]['session_id']) ? $completed_sessions[$kstr]['session_id'] : null;
                                            } elseif (array_key_exists((int)$kstr, $completed_sessions)) {
                                                $hasSession = true;
                                                $sessionIdValue = isset($completed_sessions[(int)$kstr]['session_id']) ? $completed_sessions[(int)$kstr]['session_id'] : null;
                                            }
                                        }

                                        // 2) check common session id fields returned directly in the subtest object
                                        if (!$hasSession) {
                                            if (!empty($subtest['session_id'])) { $hasSession = true; $sessionIdValue = $subtest['session_id']; }
                                            elseif (!empty($subtest['sessionId'])) { $hasSession = true; $sessionIdValue = $subtest['sessionId']; }
                                            elseif (!empty($subtest['user_session_id'])) { $hasSession = true; $sessionIdValue = $subtest['user_session_id']; }
                                            elseif (!empty($subtest['session']) && (is_array($subtest['session']) || is_object($subtest['session'])) ) {
                                                $sess = (array)$subtest['session'];
                                                if (!empty($sess['id'])) { $hasSession = true; $sessionIdValue = $sess['id']; }
                                            }
                                            // also treat API 'status'==1 as completed even if session_id missing
                                            if (!$hasSession && isset($subtest['status']) && intval($subtest['status']) === 1) {
                                                $hasSession = true;
                                                $sessionIdValue = isset($subtest['session_id']) ? $subtest['session_id'] : null;
                                            }
                                        }

                                        // 3) check boolean-like flags on $subtest itself (some APIs only return a done flag)
                                        if (!$hasSession) {
                                            $doneFlags = ['done','is_done','completed','is_completed','has_submission','finished','status'];
                                            foreach ($doneFlags as $f) {
                                                if (isset($subtest[$f])) {
                                                    $val = $subtest[$f];
                                                    // treat truthy values or explicit 'done' status as completed
                                                    if ($val === true || $val === 1 || $val === '1' || $val === 'true' || $val === 'done' || $val === 'finished') {
                                                        $hasSession = true;
                                                        break;
                                                    }
                                                    if (is_string($val) && in_array(strtolower($val), ['done','finished','completed'])) { $hasSession = true; break; }
                                                }
                                            }
                                        }

                                        // 4) finally, if completed_sessions contains an entry with done_flag set (even when session_id is null)
                                        if (!$hasSession && $current_sid !== null) {
                                            $kstr = (string)$current_sid;
                                            if (isset($completed_sessions[$kstr]) && !empty($completed_sessions[$kstr]['done_flag'])) {
                                                $hasSession = true;
                                                $sessionIdValue = isset($completed_sessions[$kstr]['session_id']) ? $completed_sessions[$kstr]['session_id'] : null;
                                            } elseif (isset($completed_sessions[(int)$kstr]) && !empty($completed_sessions[(int)$kstr]['done_flag'])) {
                                                $hasSession = true;
                                                $sessionIdValue = isset($completed_sessions[(int)$kstr]['session_id']) ? $completed_sessions[(int)$kstr]['session_id'] : null;
                                            }
                                        }
                                    ?>

                                    <p class="tryout-title">
                                        <?= htmlspecialchars(isset($subtest['nama_subtest']) ? $subtest['nama_subtest'] : ($subtest['nama'] ?? 'Subtest')); ?>
                                        <?php /* badge removed as requested */ ?>
                                    </p>

                                    <?php if ($hasSession): ?>
                                        <p class="tryout-date">
                                            <strong>Nilai: <?= htmlspecialchars(isset($completed_sessions[(string)$current_sid]['skor']) ? $completed_sessions[(string)$current_sid]['skor'] : (isset($subtest['score']) ? $subtest['score'] : (isset($subtest['score_display']) ? $subtest['score_display'] : '-'))); ?></strong>
                                        </p>
                                    <?php else: ?>
                                        <p class="tryout-date"><?= isset($subtest['jumlah_soal']) ? $subtest['jumlah_soal'] : '0'; ?> soal · <?= isset($subtest['waktu_pengerjaan']) ? $subtest['waktu_pengerjaan'] : '-'; ?> menit</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php
                                // Cek apakah subtest ini sudah dikerjakan
                                if ($hasSession) {
                                    $session_id = $sessionIdValue ?: (isset($completed_sessions[(string)$current_sid]) ? $completed_sessions[(string)$current_sid]['session_id'] : null);
                                    if (!empty($session_id)) {
                                        echo '<a href=".?hal=hasiltryout&session_id=' . $session_id . '" class="btn-tryout lihat-hasil">Lihat Hasil</a>';
                                    } else {
                                        // fallback: no session_id available but we know the subtest was completed
                                        // link to hasiltryout with tryout+subtest parameters so server can resolve the correct session
                                        $link_sid = $current_sid !== null ? $current_sid : (isset($subtest['subtest_id']) ? $subtest['subtest_id'] : (isset($subtest['id']) ? $subtest['id'] : ''));
                                        echo '<a href=".?hal=hasiltryout&tryout_id=' . $id_tryout . '&subtest_id=' . $link_sid . '" class="btn-tryout lihat-hasil">Lihat Hasil</a>';
                                    }
                                } else {
                                    // when building link to start, use available id key
                                    $link_sid = $current_sid !== null ? $current_sid : (isset($subtest['subtest_id']) ? $subtest['subtest_id'] : (isset($subtest['id']) ? $subtest['id'] : ''));
                                    echo '<a href=".?hal=tryout-test&id_tryout=' . $id_tryout . '&subtest_id=' . $link_sid . '" class="btn-tryout aktif">Kerjakan</a>';
                                }
                            ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
