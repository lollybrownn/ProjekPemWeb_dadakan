<nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm fixed-top navbar-main">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <img src="asset/logobaru.png" alt="HoopWave" width="200" height="80">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Games</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="home_games.php">Home</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Teams</a>
                        <ul class="dropdown-menu teams-dropdown">
                            <!-- Eastern Conference -->
                            <li><h6 class="dropdown-header">Eastern Conference</h6></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/hawks"><img src="https://cdn.nba.com/logos/nba/1610612737/primary/L/logo.svg" alt="ATL"> Atlanta Hawks</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/celtics"><img src="https://cdn.nba.com/logos/nba/1610612738/primary/L/logo.svg" alt="BOS"> Boston Celtics</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/nets"><img src="https://cdn.nba.com/logos/nba/1610612751/primary/L/logo.svg" alt="BKN"> Brooklyn Nets</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/hornets"><img src="https://cdn.nba.com/logos/nba/1610612766/primary/L/logo.svg" alt="CHA"> Charlotte Hornets</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/bulls"><img src="https://cdn.nba.com/logos/nba/1610612741/primary/L/logo.svg" alt="CHI"> Chicago Bulls</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/cavaliers"><img src="https://cdn.nba.com/logos/nba/1610612739/primary/L/logo.svg" alt="CLE"> Cleveland Cavaliers</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/pistons"><img src="https://cdn.nba.com/logos/nba/1610612765/primary/L/logo.svg" alt="DET"> Detroit Pistons</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/pacers"><img src="https://cdn.nba.com/logos/nba/1610612754/primary/L/logo.svg" alt="IND"> Indiana Pacers</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/heat"><img src="https://cdn.nba.com/logos/nba/1610612748/primary/L/logo.svg" alt="MIA"> Miami Heat</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/bucks"><img src="https://cdn.nba.com/logos/nba/1610612749/primary/L/logo.svg" alt="MIL"> Milwaukee Bucks</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/knicks"><img src="https://cdn.nba.com/logos/nba/1610612752/primary/L/logo.svg" alt="NYK"> New York Knicks</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/magic"><img src="https://cdn.nba.com/logos/nba/1610612753/primary/L/logo.svg" alt="ORL"> Orlando Magic</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/sixers"><img src="https://cdn.nba.com/logos/nba/1610612755/primary/L/logo.svg" alt="PHI"> Philadelphia 76ers</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/raptors"><img src="https://cdn.nba.com/logos/nba/1610612761/primary/L/logo.svg" alt="TOR"> Toronto Raptors</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/wizards"><img src="https://cdn.nba.com/logos/nba/1610612764/primary/L/logo.svg" alt="WAS"> Washington Wizards</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Western Conference</h6></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/mavericks"><img src="https://cdn.nba.com/logos/nba/1610612742/primary/L/logo.svg" alt="DAL"> Dallas Mavericks</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/nuggets"><img src="https://cdn.nba.com/logos/nba/1610612743/primary/L/logo.svg" alt="DEN"> Denver Nuggets</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/warriors"><img src="https://cdn.nba.com/logos/nba/1610612744/primary/L/logo.svg" alt="GSW"> Golden State Warriors</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/rockets"><img src="https://cdn.nba.com/logos/nba/1610612745/primary/L/logo.svg" alt="HOU"> Houston Rockets</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/clippers"><img src="https://cdn.nba.com/logos/nba/1610612746/primary/L/logo.svg" alt="LAC"> LA Clippers</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/lakers"><img src="https://cdn.nba.com/logos/nba/1610612747/primary/L/logo.svg" alt="LAL"> Los Angeles Lakers</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/grizzlies"><img src="https://cdn.nba.com/logos/nba/1610612763/primary/L/logo.svg" alt="MEM"> Memphis Grizzlies</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/timberwolves"><img src="https://cdn.nba.com/logos/nba/1610612750/primary/L/logo.svg" alt="MIN"> Minnesota Timberwolves</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/pelicans"><img src="https://cdn.nba.com/logos/nba/1610612740/primary/L/logo.svg" alt="NOP"> New Orleans Pelicans</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/thunder"><img src="https://cdn.nba.com/logos/nba/1610612760/primary/L/logo.svg" alt="OKC"> Oklahoma City Thunder</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/suns"><img src="https://cdn.nba.com/logos/nba/1610612756/primary/L/logo.svg" alt="PHX"> Phoenix Suns</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/blazers"><img src="https://cdn.nba.com/logos/nba/1610612757/primary/L/logo.svg" alt="POR"> Portland Trail Blazers</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/kings"><img src="https://cdn.nba.com/logos/nba/1610612758/primary/L/logo.svg" alt="SAC"> Sacramento Kings</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/spurs"><img src="https://cdn.nba.com/logos/nba/1610612759/primary/L/logo.svg" alt="SAS"> San Antonio Spurs</a></li>
                            <li><a class="dropdown-item team-item" href="https://www.nba.com/jazz"><img src="https://cdn.nba.com/logos/nba/1610612762/primary/L/logo.svg" alt="UTA"> Utah Jazz</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">News</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="news.php">Latest</a></li>
                            <li><a class="dropdown-item" href="news.php">Breaking News</a></li>
                            <li><a class="dropdown-item" href="news.php">Teams</a></li>
                            <li><a class="dropdown-item" href="news.php">Players</a></li>
                            <li><a class="dropdown-item" href="news.php">Analysis</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex">
                    <button class="btn btn-outline-success me-2" type="button"><a href="login.php" class="button-sigin">Sign In</a></button>
                </form>
            </div>
        </div>
    </nav>