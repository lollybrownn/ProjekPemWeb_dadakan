<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HoopWave – CLE vs TOR Box Score</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#000; color:#fff; font-family:'Helvetica Neue',Arial,sans-serif; margin:0; }
        .main-content { padding-top:140px; padding-bottom:4rem; }

        .hero-video { max-width:860px; margin:0 auto 2rem; border-radius:20px; overflow:hidden; box-shadow:0 25px 60px rgba(0,0,0,0.8); }

        .btn-today {
            background:#c8102e; color:#fff; font-weight:800; font-size:1.3rem;
            padding:16px 70px; border-radius:50px; box-shadow:0 12px 35px rgba(200,16,46,0.6);
            transition:all .3s; display:inline-block;
        }
        .btn-today:hover { background:#e0203d; transform:translateY(-4px); box-shadow:0 20px 50px rgba(200,16,46,0.7); }

        .game-header { text-align:center; margin:3rem 0 4rem; }
        .game-header h1 { font-size:2.8rem; font-weight:900; letter-spacing:1px; }
        .score-win { color:#28a745; }
        .score-lose { color:#dc3545; }

        .boxscore-card {
            background:#111; border-radius:16px; overflow:hidden;
            box-shadow:0 15px 40px rgba(0,0,0,0.6); margin-bottom:3rem;
        }
        .team-title {
            background:#c8102e; color:#fff; font-size:1.4rem; font-weight:800;
            text-align:center; padding:14px; letter-spacing:1.2px;
        }
        .box-table { margin:0; font-size:0.925rem; }
        .box-table th {
            background:#1e1e1e; color:#aaa; font-weight:600; font-size:0.78rem;
            text-transform:uppercase; letter-spacing:0.8px; padding:12px 8px;
            text-align:center; border-bottom:2px solid #333;
        }
        .box-table td {
            padding:11px 8px; text-align:center; vertical-align:middle;
            border-bottom:1px solid #222;
        }
        .box-table tbody tr:hover { background:#1a1a1a; transition:background .2s; }
        .player-cell {
            display:flex; align-items:center; gap:8px; font-weight:500;
            text-align:left !important;
        }
        .player-img {
            width:28px; height:28px; border-radius:50%; object-fit:cover;
            border:1px solid #444; flex-shrink:0;
        }
        .totals-row { background:#222 !important; font-weight:800; font-size:1.02rem; color:#fff; }
        .plus { color:#28a745; font-weight:700; }
        .minus { color:#dc3545; font-weight:700; }
        .dnp { color:#777; font-style:italic; text-align:center; padding:10px !important; }

        @media (max-width:992px) {
            .box-table { font-size:0.85rem; }
            .player-img { width:24px; height:24px; }
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

        <!-- VIDEO HIGHLIGHT -->
        <div class="hero-video ratio ratio-16x9">
            <iframe src="https://www.youtube.com/embed/jwye6qMzxLU?autoplay=1&mute=1&loop=1&playlist=jwye6qMzxLU&controls=1&rel=0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- TOMBOL TODAY'S GAMES -->
        <div class="text-center mb-5">
            <a href="home_games.php" class="btn-today text-decoration-none">Today's Games</a>
        </div>

        <!-- GAME HEADER -->
        <div class="game-header">
            <h1>
                CLEVELAND CAVALIERS <span class="score-win">99</span> – <span class="score-lose">110</span> TORONTO RAPTORS
            </h1>
            <p class="text-muted fs-5">Monday, November 24, 2025 • Final • Scotiabank Arena, Toronto</p>
        </div>

        <!-- CLEVELAND CAVALIERS -->
        <div class="boxscore-card">
            <div class="team-title">CLEVELAND CAVALIERS</div>
            <table class="table table-dark box-table mb-0">
                <thead>
                    <tr>
                        <th>PLAYER</th><th>MIN</th><th>FGM</th><th>FGA</th><th>FG%</th>
                        <th>3PM</th><th>3PA</th><th>3P%</th><th>FTM</th><th>FTA</th><th>FT%</th>
                        <th>OREB</th><th>DREB</th><th>REB</th><th>AST</th><th>STL</th><th>BLK</th><th>TO</th><th>PF</th><th>PTS</th><th>+/-</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1629630.png" class="player-img" alt="Darius Garland"> Darius Garland</td>
                        <td>33:12</td><td>11</td><td>18</td><td>61.1</td><td>6</td><td>10</td><td>60.0</td><td>4</td><td>4</td><td>100</td><td>0</td><td>3</td><td>3</td><td>12</td><td>2</td><td>0</td><td>2</td><td>1</td><td>32</td><td class="plus">+24</td>
                    </tr>
                    <tr>
                        <td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1630581.png" class="player-img" alt="Evan Mobley"> Evan Mobley</td>
                        <td>31:45</td><td>9</td><td>14</td><td>64.3</td><td>1</td><td>2</td><td>50.0</td><td>4</td><td>6</td><td>66.7</td><td>3</td><td>9</td><td>12</td><td>3</td><td>1</td><td>3</td><td>1</td><td>3</td><td>23</td><td class="plus">+18</td>
                    </tr>
                    <tr>
                        <td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1628970.png" class="player-img" alt="Donovan Mitchell"> Donovan Mitchell</td>
                        <td>30:22</td><td>10</td><td>19</td><td>52.6</td><td>4</td><td>9</td><td>44.4</td><td>6</td><td>6</td><td>100</td><td>0</td><td>5</td><td>5</td><td>7</td><td>2</td><td>0</td><td>3</td><td>2</td><td>30</td><td class="plus">+21</td>
                    </tr>
                    <tr>
                        <td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1629639.png" class="player-img" alt="Ty Jerome"> Ty Jerome</td>
                        <td>28:15</td><td>7</td><td>11</td><td>63.6</td><td>4</td><td>7</td><td>57.1</td><td>2</td><td>2</td><td>100</td><td>0</td><td>4</td><td>4</td><td>5</td><td>1</td><td>0</td><td>1</td><td>2</td><td>20</td><td class="plus">+15</td>
                    </tr>
                    <tr>
                        <td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1628960.png" class="player-img" alt="Dean Wade"> Dean Wade</td>
                        <td>25:48</td><td>5</td><td>8</td><td>62.5</td><td>4</td><td>7</td><td>57.1</td><td>0</td><td>0</td><td>—</td><td>1</td><td>6</td><td>7</td><td>2</td><td>1</td><td>1</td><td>0</td><td>1</td><td>14</td><td class="plus">+12</td>
                    </tr>
                    <!-- Bench -->
                    <tr><td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1630596.png" class="player-img" alt="Caris LeVert"> Caris LeVert</td><td>22:10</td><td>4</td><td>9</td><td>44.4</td><td>2</td><td>5</td><td>40.0</td><td>2</td><td>2</td><td>100</td><td>0</td><td>3</td><td>3</td><td>4</td><td>1</td><td>0</td><td>2</td><td>1</td><td>12</td><td class="plus">+8</td></tr>
                    <tr><td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1627783.png" class="player-img" alt="Georges Niang"> Georges Niang</td><td>18:33</td><td>3</td><td>6</td><td>50.0</td><td>2</td><td>5</td><td>40.0</td><td>0</td><td>0</td><td>—</td><td>0</td><td>2</td><td>2</td><td>1</td><td>0</td><td>0</td><td>0</td><td>1</td><td>8</td><td class="plus">+5</td></tr>
                    <tr><td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1641727.png" class="player-img" alt="Jaylon Tyson"> Jaylon Tyson</td><td>15:20</td><td>2</td><td>5</td><td>40.0</td><td>1</td><td>3</td><td>33.3</td><td>0</td><td>0</td><td>—</td><td>1</td><td>3</td><td>4</td><td>1</td><td>0</td><td>0</td><td>1</td><td>0</td><td>5</td><td class="plus">+3</td></tr>
                    <tr><td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1631108.png" class="player-img" alt="Isaac Okoro"> Isaac Okoro</td><td>14:55</td><td>2</td><td>4</td><td>50.0</td><td>1</td><td>2</td><td>50.0</td><td>0</td><td>0</td><td>—</td><td>0</td><td>2</td><td>2</td><td>1</td><td>1</td><td>0</td><td>0</td><td>2</td><td>5</td><td class="plus">+6</td></tr>
                    <tr><td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1630532.png" class="player-img" alt="Jarrett Allen"> Jarrett Allen</td><td>19:40</td><td>3</td><td>5</td><td>60.0</td><td>0</td><td>0</td><td>—</td><td>2</td><td>2</td><td>100</td><td>2</td><td>5</td><td>7</td><td>1</td><td>0</td><td>1</td><td>1</td><td>3</td><td>8</td><td class="plus">+9</td></tr>

                    <tr class="dnp"><td colspan="21">Tristan Thompson – DNP - Coach's Decision</td></tr>
                    <tr class="dnp"><td colspan="21">Luke Travers – DNP - Coach's Decision</td></tr>

                    <tr class="totals-row">
                        <td><strong>TOTALS</strong></td><td></td><td><strong>56</strong></td><td><strong>99</strong></td><td><strong>56.6</strong></td>
                        <td><strong>25</strong></td><td><strong>50</strong></td><td><strong>50.0</strong></td><td><strong>20</strong></td><td><strong>22</strong></td><td><strong>90.9</strong></td>
                        <td><strong>7</strong></td><td><strong>42</strong></td><td><strong>49</strong></td><td><strong>37</strong></td><td><strong>9</strong></td><td><strong>5</strong></td><td><strong>11</strong></td><td><strong>16</strong></td><td><strong>157</strong> Wait, no — 135</td><td><strong class="plus">+19</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- TORONTO RAPTORS -->
        <div class="boxscore-card">
            <div class="team-title">TORONTO RAPTORS</div>
            <table class="table table-dark box-table mb-0">
                <thead>
                    <tr>
                        <th>PLAYER</th><th>MIN</th><th>FGM</th><th>FGA</th><th>FG%</th>
                        <th>3PM</th><th>3PA</th><th>3P%</th><th>FTM</th><th>FTA</th><th>FT%</th>
                        <th>OREB</th><th>DREB</th><th>REB</th><th>AST</th><th>STL</th><th>BLK</th><th>TO</th><th>PF</th><th>PTS</th><th>+/-</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1628384.png" class="player-img" alt="Scottie Barnes"> Scottie Barnes</td><td>34:21</td><td>9</td><td>19</td><td>47.4</td><td>2</td><td>6</td><td>33.3</td><td>6</td><td>8</td><td>75.0</td><td>2</td><td>7</td><td>9</td><td>8</td><td>1</td><td>1</td><td>4</td><td>3</td><td>26</td><td class="minus">-14</td></tr>
                    <tr><td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1630567.png" class="player-img" alt="RJ Barrett"> RJ Barrett</td><td>33:10</td><td>8</td><td>17</td><td>47.1</td><td>2</td><td>5</td><td>40.0</td><td>4</td><td>4</td><td>100</td><td>1</td><td>5</td><td>6</td><td>4</td><td>0</td><td>0</td><td>2</td><td>2</td><td>22</td><td class="minus">-16</td></tr>
                    <tr><td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1627832.png" class="player-img" alt="Jakob Poeltl"> Jakob Poeltl</td><td>28:45</td><td>7</td><td>11</td><td>63.6</td><td>0</td><td>0</td><td>—</td><td>2</td><td>4</td><td>50.0</td><td>4</td><td>6</td><td>10</td><td>3</td><td>1</td><td>2</td><td>2</td><td>4</td><td>16</td><td class="minus">-12</td></tr>
                    <tr><td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1629611.png" class="player-img" alt="Gradey Dick"> Gradey Dick</td><td>27:33</td><td>6</td><td>12</td><td>50.0</td><td>3</td><td>7</td><td>42.9</td><td>2</td><td>2</td><td>100</td><td>0</td><td>3</td><td>3</td><td>2</td><td>0</td><td>0</td><td>1</td><td>1</td><td>17</td><td class="minus">-10</td></tr>
                    <tr><td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1630197.png" class="player-img" alt="Immanuel Quickley"> Immanuel Quickley</td><td>25:18</td><td>4</td><td>11</td><td>36.4</td><td>2</td><td>6</td><td>33.3</td><td>3</td><td>3</td><td>100</td><td>0</td><td>2</td><td>2</td><td>5</td><td>1</td><td>0</td><td>3</td><td>2</td><td>13</td><td class="minus">-8</td></tr>
                    <!-- Bench -->
                    <tr><td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1630559.png" class="player-img" alt="Davion Mitchell"> Davion Mitchell</td><td>22:40</td><td>3</td><td>7</td><td>42.9</td><td>1</td><td>3</td><td>33.3</td><td>0</td><td>0</td><td>—</td><td>0</td><td>2</td><td>2</td><td>4</td><td>1</td><td>0</td><td>1</td><td>1</td><td>7</td><td class="minus">-9</td></tr>
                    <tr><td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1641708.png" class="player-img" alt="Jonathan Mogbo"> Jonathan Mogbo</td><td>19:15</td><td>3</td><td>5</td><td>60.0</td><td>0</td><td>0</td><td>—</td><td>1</td><td>2</td><td>50.0</td><td>2</td><td>4</td><td>6</td><td>1</td><td>0</td><td>1</td><td>1</td><td>2</td><td>7</td><td class="minus">-7</td></tr>
                    <tr><td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1629640.png" class="player-img" alt="Chris Boucher"> Chris Boucher</td><td>18:22</td><td>2</td><td>6</td><td>33.3</td><td>1</td><td>4</td><td>25.0</td><td>2</td><td>2</td><td>100</td><td>1</td><td>3</td><td>4</td><td>0</td><td>0</td><td>1</td><td>0</td><td>2</td><td>7</td><td class="minus">-5</td></tr>
                    <tr><td class="player-cell"><img src="https://cdn.nba.com/headshots/nba/latest/260x190/1631107.png" class="player-img" alt="Jamal Shead"> Jamal Shead</td><td>15:36</td><td>1</td><td>4</td><td>25.0</td><td>0</td><td>1</td><td>0.0</td><td>0</td><td>0</td><td>—</td><td>0</td><td>1</td><td>1</td><td>2</td><td>1</td><td>0</td><td>1</td><td>1</td><td>2</td><td class="minus">-4</td></tr>

                    <tr class="totals-row">
                        <td><strong>TOTALS</strong></td><td></td><td><strong>43</strong></td><td><strong>92</strong></td><td><strong>46.7</strong></td>
                        <td><strong>11</strong></td><td><strong>32</strong></td><td><strong>34.4</strong></td><td><strong>19</strong></td><td><strong>25</strong></td><td><strong>76.0</strong></td>
                        <td><strong>10</strong></td><td><strong>33</strong></td><td><strong>43</strong></td><td><strong>29</strong></td><td><strong>5</strong></td><td><strong>5</strong></td><td><strong>15</strong></td><td><strong>18</strong></td><td><strong>116</strong></td><td><strong class="minus">-19</strong></td>
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