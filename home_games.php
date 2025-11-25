<?php
// Sertakan file koneksi database, meskipun belum digunakan di halaman ini, 
// ini adalah praktik yang baik jika nanti Anda ingin mengambil data dari DB.
include 'connection.php'; 

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); 
    exit;
}
$nama = htmlspecialchars($_SESSION['nama']);
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

        /* NEW CALENDAR STYLING (Mengikuti tampilan kotak penuh) */
        .calendar-day {
            width: 80px; /* Ukuran kotak diperbesar */
            text-align: center;
            padding: 5px; 
            margin: 0 2px; /* Jarak antar kotak */
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent; /* Border default transparan */
            background-color: #fff; /* Latar belakang putih */
            box-shadow: 0 1px 4px rgba(0,0,0,0.05); /* Bayangan kecil */
        }
        
        .calendar-day:hover {
            border-color: #ccc; /* Border saat hover */
        }

        .calendar-day.active {
            background-color: #000;
            color: #fff;
            border-color: #000;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transform: translateY(-2px);
        }

        .calendar-day .day-of-week {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d; /* Warna abu-abu untuk hari */
            margin-bottom: 5px;
        }

        .calendar-day.active .day-of-week {
            color: #fff; /* Putih saat aktif */
        }

        .calendar-day .date-number {
            font-size: 2.2rem; /* Ukuran tanggal diperbesar */
            font-weight: 900;
            line-height: 1;
            margin-bottom: 5px;
        }

        .calendar-day .game-count {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #dc3545; /* Warna merah untuk penanda games (contoh) */
        }
        
        .calendar-day.active .game-count {
            color: #ffc107; /* Warna kuning saat aktif */
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

        <!-- Header + Calendar -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-4">
            <h2 class="fw-bold text-uppercase mb-0">NBA Games & Scores</h2>
            
            <!-- Kontainer Utama Kalender -->
            <div class="d-flex flex-column align-items-end">
                <!-- Navigasi Bulan/Tahun & Tombol Hari Ini -->
                <div class="d-flex align-items-center mb-3">
                    <button class="btn btn-link text-dark p-0 fs-4" onclick="changeMonth(-1)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </button>
                    <!-- ID untuk menampilkan Bulan dan Tahun secara dinamis -->
                    <span class="fw-bold fs-5 mx-3" id="monthYearDisplay">November 2025</span> 
                    <button class="btn btn-link text-dark p-0 fs-4" onclick="changeMonth(1)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                    <!-- Tombol Hari Ini (TODAY) -->
                    <button class="btn btn-sm btn-outline-dark ms-4" onclick="selectToday()">TODAY</button>
                </div>

                <!-- Kalender Harian Mini (7 Hari) -->
                <div class="d-flex bg-white rounded-3 p-2 shadow-sm" id="calendarDaysContainer">
                    <!-- Hari-hari akan dimasukkan di sini oleh JavaScript -->
                </div>
            </div>
        </div>

        <!-- GAME CARD 1 (Tanggal 2025-11-25) - Conto Data Spesifik -->
        <div class="game-card p-4 p-md-5" data-date="2025-11-25">
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
                        <button class="btn btn-outline-dark rounded-pill px-5 py-2">Box Score</button>
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

        <!-- GAME CARD 2 (Tanggal 2025-11-25) - Conto Data Spesifik -->
        <div class="game-card p-4 p-md-5" data-date="2025-11-25">
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
                        <button class="btn btn-outline-dark rounded-pill px-5 py-2">Box Score</button>
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
        
        <!-- Game card placeholder untuk semua tanggal di luar 25 November 2025 -->
        <div class="game-card p-4 p-md-5" data-date="placeholder" style="display:none;">
            <div class="text-center py-5">
                <h3 class="fw-bold">Tidak Ada Pertandingan Terjadwal</h3>
                <p class="text-muted">Cek lagi besok untuk jadwalnya!</p>
            </div>
        </div>

    </div>

    <!-- Script agar sub-navbar selalu menempel tepat di bawah navbar utama -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- 1. SETUP NAVIGASI & KALENDER ---
        const MONTHS = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        const WEEKDAYS = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
        
        // Atur tanggal awal tampilan ke 25 November 2025 (berdasarkan contoh statis Anda)
        // Kita menggunakan objek Date untuk mempermudah manipulasi tanggal
        let selectedDate = new Date(2025, 10, 25); // Bulan ke-10 adalah November
        const today = new Date(); // Tanggal hari ini

        // Referensi DOM
        const monthYearDisplay = document.getElementById('monthYearDisplay');
        const calendarDaysContainer = document.getElementById('calendarDaysContainer');
        const gameCards = document.querySelectorAll('.game-card');

        // Data dummy untuk jumlah games (hanya untuk visual, tidak mempengaruhi konten)
        // Kunci adalah tanggal dalam format YYYY-MM-DD, Nilai adalah jumlah games
        const DUMMY_GAME_COUNTS = {
            '2025-11-23': 8,
            '2025-11-24': 10,
            '2025-11-25': 3, // Tanggal dengan konten spesifik
            '2025-11-26': 9,
            '2025-11-27': 11,
            '2025-11-28': 8,
            '2025-11-29': 7,
        };


        // Fungsi untuk memformat tanggal menjadi YYYY-MM-DD
        function formatDate(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }
        
        // Fungsi untuk menyaring dan menampilkan game card
        function filterGames(dateString) {
            let foundSpecificGame = false;
            const placeholderCard = document.querySelector('.game-card[data-date="placeholder"]');

            // Sembunyikan semua card (termasuk placeholder)
            gameCards.forEach(card => {
                card.style.display = 'none'; 
            });

            // Cari dan tampilkan game card yang spesifik untuk tanggal ini (misalnya 2025-11-25)
            gameCards.forEach(card => {
                const cardDate = card.getAttribute('data-date');
                if (cardDate === dateString) {
                    // Tampilkan card spesifik
                    card.style.display = ''; 
                    // Pastikan card spesifik bukan placeholder
                    if (cardDate !== "placeholder") {
                        foundSpecificGame = true;
                    }
                }
            });
            
            // Jika tidak ada game spesifik yang ditemukan UNTUK TANGGAL INI, 
            // atau jika card yang ditemukan adalah placeholder, 
            // maka terapkan aturan baru: Tampilkan placeholder jika tanggal BUKAN 25 Nov.
            
            if (dateString !== '2025-11-25' && placeholderCard) {
                // Aturan: Untuk semua tanggal selain 25 Nov, TAMPILKAN placeholder
                placeholderCard.style.display = '';
                // Sembunyikan semua card spesifik (meski sudah disembunyikan di awal, ini memastikan)
                gameCards.forEach(card => {
                     const cardDate = card.getAttribute('data-date');
                    if (cardDate === '2025-11-25') {
                        card.style.display = 'none';
                    }
                });

            } else if (dateString === '2025-11-25') {
                 // Aturan: Untuk tanggal 25 Nov, TAMPILKAN game spesifik, sembunyikan placeholder
                gameCards.forEach(card => {
                    if (card.getAttribute('data-date') === '2025-11-25') {
                        card.style.display = '';
                    } else if (card.getAttribute('data-date') === 'placeholder') {
                        card.style.display = 'none';
                    }
                });
            } else {
                 // Logika fallback default (seharusnya tidak tercapai)
                 if (placeholderCard) {
                    placeholderCard.style.display = '';
                 }
            }
        }

        // Fungsi untuk menggambar hari-hari dalam kalender mini (7 hari)
        function renderCalendar() {
            // Hapus isi container kalender sebelumnya
            calendarDaysContainer.innerHTML = ''; 

            // Atur tanggal awal tampilan kalender (3 hari sebelum tanggal yang dipilih)
            let startDate = new Date(selectedDate);
            startDate.setDate(selectedDate.getDate() - 3);

            // Update tampilan bulan dan tahun
            monthYearDisplay.textContent = `${MONTHS[selectedDate.getMonth()]} ${selectedDate.getFullYear()}`;
            
            const todayFormatted = formatDate(today);

            for (let i = 0; i < 7; i++) {
                const day = new Date(startDate);
                day.setDate(startDate.getDate() + i);

                const dayIndex = day.getDay();
                const dayText = WEEKDAYS[dayIndex];
                const dateText = day.getDate();
                const dateFormatted = formatDate(day);
                
                // Ambil data games dummy (atau 0 jika tidak ada)
                const gameCount = DUMMY_GAME_COUNTS[dateFormatted] || 0;
                
                // Tambahkan elemen hari
                const dayDiv = document.createElement('div');
                dayDiv.classList.add('calendar-day', 'd-flex', 'flex-column', 'justify-content-center', 'align-items-center');
                dayDiv.setAttribute('data-date', dateFormatted);
                
                // Tambahkan kelas 'active' jika ini adalah tanggal yang dipilih
                if (dateFormatted === formatDate(selectedDate)) {
                    dayDiv.classList.add('active');
                }

                // Tambahkan kelas 'today-box' jika ini adalah hari ini
                if (dateFormatted === todayFormatted) {
                    // Hanya tambahkan penanda visual, tidak mempengaruhi logika active
                    // dayDiv.classList.add('today-box'); 
                }
                
                // Struktur HTML baru
                dayDiv.innerHTML = `
                    <div class="day-of-week">${dayText}</div>
                    <div class="date-number">${dateText}</div>
                    <div class="game-count text-danger">${gameCount > 0 ? gameCount + ' GAMES' : 'OFF'}</div>
                `;
                
                // Tambahkan event listener untuk memilih hari
                dayDiv.addEventListener('click', () => selectDate(day));
                
                calendarDaysContainer.appendChild(dayDiv);
            }
            
            // Setelah kalender dirender, filter game yang sesuai dengan tanggal yang dipilih
            filterGames(formatDate(selectedDate));
        }
        
        // Fungsi untuk memilih tanggal (ketika mengklik hari di kalender)
        function selectDate(date) {
            selectedDate = date;
            renderCalendar(); // Render ulang kalender untuk memperbarui status 'active' dan game
        }

        // Fungsi untuk pindah ke hari ini
        function selectToday() {
            selectedDate = new Date();
            renderCalendar();
        }

        // Fungsi untuk mengubah bulan (ketika mengklik tombol < atau >)
        function changeMonth(direction) {
            // Ubah tanggal yang dipilih ke tanggal yang sama di bulan berikutnya atau sebelumnya
            selectedDate.setMonth(selectedDate.getMonth() + direction);
            
            // Periksa apakah bulan berubah. Jika ya, setel tanggal ke tgl 15 untuk tampilan tengah
            const newMonth = selectedDate.getMonth();
            const currentMonth = new Date().getMonth();
            
            // Jika tanggal yang dipilih saat ini tidak ada di bulan baru (misalnya dari 31 Jan ke Feb)
            // setDate akan otomatis menyesuaikan (misalnya menjadi 2 Mar). Kita kembalikan ke tgl 15.
            if (newMonth !== (currentMonth + direction) % 12) {
                 selectedDate.setDate(15);
            }

            renderCalendar(); 
        }

        // --- 2. LOGIKA LAYOUT NAVIGASI ---
        function adjustSubNavbar() {
            const main = document.querySelector('.navbar-main');
            const sub = document.getElementById('subNavbar');
            if (main && sub) {
                // Atur posisi sub-navbar tepat di bawah navbar utama
                sub.style.top = main.offsetHeight + 'px';
                // Atur padding body agar konten tidak tertutup oleh kedua navbar
                document.body.style.paddingTop = (main.offsetHeight + sub.offsetHeight + 30) + 'px';
            }
        }
        
        // Inisialisasi pada saat load
        window.addEventListener('load', () => {
            adjustSubNavbar();
            renderCalendar(); // Render kalender pertama kali
        });
        
        // Atur ulang pada saat resize
        window.addEventListener('resize', adjustSubNavbar);
    </script>
</body>

</html>