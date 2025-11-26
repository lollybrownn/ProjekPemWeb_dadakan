<?php
include "connection.php";
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil semua tim
$teams_result = $conn->query("SELECT * FROM teams ORDER BY full_name ASC");
$teams = $teams_result->fetch_all(MYSQLI_ASSOC);

// Pesan notifikasi
$alert = '';

// Hapus game
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM games WHERE id = $id");
    $alert = '<div class="alert alert-success">Game berhasil dihapus!</div>';
}

// Proses Tambah / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $game_date = $_POST['game_date'];
    $home_team_id = (int)$_POST['home_team'];
    $away_team_id = (int)$_POST['away_team'];
    $home_score = $_POST['home_score'] === '' ? null : (int)$_POST['home_score'];
    $away_score = $_POST['away_score'] === '' ? null : (int)$_POST['away_score'];
    $status = $_POST['status'];
    $youtube_url = trim($_POST['youtube_url']);
    $box_score_file = trim($_POST['box_score_file']);

    // Cek home & away tidak boleh sama
    if ($home_team_id === $away_team_id) {
        $alert = '<div class="alert alert-danger">Home Team dan Away Team tidak boleh sama!</div>';
    } else {
        // Ambil nama tim
        $home_q = $conn->query("SELECT full_name FROM teams WHERE id = $home_team_id")->fetch_assoc();
        $away_q = $conn->query("SELECT full_name FROM teams WHERE id = $away_team_id")->fetch_assoc();
        
        $home_team = $home_q['full_name'] ?? '';
        $away_team = $away_q['full_name'] ?? '';

        if ($id > 0) {
            $sql = "UPDATE games SET game_date=?, home_team=?, away_team=?, home_score=?, away_score=?, status=?, youtube_url=?, box_score_file=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssiisssi", $game_date, $home_team, $away_team, $home_score, $away_score, $status, $youtube_url, $box_score_file, $id);
        } else {
            $sql = "INSERT INTO games (game_date, home_team, away_team, home_score, away_score, status, youtube_url, box_score_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssiisss", $game_date, $home_team, $away_team, $home_score, $away_score, $status, $youtube_url, $box_score_file);
        }
        if ($stmt->execute()) {
            $alert = '<div class="alert alert-success">Game berhasil disimpan!</div>';
            if (!$id) { // reset form setelah tambah baru
                header("Location: admin_games.php?success=1");
                exit;
            }
        } else {
            $alert = '<div class="alert alert-danger">Terjadi kesalahan. Coba lagi.</div>';
        }
    }
}

// Edit mode
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = $conn->query("SELECT * FROM games WHERE id = $id");
    $edit = $res->fetch_assoc();
    if ($edit) {
        $home_id = $conn->query("SELECT id FROM teams WHERE full_name = '{$edit['home_team']}'")->fetch_assoc()['id'] ?? 0;
        $away_id = $conn->query("SELECT id FROM teams WHERE full_name = '{$edit['away_team']}'")->fetch_assoc()['id'] ?? 0;
        $edit['home_team_id'] = $home_id;
        $edit['away_team_id'] = $away_id;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Games - Admin HoopWave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background:#f8f9fa; padding-top:120px; font-family:'Helvetica Neue',Arial,sans-serif; }
        .navbar-main { background-image:url(asset/background-navbar.png); background-size:cover; }
        .card { border:none; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.08); }
        .form-control, .form-select { border-radius:12px; padding:0.75rem 1rem; }
        .btn-primary { background:#c8102e; border:none; border-radius:50px; padding:0.75rem 2rem; font-weight:600; }
        .btn-primary:hover { background:#a00d24; }
        .team-option { display:flex; align-items:center; gap:12px; padding:8px 0; }
        .team-option img { width:32px; height:32px; object-fit:contain; }
        .table thead { background:#f1f1f1; }
        .badge-final { background:#28a745; }
        .badge-live { background:#ffc107; color:#000; }
        .badge-upcoming { background:#6c757d; }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <h2 class="text-center mb-4 fw-bold text-dark">Kelola Highlight Games</h2>

            <?= $alert ?>

            <!-- Form Tambah / Edit -->
            <div class="card mb-5">
                <div class="card-body p-5">
                    <h4 class="mb-4 text-primary"><?= $edit ? 'Edit Game' : 'Tambah Game Baru' ?></h4>
                    <form method="POST">
                        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>

                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tanggal Pertandingan</label>
                                <input type="date" name="game_date" class="form-control" 
                                       value="<?= $edit['game_date'] ?? date('Y-m-d') ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Home Team</label>
                                <select name="home_team" class="form-select" required>
                                    <option value="">— Pilih Home Team —</option>
                                    <?php foreach($teams as $t): ?>
                                        <option value="<?= $t['id'] ?>" <?= ($edit['home_team_id']??0)==$t['id']?'selected':'' ?> 
                                                data-logo="https://cdn.nba.com/logos/nba/<?= $t['logo_code'] ?>/primary/L/logo.svg">
                                            <?= $t['full_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Away Team</label>
                                <select name="away_team" class="form-select" required>
                                    <option value="">— Pilih Away Team —</option>
                                    <?php foreach($teams as $t): ?>
                                        <option value="<?= $t['id'] ?>" <?= ($edit['away_team_id']??0)==$t['id']?'selected':'' ?>
                                                data-logo="https://cdn.nba.com/logos/nba/<?= $t['logo_code'] ?>/primary/L/logo.svg">
                                            <?= $t['full_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Skor Home</label>
                                <input type="number" name="home_score" class="form-control" value="<?= $edit['home_score']??'' ?>" placeholder="-">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Skor Away</label>
                                <input type="number" name="away_score" class="form-control" value="<?= $edit['away_score']??'' ?>" placeholder="-">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="upcoming" <?= ($edit['status']??'')=='upcoming'?'selected':'' ?>>Upcoming</option>
                                    <option value="live" <?= ($edit['status']??'')=='live'?'selected':'' ?>>Live</option>
                                    <option value="final" <?= ($edit['status']??'final')=='final'?'selected':'' ?>>Final</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">YouTube Embed URL</label>
                                <input type="text" name="youtube_url" class="form-control" 
                                       value="<?= $edit['youtube_url']??'' ?>" placeholder="https://www.youtube.com/embed/xxx">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Box Score File (opsional)</label>
                                <input type="text" name="box_score_file" class="form-control" 
                                       value="<?= $edit['box_score_file']??'' ?>" placeholder="box_score5.php">
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save"></i> <?= $edit ? 'Update' : 'Simpan' ?> Game
                            </button>
                            <?php if($edit): ?>
                                <a href="admin_games.php" class="btn btn-secondary px-4">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Daftar Games -->
            <div class="card">
                <div class="card-body p-5">
                    <h4 class="mb-4 text-primary">Daftar Semua Games</h4>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pertandingan</th>
                                    <th>Skor</th>
                                    <th>Status</th>
                                    <th>Video</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = $conn->query("SELECT * FROM games ORDER BY game_date DESC, id DESC");
                                while($g = $res->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?= date('d M Y', strtotime($g['game_date'])) ?></td>
                                    <td><strong><?= $g['away_team'] ?></strong> @ <strong><?= $g['home_team'] ?></strong></td>
                                    <td class="text-center fw-bold">
                                        <?= $g['away_score']??'-' ?> - <?= $g['home_score']??'-' ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $g['status']=='final'?'badge-final':($g['status']=='live'?'badge-live':'badge-upcoming') ?>">
                                            <?= strtoupper($g['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= $g['youtube_url'] ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' ?></td>
                                    <td>
                                        <a href="?edit=<?= $g['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="?delete=<?= $g['id'] ?>" class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Yakin hapus game ini?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Tampilkan logo di dropdown (opsional, lebih keren)
document.querySelectorAll('select[name="home_team"], select[name="away_team"]').forEach(select => {
    new Choices(select, {
        searchEnabled: true,
        itemSelectText: '',
        shouldSort: false,
        callbackOnCreateTemplates: function(template) {
            return {
                item: ({ classNames }, data) => {
                    const logo = data.element.dataset.logo || 'asset/default-logo.png';
                    return template(`
                        <div class="${classNames.item}" data-item data-id="${data.id}" data-value="${data.value}">
                            <img src="${logo}" width="28" class="me-2" onerror="this.src='asset/default-logo.png'">
                            ${data.label}
                        </div>
                    `);
                },
                choice: ({ classNames }, data) => {
                    const logo = data.element.dataset.logo || 'asset/default-logo.png';
                    return template(`
                        <div class="${classNames.item} ${classNames.itemChoice}" data-choice data-id="${data.id}" data-value="${data.value}">
                            <img src="${logo}" width="28" class="me-2" onerror="this.src='asset/default-logo.png'">
                            ${data.label}
                        </div>
                    `);
                }
            };
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
</body>
</html>