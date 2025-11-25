<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HoopWave – NBA Live</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            margin: 0; 
            background: #000; 
            font-family: 'Helvetica Neue', Arial, sans-serif; 
            color: #fff; 
        }

        /* Dropdown Teams */
        .teams-dropdown { width:360px !important; max-height:80vh; overflow-y:auto; padding:0.5rem 0; box-shadow:0 10px 30px rgba(0,0,0,0.2); }
        .team-item { display:flex !important; align-items:center; gap:12px; padding:0.45rem 1rem; }
        .team-item img { width:26px; height:26px; }
        .team-item:hover { background:#f8f9fa; color:#0d6efd !important; border-radius:6px; }
        @media (min-width:992px) { .dropdown:hover > .dropdown-menu { display:block; } }

        /* VIDEO TIDAK MASUK KE BELAKANG NAVBAR */
        .main-content {
            margin-top: 125px;     /* sesuaikan kalau navbar kamu lebih tinggi/kurang */
            padding-bottom: 3rem;
        }

        .hero-video {
            max-width: 850px;
            margin: 0 auto;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.7);
        }

        /* Tombol Today’s Games */
        .btn-wrapper {
            text-align: center;
            margin: 2rem 0 4rem;
        }
        .btn-today {
            background: #c8102e;
            color: #fff;
            font-weight: 700;
            font-size: 1.25rem;
            padding: 13px 55px;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(200,16,46,0.5);
            transition: all 0.3s ease;
            display: inline-block;
        }
        .btn-today:hover {
            background: #e61e3d;
            transform: translateY(-5px);
            box-shadow: 0 18px 40px rgba(200,16,46,0.6);
        }
    </style>
</head>
<body>

    <?php include "navbar.php"; ?>

    <!-- KONTEN UTAMA DENGAN MARGIN TOP AGAR TIDAK TERTUTUP NAVBAR -->
    <div class="main-content">
        <div class="container">

            <!-- VIDEO UTAMA -->
            <div class="hero-video ratio ratio-16x9">
                <iframe 
                    src="https://www.youtube.com/embed/lSK-01qq9rM?autoplay=1&mute=1&loop=1&playlist=lSK-01qq9rM&controls=1&rel=0&modestbranding=1&playsinline=1"
                    title="NBA Featured Highlight"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>

            <!-- TOMBOL DI BAWAH VIDEO -->
            <div class="btn-wrapper">
                <a href="home_games.php" class="btn-today text-decoration-none">
                    Today's Games
                </a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>