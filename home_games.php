<?php
session_start();

// Set timezone ke Asia/Jakarta (WIB) untuk memastikan tanggal lokal yang benar
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); 
    exit;
}
$nama = htmlspecialchars($_SESSION['nama']);

// --- LOGIKA KALENDER DINAMIS DAN NAVIGASI ---

// 1. Tentukan tanggal yang sedang dilihat (displayDate)
$today = new DateTime('now'); // Tanggal hari ini
$todayCheck = $today->format('Y-m-d'); 

if (isset($_GET['date']) && !empty($_GET['date'])) {
    // Ambil tanggal dari parameter URL
    try {
        $displayDate = new DateTime($_GET['date']);
    } catch (Exception $e) {
        // Jika tanggal di URL tidak valid, kembali ke hari ini
        $displayDate = clone $today;
    }
} else {
    // Default: gunakan tanggal hari ini
    $displayDate = clone $today;
}

$displayDateCheck = $displayDate->format('Y-m-d');
$monthYear = $displayDate->format('F Y');

// 2. Hitung tanggal mulai untuk tampilan 7 hari (3 hari sebelum displayDate)
$startDate = clone $displayDate;
$startDate->modify('-3 days');

// 3. Hitung tanggal untuk navigasi (Prev Day dan Next Day)
$prevDate = clone $displayDate;
$prevDate->modify('-1 day');
$prevDateUrl = 'home_games.php?date=' . $prevDate->format('Y-m-d');

$nextDate = clone $displayDate;
$nextDate->modify('+1 day');
$nextDateUrl = 'home_games.php?date=' . $nextDate->format('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBA Games & Scores - HoopWave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f9f9f9;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            padding-top: 170px;
        }

        /* Navbar Utama */
        .navbar-main {
            z-index: 1050 !important;
            background-image: url(asset/background-navbar.png);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: transparent !important;
        }

        #subNavbar {
            z-index: 1040 !important;
            background: #f8f9fa !important;
        }

        /* Teams Dropdown */
        .teams-dropdown {
            width: 360px !important;
            max-height: 80vh;
            overflow-y: auto;
            padding: 0.5rem 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .team-item {
            display: flex !important;
            align-items: center;
            gap: 12px;
            padding: 0.45rem 1rem;
        }

        .team-item img {
            width: 26px;
            height: 26px;
        }

        .team-item:hover {
            background: #f8f9fa;
            color: #0d6efd !important;
            border-radius: 6px;
        }

        /* Underline Home */
        .nav-underline-custom {
            position: relative;
            color: #000 !important;
            font-weight: 600;
        }

        .nav-underline-custom::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 4px;
            background: #000;
            border-radius: 2px;
            transition: all .3s;
        }

        .nav-underline-custom:hover::after {
            width: 60px;
        }

        /* Game Card NBA Style */
        .game-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .team-logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
        }

        .score-big {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1;
        }

        .final-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: #000;
        }

        .btn-league-pass {
            background: #ffb700 !important;
            color: #000 !important;
            font-weight: bold;
            border: none;
        }

        .leader-img {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .recap-thumb {
            border-radius: 14px;
            overflow: hidden;
            position: relative;
        }

        .recap-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.85));
            color: #fff;
            padding: 2rem 1rem 1rem;
            text-align: center;
            font-weight: bold;
        }

        .today-box {
            border: 2px solid #000 !important;
            background: #fff !important;
            color: #000;
            border-radius: 8px;
        }
        
        .selected-date {
            background: #000 !important; /* Warna latar belakang untuk tanggal yang dipilih */
            color: #fff !important; /* Warna teks untuk tanggal yang dipilih */
            border: 2px solid #000 !important;
        }
        
        /* Jika tanggal yang dipilih juga adalah hari ini, gabungkan gaya */
        .today-box.selected-date {
            background: #007bff !important; /* Contoh warna lain untuk hari ini yang dipilih */
            border-color: #007bff !important;
        }

        .calendar-day {
            width: 48px;
            text-align: center;
            padding: 10px 0;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s, color 0.2s, border-color 0.2s;
        }
        .calendar-day:hover:not(.selected-date) {
            background-color: #e9ecef;
        }
    </style>
</head>

<body>

    <?php include "navbar.php" ?>

    <!-- SUB-NAVBAR GAMES (menempel tepat di bawah navbar utama) -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom" id="subNavbar"
        style="position:fixed; left:0; right:0; z-index:1040;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-4 text-dark mb-0">Games</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#subnavGames">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="subnavGames">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active nav-underline-custom fw-semibold" href="home_games.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ISI HALAMAN -->
    <div class="container my-5">

        <!-- Header + Calendar (Dinamic Section) -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-4">
            <h2 class="fw-bold text-uppercase mb-0">NBA Games & Scores</h2>
            <div class="d-flex flex-wrap align-items-center gap-4">
                <div class="d-flex align-items-center gap-3">
                    <!-- Tampilkan Bulan dan Tahun dari tanggal yang dipilih -->
                    <span class="fw-bold"><?php echo $monthYear; ?></span>
                    
                    <!-- Tombol Navigasi Harian (menggunakan URL parameter) -->
                    <a href="<?php echo $prevDateUrl; ?>" class="text-decoration-none">
                        <button class="btn btn-link text-dark p-0 fs-4">&lt;</button>
                    </a>
                    <a href="<?php echo $nextDateUrl; ?>" class="text-decoration-none">
                        <button class="btn btn-link text-dark p-0 fs-4">&gt;</button>
                    </a>
                </div>
                
                <!-- Kalender 7 Hari Dinamis -->
                <div class="d-flex gap-1 bg-white rounded-3 shadow-sm p-2">
                    <?php 
                    $currentDay = clone $startDate; // Mulai dari tanggal 3 hari sebelum tanggal yang dipilih
                    for ($i = 0; $i < 7; $i++) {
                        $dayName = $currentDay->format('D'); 
                        $dayNum = $currentDay->format('d'); 
                        $dateToCheck = $currentDay->format('Y-m-d'); 

                        $classes = '';
                        $is_selected = ($dateToCheck === $displayDateCheck);
                        
                        // Cek apakah ini tanggal hari ini (Today)
                        if ($dateToCheck === $todayCheck) {
                            $classes .= ' today-box';
                        }
                        
                        // Cek apakah ini tanggal yang sedang dipilih (Selected)
                        if ($is_selected) {
                            $classes .= ' selected-date';
                        }
                        
                        // Tentukan kelas warna teks berdasarkan apakah tanggal dipilih atau tidak
                        // Jika dipilih, gunakan text-white. Jika tidak, gunakan text-muted untuk small, dan text-dark untuk strong.
                        $smallTextColor = $is_selected ? 'text-white' : 'text-muted';
                        $strongTextColor = $is_selected ? 'text-white' : 'text-dark';

                        // Tentukan link untuk memilih tanggal ini
                        $dateLink = 'home_games.php?date=' . $dateToCheck;

                        echo '<a href="' . $dateLink . '" class="text-decoration-none">';
                        echo '<div class="calendar-day ' . trim($classes) . '">';
                        // Gunakan $smallTextColor untuk Day Name
                        echo '<small class="' . $smallTextColor . '">' . $dayName . '</small><br>';
                        // Gunakan $strongTextColor untuk Day Number
                        echo '<strong class="' . $strongTextColor . '">' . $dayNum . '</strong>';
                        echo '</div>';
                        echo '</a>';

                        $currentDay->modify('+1 day'); // Maju ke hari berikutnya
                    }
                    ?>
                </div>
                <!-- Tombol Kembali ke Hari Ini (Tambahan) -->
                <?php if ($displayDateCheck !== $todayCheck): ?>
                    <a href="home_games.php" class="btn btn-outline-secondary rounded-pill btn-sm">Today</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Konten Game: Sesuaikan Judul dengan Tanggal yang Dipilih -->
        <h3 class="mb-4">Games on <?php echo $displayDate->format('l, F j, Y'); ?></h3>

        <!-- GAME CARD 1 (Data Statis - Anda akan mengganti ini dengan data dinamis berdasarkan $displayDate) -->
        <div class="game-card p-4 p-md-5">
            <div class="row g-5 align-items-center">

                <!-- SKOR + LOGO TIM -->
                <div class="col-lg-5">
                    <div class="d-flex justify-content-between align-items-center text-center">
                        <div>
                            <img src="https://cdn.nba.com/logos/nba/1610612765/primary/L/logo.svg" class="team-logo"
                                alt="Pistons">
                            <div class="mt-3 fw-bold fs-5">Pistons<br><small class="text-muted">15-2</small></div>
                        </div>
                        <div>
                            <div class="score-big">122</div>
                            <div class="final-text mt-2">FINAL</div>
                            <div class="score-big mt-3">117</div>
                        </div>
                        <div>
                            <img src="https://cdn.nba.com/logos/nba/1610612754/primary/L/logo.svg" class="team-logo"
                                alt="Pacers">
                            <div class="mt-3 fw-bold fs-5">Pacers<br><small class="text-muted">2-15</small></div>
                        </div>
                    </div>

                    <!-- Tombol hanya Box Score & Game Details -->
                    <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
                        <a href="box_score.php"><button class="btn btn-outline-dark rounded-pill px-5 py-2">Box Score</button></a>
                    </div>
                </div>

                <!-- GAME LEADERS -->
                <div class="col-lg-4">
                    <h5 class="text-center fw-bold mb-4 text-uppercase">Game Leaders</h5>
                    <div class="row text-center">
                        <div class="col-6">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1630595.png" class="leader-img"
                                alt="Cade Cunningham">
                            <p class="mt-2 mb-0 fw-bold small">Cade Cunningham</p>
                            <p class="text-muted small">DET #2 • PG</p>
                            <div class="d-flex justify-content-center gap-3 mt-2">
                                <div><strong>24</strong><br><small>PTS</small></div>
                                <div><strong>11</strong><br><small>REB</small></div>
                                <div><strong>6</strong><br><small>AST</small></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1627783.png" class="leader-img"
                                alt="Pascal Siakam">
                            <p class="mt-2 mb-0 fw-bold small">Pascal Siakam</p>
                            <p class="text-muted small">IND #43 • PF</p>
                            <div class="d-flex justify-content-center gap-3 mt-2">
                                <div><strong>24</strong><br><small>PTS</small></div>
                                <div><strong>8</strong><br><small>REB</small></div>
                                <div><strong>3</strong><br><small>AST</small></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GAME RECAP: YouTube Video Embed -->
                <div class="col-lg-3">
                    <h5 class="text-center fw-bold mb-4 text-uppercase">Game Recap</h5>
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg">
                        <iframe src="https://www.youtube.com/embed/lSK-01qq9rM"
                            title="Pistons vs Pacers Full Game Highlights" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen class="rounded-4">
                        </iframe>
                    </div>
                    <div class="text-center mt-3">
                        <small class="text-muted">Full Game Highlights • 17:35</small>
                    </div>
                </div>

            </div>
        </div>

    <!-- GAME CARD 2 (contoh lagi - Data Statis) -->
    <div class="game-card p-4 p-md-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <div class="d-flex justify-content-between align-items-center text-center">
                    <div>
                        <img src="https://cdn.nba.com/logos/nba/1610612739/primary/L/logo.svg" class="team-logo"
                            alt="Cavaliers">
                        <div class="mt-3 fw-bold fs-5">Cavaliers<br><small class="text-muted">12-7</small></div>
                    </div>
                    <div>
                        <div class="score-big">99</div>
                        <div class="final-text mt-2">FINAL</div>
                        <div class="score-big mt-3">110</div>
                    </div>
                    <div>
                        <img src="https://cdn.nba.com/logos/nba/1610612761/primary/L/logo.svg" class="team-logo"
                            alt="Raptors">
                        <div class="mt-3 fw-bold fs-5">Raptors<br><small class="text-muted">13-5</small></div>
                    </div>
                </div>
                <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
                    <a href="box_score1.php"><button class="btn btn-outline-dark rounded-pill px-5 py-2">Box Score</button></a>
                </div>
            </div>
            <!-- Leaders & Recap bisa ditambahkan lagi -->
             <div class="col-lg-4">
                    <h5 class="text-center fw-bold mb-4 text-uppercase">Game Leaders</h5>
                    <div class="row text-center">
                        <div class="col-6">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1628378.png" class="leader-img"
                                alt="Donovan Mitchell">
                            <p class="mt-2 mb-0 fw-bold small">Donovan Mitchell</p>
                            <p class="text-muted small">CLE #45 • SG</p>
                            <div class="d-flex justify-content-center gap-3 mt-2">
                                <div><strong>17</strong><br><small>PTS</small></div>
                                <div><strong>1</strong><br><small>REB</small></div>
                                <div><strong>8</strong><br><small>AST</small></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1627742.png" class="leader-img"
                                alt="Brandon Ingram">
                            <p class="mt-2 mb-0 fw-bold small">Brandon Ingram</p>
                            <p class="text-muted small">TOR #3 • SF</p>
                            <div class="d-flex justify-content-center gap-3 mt-2">
                                <div><strong>37</strong><br><small>PTS</small></div>
                                <div><strong>7</strong><br><small>REB</small></div>
                                <div><strong>2</strong><br><small>AST</small></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GAME RECAP: YouTube Video Embed -->
                <div class="col-lg-3">
                    <h5 class="text-center fw-bold mb-4 text-uppercase">Game Recap</h5>
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg">
                        <iframe src="https://www.youtube.com/embed/jwye6qMzxLU"
                            title="Cavaliers vs Raptors Full Game Highlights" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen class="rounded-4">
                        </iframe>
                    </div>
                    <div class="text-center mt-3">
                        <small class="text-muted">Full Game Highlights • 16:10</small>
                    </div>
                </div>
        </div>
    </div>

    </div>

    <!-- Script agar sub-navbar selalu menempel tepat di bawah navbar utama -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi untuk menyesuaikan posisi sub-navbar dan padding body
        function adjustSubNavbar() {
            const main = document.querySelector('.navbar-main');
            const sub = document.getElementById('subNavbar');
            if (main && sub) {
                // Set posisi top sub-navbar tepat di bawah navbar utama
                sub.style.top = main.offsetHeight + 'px';
                // Tambah padding ke body agar konten tidak tertutup
                document.body.style.paddingTop = (main.offsetHeight + sub.offsetHeight + 30) + 'px';
            }
        }
        window.addEventListener('load', adjustSubNavbar);
        window.addEventListener('resize', adjustSubNavbar);
    </script>
</body>

</html>