<?php
include "connection.php";
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil artikel dari database
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();

// Jika artikel tidak ditemukan, redirect ke news.php
if (!$article) {
    header("Location: news.php");
    exit;
}

// Decode categories
$categories = json_decode($article['categories'], true);

// Ambil artikel terkait (dari kategori yang sama, kecuali artikel ini)
$relatedQuery = "SELECT * FROM news WHERE id != ? AND JSON_CONTAINS(categories, ?) ORDER BY created_at DESC LIMIT 3";
$stmtRelated = $conn->prepare($relatedQuery);
$categoryJson = json_encode($categories[0]);
$stmtRelated->bind_param("is", $id, $categoryJson);
$stmtRelated->execute();
$relatedArticles = $stmtRelated->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?> - HoopWave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background:#f9f9f9; 
            padding-top:170px; 
            font-family:'Helvetica Neue',Arial,sans-serif; 
            color:#333; 
        }
        .navbar-main { 
            z-index:1050 !important; 
            background-image:url(asset/background-navbar1.png); 
            background-size:cover; 
            background-position:center; 
            background-repeat:no-repeat; 
        }
        #subNavbar { 
            z-index:1040 !important; 
            background:#fff !important; 
            border-bottom:1px solid #e0e0e0; 
            position:fixed; 
            left:0; 
            right:0; 
        }
        .sub-nav-item { 
            color:#666; 
            font-weight:500; 
            padding:0.75rem 1rem; 
            border-bottom:2px solid transparent; 
            text-decoration:none; 
            transition:all .3s; 
        }
        .sub-nav-item:hover, .sub-nav-item.active { 
            color:#000; 
            border-bottom-color:#c8102e; 
            background:#f8f9fa; 
        }
        .article-hero-img { 
            width:100%; 
            height:500px; 
            object-fit:cover; 
            border-radius:16px; 
        }
        .article-content { 
            font-size:1.15rem; 
            line-height:1.8; 
        }
        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 20px 0;
        }
        .article-content p {
            margin-bottom: 1.5rem;
        }
        .teams-dropdown {
            width: 360px !important;
            max-height: 80vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.5rem 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
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
        @media (min-width: 992px) {
            .dropdown:hover>.dropdown-menu {
                display: block;
            }
        }
        @media (max-width:768px) { 
            .article-hero-img { height:300px; } 
        }
        .related-article {
            transition: transform 0.3s;
        }
        .related-article:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<!-- SUB NAVBAR -->
<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom shadow-sm" id="subNavbar">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4 text-dark mb-0">News</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#subnavNews">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="subnavNews">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link sub-nav-item" href="news.php?tab=latest">Latest</a></li>
                <li class="nav-item"><a class="nav-link sub-nav-item" href="news.php?tab=breaking">Breaking News</a></li>
                <li class="nav-item"><a class="nav-link sub-nav-item" href="news.php?tab=teams">Teams</a></li>
                <li class="nav-item"><a class="nav-link sub-nav-item" href="news.php?tab=players">Players</a></li>
                <li class="nav-item"><a class="nav-link sub-nav-item" href="news.php?tab=analysis">Analysis</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <article class="bg-white rounded-4 shadow-lg overflow-hidden">
        <img src="<?= $article['image'] ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="article-hero-img">

        <div class="p-4 p-md-5">
            <!-- Categories Badges -->
            <div class="mb-3">
                <?php foreach($categories as $cat): ?>
                    <span class="badge bg-<?= $cat === 'Breaking News' ? 'danger' : 'primary' ?> me-2"><?= $cat ?></span>
                <?php endforeach; ?>
            </div>

            <h1 class="display-4 fw-bold mb-4"><?= htmlspecialchars($article['title']) ?></h1>
            
            <div class="d-flex align-items-center text-muted mb-4 fs-5">
                <strong><?= htmlspecialchars($article['author']) ?></strong>
                <span class="mx-2">•</span>
                <span><?= date('F j, Y • g:i A', strtotime($article['created_at'])) ?></span>
            </div>
            
            <hr class="my-5">
            
            <div class="article-content">
                <?= $article['content'] ?>
            </div>

            <!-- Related Articles -->
            <?php if($relatedArticles->num_rows > 0): ?>
            <hr class="my-5">
            <h3 class="fw-bold mb-4">Related Articles</h3>
            <div class="row g-4">
                <?php while($related = $relatedArticles->fetch_assoc()): ?>
                <div class="col-md-4">
                    <a href="article.php?id=<?= $related['id'] ?>" class="text-decoration-none text-dark">
                        <div class="related-article bg-light rounded-3 overflow-hidden shadow-sm">
                            <img src="<?= $related['thumbnail'] ?>" class="w-100" style="height:180px;object-fit:cover;" alt="">
                            <div class="p-3">
                                <h5 class="fw-bold"><?= htmlspecialchars($related['title']) ?></h5>
                                <small class="text-muted"><?= date('M j, Y', strtotime($related['created_at'])) ?></small>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>

            <div class="mt-5">
                <a href="news.php" class="btn btn-outline-dark btn-lg px-5">
                    <i class="fas fa-arrow-left me-2"></i> Back to News
                </a>
            </div>
        </div>
    </article>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function adjustSubNavbar() {
        const mainNav = document.querySelector('.navbar-main');
        const subNav = document.getElementById('subNavbar');
        if (mainNav && subNav) {
            subNav.style.top = mainNav.offsetHeight + 'px';
            document.body.style.paddingTop = (mainNav.offsetHeight + subNav.offsetHeight + 30) + 'px';
        }
    }
    window.addEventListener('load', adjustSubNavbar);
    window.addEventListener('resize', adjustSubNavbar);
</script>
</body>
</html>