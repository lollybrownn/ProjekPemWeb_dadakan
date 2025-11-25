<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBA Games & Scores - HoopWave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f9f9f9; font-family:'Helvetica Neue',Arial,sans-serif; padding-top:170px; }
        
        /* Navbar Utama */
        .navbar-main { z-index:1050 !important; }
        #subNavbar { z-index:1040 !important; background:#f8f9fa !important; }

        /* Teams Dropdown */
        .teams-dropdown { width:360px !important; max-height:80vh; overflow-y:auto; padding:0.5rem 0; box-shadow:0 10px 30px rgba(0,0,0,0.15); }
        .team-item { display:flex !important; align-items:center; gap:12px; padding:0.45rem 1rem; }
        .team-item img { width:26px; height:26px; }
        .team-item:hover { background:#f8f9fa; color:#0d6efd !important; border-radius:6px; }

        /* Underline Home */
        .nav-underline-custom { position:relative; color:#000 !important; font-weight:600; }
        .nav-underline-custom::after { content:''; position:absolute; bottom:-6px; left:50%; transform:translateX(-50%); width:40px; height:4px; background:#000; border-radius:2px; transition:all .3s; }
        .nav-underline-custom:hover::after { width:60px; }

        /* Game Card NBA Style */
        .game-card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 8px 25px rgba(0,0,0,0.1); margin-bottom:2rem; }
        .team-logo { width:72px; height:72px; object-fit:contain; }
        .score-big { font-size:3.5rem; font-weight:900; line-height:1; }
        .final-text { font-size:1.1rem; font-weight:700; color:#000; }
        .btn-league-pass { background:#ffb700 !important; color:#000 !important; font-weight:bold; border:none; }
        .leader-img { width:56px; height:56px; border-radius:50%; border:3px solid #fff; box-shadow:0 3px 10px rgba(0,0,0,0.2); }
        .recap-thumb { border-radius:14px; overflow:hidden; position:relative; }
        .recap-overlay { position:absolute; bottom:0; left:0; right:0; background:linear-gradient(transparent,rgba(0,0,0,0.85)); color:#fff; padding:2rem 1rem 1rem; text-align:center; font-weight:bold; }
        .today-box { border:2px solid #000 !important; background:#fff !important; color:#000; border-radius:8px; }
        .calendar-day { width:48px; text-align:center; padding:10px 0; border-radius:8px; }
    </style>
</head>
<body>

    <?php include "navbar.php"?>

    <!-- SUB-NAVBAR GAMES (menempel tepat di bawah navbar utama) -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom" id="subNavbar" style="position:fixed; left:0; right:0; z-index:1040;">
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
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-4">
            <h2 class="fw-bold text-uppercase mb-0">NBA Games & Scores</h2>
            <div class="d-flex flex-wrap align-items-center gap-4">
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-bold">November 2025</span>
                    <button class="btn btn-link text-dark p-0 fs-4">&lt;</button>
                    <button class="btn btn-link text-dark p-0 fs-4">&gt;</button>
                </div>
                <div class="d-flex gap-1 bg-white rounded-3 shadow-sm p-2">
                    <div class="calendar-day"><small class="text-muted">Sun</small><br>23</div>
                    <div class="calendar-day"><small class="text-muted">Mon</small><br>24</div>
                    <div class="calendar-day today-box"><small class="text-muted">Tue</small><br><strong>25</strong></div>
                    <div class="calendar-day"><small class="text-muted">Wed</small><br>26</div>
                    <div class="calendar-day"><small class="text-muted">Thu</small><br>27</div>
                    <div class="calendar-day"><small class="text-muted">Fri</small><br>28</div>
                    <div class="calendar-day"><small class="text-muted">Sat</small><br>29</div>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="hideScores">
                    <label class="form-check-label text-muted fw-medium" for="hideScores">HIDE SCORES</label>
                </div>
            </div>
        </div>

        <!-- GAME CARD 1 -->
        <div class="game-card p-4 p-md-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5">
                    <div class="d-flex justify-content-between align-items-center text-center">
                        <div>
                            <img src="https://cdn.nba.com/logos/nba/1610612765/primary/L/logo.svg" class="team-logo" alt="Pistons">
                            <div class="mt-3 fw-bold fs-5">Pistons<br><small class="text-muted">15-2</small></div>
                        </div>
                        <div>
                            <div class="score-big">122</div>
                            <div class="final-text mt-2">FINAL</div>
                            <div class="score-big mt-3">117</div>
                        </div>
                        <div>
                            <img src="https://cdn.nba.com/logos/nba/1610612754/primary/L/logo.svg" class="team-logo" alt="Pacers">
                            <div class="mt-3 fw-bold fs-5">Pacers<br><small class="text-muted">2-15</small></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
                        <button class="btn btn-outline-dark rounded-pill px-4">Watch</button>
                        <button class="btn btn-outline-dark rounded-pill px-4">Box Score</button>
                        <button class="btn btn-outline-dark rounded-pill px-4">Game Details</button>
                        <button class="btn btn-league-pass rounded-pill px-4">League Pass</button>
                    </div>
                </div>

                <div class="col-lg-4">
                    <h5 class="text-center fw-bold mb-4">GAME LEADERS</h5>
                    <div class="row text-center">
                        <div class="col-6">
                            <img src="https://cdn.nba.com/headshots/nba/latest/1040x760/1641730.png" class="leader-img" alt="Cade">
                            <p class="mt-2 mb-0 fw-bold small">Cade Cunningham</p>
                            <p class="text-muted small">DET #2 PG</p>
                            <div class="d-flex justify-content-center gap-3 mt-2">
                                <div><strong>24</strong><br><small>PTS</small></div>
                                <div><strong>11</strong><br><small>REB</small></div>
                                <div><strong>6</strong><br><small>AST</small></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <img src="https://cdn.nba.com/headshots/nba/latest/1040x760/1630162.png" class="leader-img" alt="Siakam">
                            <p class="mt-2 mb-0 fw-bold small">Pascal Siakam</p>
                            <p class="text-muted small">IND #43 PF</p>
                            <div class="d-flex justify-content-center gap-3 mt-2">
                                <div><strong>24</strong><br><small>PTS</small></div>
                                <div><strong>8</strong><br><small>REB</small></div>
                                <div><strong>3</strong><br><small>AST</small></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <h5 class="text-center fw-bold mb-4">GAME RECAP</h5>
                    <div class="recap-thumb">
                        <img src="https://cdn.nba.com/manage/2025/11/recap-det-ind.jpg" class="w-100" alt="Recap">
                        <div class="recap-overlay">FULL GAME RECAP ► 12:09</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GAME CARD 2 (contoh lagi) -->
        <div class="game-card p-4 p-md-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5">
                    <div class="d-flex justify-content-between align-items-center text-center">
                        <div>
                            <img src="https://cdn.nba.com/logos/nba/1610612739/primary/L/logo.svg" class="team-logo" alt="Cavaliers">
                            <div class="mt-3 fw-bold fs-5">Cavaliers<br><small class="text-muted">12-7</small></div>
                        </div>
                        <div>
                            <div class="score-big">99</div>
                            <div class="final-text mt-2">FINAL</div>
                            <div class="score-big mt-3">110</div>
                        </div>
                        <div>
                            <img src="https://cdn.nba.com/logos/nba/1610612761/primary/L/logo.svg" class="team-logo" alt="Raptors">
                            <div class="mt-3 fw-bold fs-5">Raptors<br><small class="text-muted">13-5</small></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
                        <button class="btn btn-outline-dark rounded-pill px-4">Watch</button>
                        <button class="btn btn-outline-dark rounded-pill px-4">Box Score</button>
                        <button class="btn btn-league-pass rounded-pill px-4">League Pass</button>
                    </div>
                </div>
                <!-- Leaders & Recap bisa ditambahkan lagi -->
            </div>
        </div>

    </div>

    <!-- Script agar sub-navbar selalu menempel tepat di bawah navbar utama -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function adjustSubNavbar() {
            const main = document.querySelector('.navbar-main');
            const sub  = document.getElementById('subNavbar');
            if (main && sub) {
                sub.style.top = main.offsetHeight + 'px';
                document.body.style.paddingTop = (main.offsetHeight + sub.offsetHeight + 30) + 'px';
            }
        }
        window.addEventListener('load', adjustSubNavbar);
        window.addEventListener('resize', adjustSubNavbar);
    </script>
</body>
</html>