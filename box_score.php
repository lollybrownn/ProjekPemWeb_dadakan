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
    <title>HoopWave – DET vs IND Box Score</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#000; color:#fff; font-family:'Helvetica Neue',Arial,sans-serif; margin:0; }
        .main-content { padding-top:140px; padding-bottom:4rem; }

        /* VIDEO */
        .hero-video { max-width:860px; margin:0 auto 2rem; border-radius:20px; overflow:hidden; box-shadow:0 25px 60px rgba(0,0,0,0.8); }

        /* TOMBOL TODAY'S GAMES */
        .btn-today {
            background:#c8102e; color:#fff; font-weight:800; font-size:1.3rem;
            padding:16px 70px; border-radius:50px; box-shadow:0 12px 35px rgba(200,16,46,0.6);
            transition:all .3s; display:inline-block;
        }
        .btn-today:hover { background:#e0203d; transform:translateY(-4px); box-shadow:0 20px 50px rgba(200,16,46,0.7); }

        /* GAME HEADER */
        .game-header { text-align:center; margin:3rem 0 4rem; }
        .game-header h1 { font-size:2.8rem; font-weight:900; letter-spacing:1px; }
        .score-win { color:#28a745; }
        .score-lose { color:#dc3545; }

        /* BOX SCORE TABLE – NBA.COM STYLE 2025 */
        .boxscore-card {
            background:#111;
            border-radius:16px;
            overflow:hidden;
            box-shadow:0 15px 40px rgba(0,0,0,0.6);
            margin-bottom:3rem;
        }
        .team-title {
            background:#c8102e;
            color:#fff;
            font-size:1.4rem;
            font-weight:800;
            text-align:center;
            padding:14px;
            letter-spacing:1.2px;
        }
        .box-table {
            margin:0;
            font-size:0.925rem;
        }
        .box-table th {
            background:#1e1e1e;
            color:#aaa;
            font-weight:600;
            font-size:0.78rem;
            text-transform:uppercase;
            letter-spacing:0.8px;
            padding:12px 8px;
            text-align:center;
            border-bottom:2px solid #333;
        }
        .box-table td {
            padding:11px 8px;
            text-align:center;
            vertical-align:middle;
            border-bottom:1px solid #222;
        }
        .box-table tbody tr:hover {
            background:#1a1a1a;
            transition:background .2s;
        }
        .player-cell {
            display:flex;
            align-items:center;
            gap:8px;
            font-weight:500;
            text-align:left !important;
        }
        .player-img {
            width:28px;  /* Ukuran small sesuai permintaan */
            height:28px;
            border-radius:50%;
            object-fit:cover;
            border:1px solid #444;  /* Border tipis untuk clean */
            flex-shrink:0;
        }
        .totals-row {
            background:#222 !important;
            font-weight:800;
            font-size:1.02rem;
            color:#fff;
        }
        .plus { color:#28a745; font-weight:700; }
        .minus { color:#dc3545; font-weight:700; }
        .dnp {
            color:#777;
            font-style:italic;
            text-align:center;
            padding:10px !important;
        }
        .empty-cell { background:#111; }

        .navbar-main {
            background-image: url(asset/background-navbar.png);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: transparent !important;
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

        /* Responsive */
        @media (max-width:992px) {
            .box-table { font-size:0.85rem; }
            .player-img { width:24px; height:24px; }  /* Bahkan lebih small di tablet */
            .player-cell { gap:6px; }
        }
        @media (max-width:576px) {
            .game-header h1 { font-size:2rem; }
            .btn-today { font-size:1.1rem; padding:14px 50px; }
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="main-content">
    <div class="container">

        <!-- VIDEO HIGHLIGHT (Sesuai NBA.com highlights untuk game ini) -->
        <div class="hero-video ratio ratio-16x9">
            <iframe src="https://www.youtube.com/embed/lSK-01qq9rM?autoplay=1&mute=1&loop=1&playlist=lSK-01qq9rM&controls=1&rel=0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- TOMBOL TODAY'S GAMES -->
        <div class="text-center mb-5">
            <a href="home_games.php" class="btn-today text-decoration-none">Today's Games</a>
        </div>

        <!-- GAME HEADER -->
        <div class="game-header">
            <h1>
                DETROIT PISTONS <span class="score-win">122</span> – <span class="score-lose">117</span> INDIANA PACERS
            </h1>
            <p class="text-muted fs-5">Monday, November 24, 2025 • Final • Little Caesars Arena</p>
        </div>

        <!-- DETROIT PISTONS BOX SCORE -->
        <div class="boxscore-card">
            <div class="team-title">DETROIT PISTONS</div>
            <table class="table table-dark box-table mb-0">
                <thead>
                    <tr>
                        <th>PLAYER</th>
                        <th>MIN</th><th>FGM</th><th>FGA</th><th>FG%</th>
                        <th>3PM</th><th>3PA</th><th>3P%</th>
                        <th>FTM</th><th>FTA</th><th>FT%</th>
                        <th>OREB</th><th>DREB</th><th>REB</th>
                        <th>AST</th><th>STL</th><th>BLK</th><th>TO</th><th>PF</th><th>PTS</th><th>+/-</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1630162.png" class="player-img" alt="Ausar Thompson" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=AT'">
                            Ausar Thompson
                        </td>
                        <td>30:31</td><td>6</td><td>9</td><td>66.7</td><td>0</td><td>0</td><td>—</td><td>1</td><td>1</td><td>100.0</td><td>1</td><td>2</td><td>3</td><td>4</td><td>1</td><td>0</td><td>1</td><td>1</td><td>13</td><td class="plus">+5</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1628389.png" class="player-img" alt="Tobias Harris" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=TH'">
                            Tobias Harris
                        </td>
                        <td>25:49</td><td>4</td><td>8</td><td>50.0</td><td>1</td><td>2</td><td>50.0</td><td>3</td><td>3</td><td>100.0</td><td>0</td><td>1</td><td>1</td><td>2</td><td>1</td><td>0</td><td>1</td><td>4</td><td>12</td><td class="plus">+2</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1628973.png" class="player-img" alt="Jalen Duren" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=JD'">
                            Jalen Duren
                        </td>
                        <td>30:18</td><td>6</td><td>10</td><td>60.0</td><td>0</td><td>0</td><td>—</td><td>5</td><td>8</td><td>62.5</td><td>5</td><td>7</td><td>12</td><td>2</td><td>0</td><td>0</td><td>3</td><td>5</td><td>17</td><td class="plus">+7</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1630595.png" class="player-img" alt="Cade Cunningham" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=CC'">
                            Cade Cunningham
                        </td>
                        <td>35:48</td><td>8</td><td>18</td><td>44.4</td><td>0</td><td>2</td><td>0.0</td><td>8</td><td>10</td><td>80.0</td><td>4</td><td>7</td><td>11</td><td>6</td><td>0</td><td>0</td><td>3</td><td>4</td><td>24</td><td class="minus">-1</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1629636.png" class="player-img" alt="Caris LeVert" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=CL'">
                            Caris LeVert
                        </td>
                        <td>22:30</td><td>5</td><td>9</td><td>55.6</td><td>3</td><td>6</td><td>50.0</td><td>6</td><td>9</td><td>66.7</td><td>0</td><td>1</td><td>1</td><td>3</td><td>1</td><td>1</td><td>1</td><td>1</td><td>19</td><td class="minus">-1</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1631098.png" class="player-img" alt="Isaiah Stewart" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=IS'">
                            Isaiah Stewart
                        </td>
                        <td>19:59</td><td>4</td><td>6</td><td>66.7</td><td>1</td><td>2</td><td>50.0</td><td>0</td><td>0</td><td>—</td><td>1</td><td>4</td><td>5</td><td>0</td><td>1</td><td>2</td><td>3</td><td>9</td><td class="minus">-3</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1641723.png" class="player-img" alt="Ron Holland II" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=RH'">
                            Ron Holland II
                        </td>
                        <td>11:04</td><td>0</td><td>0</td><td>—</td><td>0</td><td>0</td><td>—</td><td>0</td><td>0</td><td>—</td><td>1</td><td>2</td><td>3</td><td>3</td><td>3</td><td>1</td><td>1</td><td>3</td><td>0</td><td class="plus">+1</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1631118.png" class="player-img" alt="Jaden Ivey" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=JI'">
                            Jaden Ivey
                        </td>
                        <td>11:30</td><td>5</td><td>10</td><td>50.0</td><td>2</td><td>3</td><td>66.7</td><td>0</td><td>0</td><td>—</td><td>1</td><td>2</td><td>3</td><td>1</td><td>0</td><td>0</td><td>1</td><td>0</td><td>12</td><td class="plus">+7</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1629654.png" class="player-img" alt="Dennis Schröder" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=DS'">
                            Dennis Schröder
                        </td>
                        <td>7:37</td><td>1</td><td>2</td><td>50.0</td><td>1</td><td>2</td><td>50.0</td><td>2</td><td>2</td><td>100.0</td><td>0</td><td>2</td><td>2</td><td>3</td><td>0</td><td>1</td><td>1</td><td>0</td><td>5</td><td class="minus">-1</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1641725.png" class="player-img" alt="Javonte Green" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=JG'">
                            Javonte Green
                        </td>
                        <td>11:14</td><td>1</td><td>2</td><td>50.0</td><td>0</td><td>0</td><td>—</td><td>0</td><td>0</td><td>—</td><td>0</td><td>0</td><td>0</td><td>0</td><td>1</td><td>1</td><td>1</td><td>2</td><td>2</td><td class="minus">-4</td>
                    </tr>
                    <tr class="dnp"><td colspan="21">Chaz Lanier – DNP - Coach's Decision</td></tr>
                    <tr class="dnp"><td colspan="21">Paul Reed – DNP - Coach's Decision</td></tr>

                    <tr class="totals-row">
                        <td><strong>TOTALS</strong></td>
                        <td></td><td><strong>42</strong></td><td><strong>84</strong></td><td><strong>50.0</strong></td>
                        <td><strong>9</strong></td><td><strong>25</strong></td><td><strong>36.0</strong></td>
                        <td><strong>29</strong></td><td><strong>37</strong></td><td><strong>78.4</strong></td>
                        <td><strong>14</strong></td><td><strong>31</strong></td><td><strong>45</strong></td>
                        <td><strong>24</strong></td><td><strong>8</strong></td><td><strong>4</strong></td><td><strong>15</strong></td><td><strong>27</strong></td><td><strong>122</strong></td><td><strong class="plus">+5</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- INDIANA PACERS BOX SCORE -->
        <div class="boxscore-card">
            <div class="team-title">INDIANA PACERS</div>
            <table class="table table-dark box-table mb-0">
                <thead>
                    <tr>
                        <th>PLAYER</th>
                        <th>MIN</th><th>FGM</th><th>FGA</th><th>FG%</th>
                        <th>3PM</th><th>3PA</th><th>3P%</th>
                        <th>FTM</th><th>FTA</th><th>FT%</th>
                        <th>OREB</th><th>DREB</th><th>REB</th>
                        <th>AST</th><th>STL</th><th>BLK</th><th>TO</th><th>PF</th><th>PTS</th><th>+/-</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1631114.png" class="player-img" alt="Bennedict Mathurin" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=BM'">
                            Bennedict Mathurin
                        </td>
                        <td>32:45</td><td>7</td><td>14</td><td>50.0</td><td>2</td><td>5</td><td>40.0</td><td>3</td><td>4</td><td>75.0</td><td>1</td><td>3</td><td>4</td><td>2</td><td>0</td><td>0</td><td>2</td><td>2</td><td>19</td><td class="minus">-3</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1627783.png" class="player-img" alt="Pascal Siakam" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=PS'">
                            Pascal Siakam
                        </td>
                        <td>35:12</td><td>10</td><td>18</td><td>55.6</td><td>2</td><td>5</td><td>40.0</td><td>2</td><td>3</td><td>66.7</td><td>2</td><td>6</td><td>8</td><td>4</td><td>1</td><td>1</td><td>2</td><td>3</td><td>24</td><td class="minus">-8</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1630168.png" class="player-img" alt="Myles Turner" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=MT'">
                            Myles Turner
                        </td>
                        <td>28:47</td><td>6</td><td>12</td><td>50.0</td><td>1</td><td>3</td><td>33.3</td><td>4</td><td>5</td><td>80.0</td><td>3</td><td>5</td><td>8</td><td>1</td><td>0</td><td>2</td><td>1</td><td>3</td><td>17</td><td class="minus">-6</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1631097.png" class="player-img" alt="Tyrese Haliburton" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=TH'">
                            Tyrese Haliburton
                        </td>
                        <td>34:21</td><td>5</td><td>12</td><td>41.7</td><td>2</td><td>6</td><td>33.3</td><td>3</td><td>3</td><td>100.0</td><td>0</td><td>4</td><td>4</td><td>11</td><td>1</td><td>0</td><td>4</td><td>2</td><td>15</td><td class="minus">-2</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1630597.png" class="player-img" alt="Andrew Nembhard" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=AN'">
                            Andrew Nembhard
                        </td>
                        <td>29:56</td><td>6</td><td>10</td><td>60.0</td><td>1</td><td>2</td><td>50.0</td><td>2</td><td>2</td><td>100.0</td><td>0</td><td>2</td><td>2</td><td>5</td><td>2</td><td>0</td><td>1</td><td>2</td><td>15</td><td class="minus">-5</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1641706.png" class="player-img" alt="Ben Sheppard" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=BS'">
                            Ben Sheppard
                        </td>
                        <td>23:18</td><td>4</td><td>8</td><td>50.0</td><td>2</td><td>4</td><td>50.0</td><td>1</td><td>1</td><td>100.0</td><td>0</td><td>1</td><td>1</td><td>1</td><td>1</td><td>0</td><td>0</td><td>1</td><td>11</td><td class="plus">+1</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1641720.png" class="player-img" alt="Jarace Walker" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=JW'">
                            Jarace Walker
                        </td>
                        <td>18:33</td><td>3</td><td>7</td><td>42.9</td><td>1</td><td>3</td><td>33.3</td><td>0</td><td>0</td><td>—</td><td>2</td><td>2</td><td>4</td><td>1</td><td>0</td><td>0</td><td>1</td><td>1</td><td>7</td><td class="minus">-4</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1641724.png" class="player-img" alt="Johnny Furphy" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=JF'">
                            Johnny Furphy
                        </td>
                        <td>14:22</td><td>2</td><td>5</td><td>40.0</td><td>1</td><td>3</td><td>33.3</td><td>0</td><td>0</td><td>—</td><td>0</td><td>1</td><td>1</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>5</td><td class="minus">-7</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1631193.png" class="player-img" alt="T.J. McConnell" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=TM'">
                            T.J. McConnell
                        </td>
                        <td>12:45</td><td>3</td><td>4</td><td>75.0</td><td>0</td><td>0</td><td>—</td><td>0</td><td>0</td><td>—</td><td>0</td><td>0</td><td>0</td><td>1</td><td>1</td><td>0</td><td>1</td><td>2</td><td>6</td><td class="minus">-6</td>
                    </tr>
                    <tr>
                        <td class="player-cell">
                            <img src="https://cdn.nba.com/headshots/nba/latest/260x190/1641713.png" class="player-img" alt="Enrique Freeman" onerror="this.src='https://via.placeholder.com/28x28/333/fff?text=EF'">
                            Enrique Freeman
                        </td>
                        <td>9:01</td><td>0</td><td>1</td><td>0.0</td><td>0</td><td>0</td><td>—</td><td>0</td><td>0</td><td>—</td><td>0</td><td>1</td><td>1</td><td>0</td><td>0</td><td>0</td><td>0</td><td>1</td><td>0</td><td class="minus">-3</td>
                    </tr>

                    <tr class="totals-row">
                        <td><strong>TOTALS</strong></td>
                        <td></td><td><strong>43</strong></td><td><strong>89</strong></td><td><strong>48.3</strong></td>
                        <td><strong>11</strong></td><td><strong>30</strong></td><td><strong>36.7</strong></td>
                        <td><strong>20</strong></td><td><strong>26</strong></td><td><strong>76.9</strong></td>
                        <td><strong>12</strong></td><td><strong>30</strong></td><td><strong>42</strong></td>
                        <td><strong>26</strong></td><td><strong>7</strong></td><td><strong>3</strong></td><td><strong>14</strong></td><td><strong>25</strong></td><td><strong>117</strong></td><td><strong class="minus">-5</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="home_games.php" class="btn btn-outline-light btn-lg px-5">Kembali ke Games</a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>