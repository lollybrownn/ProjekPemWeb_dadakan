<?php
include "connection.php";

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

// Tanggal
$selectedDate = $_GET['date'] ?? date('Y-m-d');
$displayDate  = date('l, F j, Y', strtotime($selectedDate));

$prev = date('Y-m-d', strtotime($selectedDate . ' -1 day'));
$next = date('Y-m-d', strtotime($selectedDate . ' +1 day'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBA Games - HoopWave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f9f9f9; padding-top:170px; font-family:'Helvetica Neue',Arial,sans-serif; }
        .navbar-main { background-image:url(asset/background-navbar1.png); background-position:center; background-repeat: no-repeat;}
        #subNavbar { background:#fff !important; border-bottom:1px solid #ddd; }
        .game-wrapper {
            background:#fff;
            border-radius:16px;
            box-shadow:0 8px 30px rgba(0,0,0,0.1);
            overflow:hidden;
            margin-bottom:3rem;
        }
        .team-logo { width:72px; height:72px; }
        .score-big { font-size:3.5rem; font-weight:900; line-height:1; }
        .final-text { font-size:1.1rem; font-weight:700; color:#000; }
        .leader-img { width:60px; height:60px; border-radius:50%; object-fit:cover; border:3px solid #eee; }
        .btn-outline-dark { border-radius:50px; }
        .navbar-main {
            background-image: url(asset/background-navbar1.png);
            background-position: center;
            background-repeat: no-repeat;
            background-color: transparent !important;
            background-size: cover;
        }
        
        .teams-dropdown {
            width: 360px !important;
            /* khusus Teams saja */
            max-height: 80vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.5rem 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        /* Dropdown lain (Games, dll) tetap normal */
        .dropdown-menu:not(.teams-dropdown) {
            width: auto;
            max-height: none;
            overflow: visible;
        }

        /* Logo + nama tim rapi */
        .team-item {
            display: flex !important;
            align-items: center;
            gap: 12px;
            padding: 0.45rem 1rem;
            transition: all 0.2s;
        }

        .team-item img {
            width: 26px;
            height: 26px;
            flex-shrink: 0;
        }

        .team-item:hover {
            background-color: #f8f9fa;
            color: #0d6efd !important;
            border-radius: 6px;
        }

        .dropdown-header {
            padding-left: 1.5rem;
            font-weight: 700;
            font-size: 0.95rem;
            color: #1a1a1a;
        }

        /* Hover buka dropdown di desktop (lebih smooth) */
        @media (min-width: 992px) {
            .dropdown:hover>.dropdown-menu {
                display: block;
            }
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<!-- Sub Navbar + Tanggal -->
<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm fixed-top" id="subNavbar" style="top:100px; z-index: 1;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4 text-dark">Games</a>
        <div class="ms-auto d-flex align-items-center gap-2">
            <a href="?date=<?= $prev ?>" class="btn btn-outline-secondary btn-sm">Previous</a>
            <span class="fw-bold px-3"><?= $displayDate ?></span>
            <a href="?date=<?= $next ?>" class="btn btn-outline-secondary btn-sm">Next</a>
            <a href="home_games.php" class="btn btn-dark btn-sm">Today</a>
        </div>
    </div>
</nav>

<div class="container my-5">

<?php
$stmt = $conn->prepare("SELECT * FROM games WHERE DATE(game_date)=? ORDER BY id ASC");
$stmt->bind_param("s", $selectedDate);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0):
    while ($game = $result->fetch_assoc()):
        $homeWin = $game['home_score'] > $game['away_score'];
        $awayWin = $game['away_score'] > $game['home_score'];
?>
    <!-- SATU KOTAK BESAR (Skor + Leaders + Recap) -->
    <div class="game-wrapper">
        <div class="row g-0">

            <!-- KIRI: Skor & Tim -->
            <div class="col-lg-5 bg-white p-4 d-flex align-items-center justify-content-center">
                <div class="w-100 text-center">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="badge bg-dark fs-6"><?= strtoupper($game['status']) ?></span>
                        <small class="text-muted">7:30 PM ET</small>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-5">
                            <img src="https://cdn.nba.com/logos/nba/<?= teamCode($game['away_team']) ?>/primary/L/logo.svg" class="team-logo" alt="">
                            <div class="mt-2 fw-bold fs-5"><?= shortName($game['away_team']) ?></div>
                            <div class="score-big <?= $awayWin?'text-success':'' ?>"><?= $game['away_score']??'-' ?></div>
                        </div>
                        <div class="col-2">
                            <h3 class="mb-0">VS</h3>
                            <?php if($game['status']=='final'): ?>
                                <div class="final-text mt-2">FINAL</div>
                            <?php endif; ?>
                        </div>
                        <div class="col-5">
                            <img src="https://cdn.nba.com/logos/nba/<?= teamCode($game['home_team']) ?>/primary/L/logo.svg" class="team-logo" alt="">
                            <div class="mt-2 fw-bold fs-5"><?= shortName($game['home_team']) ?></div>
                            <div class="score-big <?= $homeWin?'text-success':'' ?>"><?= $game['home_score']??'-' ?></div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <?php if($game['box_score_file'] && file_exists($game['box_score_file'])): ?>
                            <a href="<?= $game['box_score_file'] ?>" class="btn btn-outline-dark rounded-pill px-5">Box Score</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- TENGAH: Game Leaders -->
            <div class="col-lg-3 bg-light p-4 d-flex align-items-center justify-content-center">
                <div class="text-center w-100">
                    <h5 class="fw-bold mb-4 text-uppercase">Game Leaders</h5>
                    <div class="row">
                        <div class="col-6">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1628378.png" class="leader-img" alt="">
                            <p class="mt-2 mb-0 fw-bold small">Donovan Mitchell</p>
                            <p class="text-muted small">CLE • SG</p>
                            <div class="d-flex justify-content-center gap-3 mt-2 small">
                                <div><strong>17</strong><br>PTS</div>
                                <div><strong>1</strong><br>REB</div>
                                <div><strong>8</strong><br>AST</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1627742.png" class="leader-img" alt="">
                            <p class="mt-2 mb-0 fw-bold small">Brandon Ingram</p>
                            <p class="text-muted small">TOR • SF</p>
                            <div class="d-flex justify-content-center gap-3 mt-2 small">
                                <div><strong>37</strong><br>PTS</div>
                                <div><strong>7</strong><br>REB</div>
                                <div><strong>2</strong><br>AST</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KANAN: Recap Video -->
            <div class="col-lg-4 bg-white p-4 d-flex align-items-center justify-content-center">
                <?php if($game['youtube_url']): ?>
                    <div class="w-100">
                        <h5 class="text-center fw-bold mb-3 text-uppercase">Game Recap</h5>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow">
                            <iframe src="<?= htmlspecialchars($game['youtube_url']) ?>" 
                                    title="Highlights" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">Video recap belum tersedia</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
<?php
    endwhile;
else:
?>
    <div class="text-center py-5">
        <h4 class="text-muted">Tidak ada pertandingan pada tanggal ini</h4>
    </div>
<?php endif; ?>

</div>

<?php
function teamCode($team){ $m=["Atlanta Hawks"=>"1610612737","Boston Celtics"=>"1610612738","Brooklyn Nets"=>"1610612751","Charlotte Hornets"=>"1610612766","Chicago Bulls"=>"1610612741","Cleveland Cavaliers"=>"1610612739","Dallas Mavericks"=>"1610612742","Denver Nuggets"=>"1610612743","Detroit Pistons"=>"1610612765","Golden State Warriors"=>"1610612744","Houston Rockets"=>"1610612745","Indiana Pacers"=>"1610612754","LA Clippers"=>"1610612746","Los Angeles Lakers"=>"1610612747","Memphis Grizzlies"=>"1610612763","Miami Heat"=>"1610612748","Milwaukee Bucks"=>"1610612749","Minnesota Timberwolves"=>"1610612750","New Orleans Pelicans"=>"1610612740","New York Knicks"=>"1610612752","Oklahoma City Thunder"=>"1610612760","Orlando Magic"=>"1610612753","Philadelphia 76ers"=>"1610612755","Phoenix Suns"=>"1610612756","Portland Trail Blazers"=>"1610612757","Sacramento Kings"=>"1610612758","San Antonio Spurs"=>"1610612759","Toronto Raptors"=>"1610612761","Utah Jazz"=>"1610612762","Washington Wizards"=>"1610612764"]; return $m[$team]??'default'; }
function shortName($team){ $p=explode(" ",$team); return end($p); }
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>