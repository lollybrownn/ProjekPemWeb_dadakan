<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">
            <img src="asset/logo1.png" alt="HoopWave" width="100" height="100">
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Games
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="home_games.php">Home</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Teams
                    </a>
                    <ul class="dropdown-menu"> <!-- class ini yang kita kasih scroll -->
                        <li><h6 class="dropdown-header">Eastern Conference</h6></li>
                        <li><a class="dropdown-item" href="team.php?id=atlanta-hawks">Atlanta Hawks</a></li>
                        <li><a class="dropdown-item" href="team.php?id=boston-celtics">Boston Celtics</a></li>
                        <li><a class="dropdown-item" href="team.php?id=brooklyn-nets">Brooklyn Nets</a></li>
                        <li><a class="dropdown-item" href="team.php?id=charlotte-hornets">Charlotte Hornets</a></li>
                        <li><a class="dropdown-item" href="team.php?id=chicago-bulls">Chicago Bulls</a></li>
                        <li><a class="dropdown-item" href="team.php?id=cleveland-cavaliers">Cleveland Cavaliers</a></li>
                        <li><a class="dropdown-item" href="team.php?id=detroit-pistons">Detroit Pistons</a></li>
                        <li><a class="dropdown-item" href="team.php?id=indiana-pacers">Indiana Pacers</a></li>
                        <li><a class="dropdown-item" href="team.php?id=miami-heat">Miami Heat</a></li>
                        <li><a class="dropdown-item" href="team.php?id=milwaukee-bucks">Milwaukee Bucks</a></li>
                        <li><a class="dropdown-item" href="team.php?id=new-york-knicks">New York Knicks</a></li>
                        <li><a class="dropdown-item" href="team.php?id=orlando-magic">Orlando Magic</a></li>
                        <li><a class="dropdown-item" href="team.php?id=philadelphia-76ers">Philadelphia 76ers</a></li>
                        <li><a class="dropdown-item" href="team.php?id=toronto-raptors">Toronto Raptors</a></li>
                        <li><a class="dropdown-item" href="team.php?id=washington-wizards">Washington Wizards</a></li>

                        <li>
                            <hr class="dropdown-divider">
                        </li> 

                        <li><h6 class="dropdown-header">Western Conference</h6></li>
                        <li><a class="dropdown-item" href="team.php?id=dallas-mavericks">Dallas Mavericks</a></li>
                        <li><a class="dropdown-item" href="team.php?id=denver-nuggets">Denver Nuggets</a></li>
                        <li><a class="dropdown-item" href="team.php?id=golden-state-warriors">Golden State Warriors</a>
                        </li>
                        <li><a class="dropdown-item" href="team.php?id=houston-rockets">Houston Rockets</a></li>
                        <li><a class="dropdown-item" href="team.php?id=la-clippers">LA Clippers</a></li>
                        <li><a class="dropdown-item" href="team.php?id=los-angeles-lakers">Los Angeles Lakers</a></li>
                        <li><a class="dropdown-item" href="team.php?id=memphis-grizzlies">Memphis Grizzlies</a></li>
                        <li><a class="dropdown-item" href="team.php?id=minnesota-timberwolves">Minnesota
                                Timberwolves</a></li>
                        <li><a class="dropdown-item" href="team.php?id=new-orleans-pelicans">New Orleans Pelicans</a>
                        </li>
                        <li><a class="dropdown-item" href="team.php?id=oklahoma-city-thunder">Oklahoma City Thunder</a>
                        </li>
                        <li><a class="dropdown-item" href="team.php?id=phoenix-suns">Phoenix Suns</a></li>
                        <li><a class="dropdown-item" href="team.php?id=portland-trail-blazers">Portland Trail
                                Blazers</a></li>
                        <li><a class="dropdown-item" href="team.php?id=sacramento-kings">Sacramento Kings</a></li>
                        <li><a class="dropdown-item" href="team.php?id=san-antonio-spurs">San Antonio Spurs</a></li>
                        <li><a class="dropdown-item" href="team.php?id=utah-jazz">Utah Jazz</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>