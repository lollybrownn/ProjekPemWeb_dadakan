<?php
include "connection.php";
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Buat folder upload jika belum ada
$upload_dir = "upload/news/";
$thumb_dir = "upload/news/thumbnail/";

if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}
if (!file_exists($thumb_dir)) {
    mkdir($thumb_dir, 0777, true);
}

$alert = '';

// Hapus berita
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $news = $conn->query("SELECT image, thumbnail FROM news WHERE id = $id")->fetch_assoc();
    if ($news) {
        @unlink($news['image']);
        @unlink($news['thumbnail']);
    }
    $conn->query("DELETE FROM news WHERE id = $id");
    $alert = '<div class="alert alert-success">Berita berhasil dihapus!</div>';
}

// Proses Tambah / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $title = trim($_POST['title']);
    $content = $_POST['content'];
    $categories = $_POST['categories'] ?? ['Latest'];
    $categories_json = json_encode($categories);
    $author = trim($_POST['author']) ?: 'HoopWave Team';

    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title) . '-' . time());

    $image = $_POST['old_image'] ?? '';
    $thumbnail = $_POST['old_thumbnail'] ?? '';
    
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'news-' . time() . '.' . $ext;
        $target = $upload_dir . $filename;
        $thumb_target = $thumb_dir . "thumb-" . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            
            // Cek apakah GD extension aktif
            if (extension_loaded('gd')) {
                try {
                    $src = imagecreatefromstring(file_get_contents($target));
                    if ($src !== false) {
                        $thumb = imagecreatetruecolor(600, 400);
                        imagecopyresampled($thumb, $src, 0, 0, 0, 0, 600, 400, imagesx($src), imagesy($src));
                        imagejpeg($thumb, $thumb_target, 85);
                        imagedestroy($src); 
                        imagedestroy($thumb);
                        
                        $thumbnail = $thumb_target;
                    } else {
                        // Jika gagal create image, pakai gambar asli
                        $thumbnail = $target;
                    }
                } catch (Exception $e) {
                    // Jika error, pakai gambar asli sebagai thumbnail
                    $thumbnail = $target;
                }
            } else {
                // Jika GD tidak aktif, pakai gambar asli sebagai thumbnail
                $thumbnail = $target;
                $alert = '<div class="alert alert-warning">GD extension tidak aktif. Thumbnail menggunakan gambar asli.</div>';
            }

            $image = $target;

            // Hapus gambar lama jika ada
            if ($id && isset($_POST['old_image']) && file_exists($_POST['old_image'])) {
                @unlink($_POST['old_image']);
            }
            if ($id && isset($_POST['old_thumbnail']) && file_exists($_POST['old_thumbnail'])) {
                @unlink($_POST['old_thumbnail']);
            }
        } else {
            $alert = '<div class="alert alert-danger">Gagal upload gambar!</div>';
        }
    }

    if ($id > 0) {
        $sql = "UPDATE news SET title=?, slug=?, content=?, image=?, thumbnail=?, categories=?, author=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $title, $slug, $content, $image, $thumbnail, $categories_json, $author, $id);
    } else {
        $sql = "INSERT INTO news (title, slug, content, image, thumbnail, categories, author) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $title, $slug, $content, $image, $thumbnail, $categories_json, $author);
    }

    if ($stmt->execute()) {
        $alert = '<div class="alert alert-success">Berita berhasil disimpan!</div>';
    } else {
        $alert = '<div class="alert alert-danger">Gagal menyimpan berita: ' . $conn->error . '</div>';
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = $conn->query("SELECT * FROM news WHERE id = $id");
    $edit = $res->fetch_assoc();
}

$all_cats = ['Latest', 'Breaking News', 'Teams', 'Players', 'Analysis'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - Admin HoopWave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background:#f8f9fa; padding-top:100px; font-family:'Helvetica Neue',Arial,sans-serif; }
        .card { border:none; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.08); }
        .form-control, .form-select { border-radius:12px; }
        .btn-primary { background:#c8102e; border:none; border-radius:50px; padding:0.75rem 2rem; }
        .btn-primary:hover { background:#a00d24; }
        .btn-back { position:fixed; top:15px; left:20px; z-index:9999; background:#c8102e; color:white; border-radius:50px; padding:0.65rem 1.6rem; font-size:0.95rem; box-shadow:0 5px 15px rgba(200,16,46,0.35); text-decoration:none; }
        .btn-back:hover { background:#a00d24; color:white; }
        .preview-img { max-height:300px; object-fit:cover; border-radius:12px; }
        textarea#content { min-height:400px; font-size:1rem; }
    </style>
</head>
<body>

<a href="dashboard_admin.php" class="btn btn-back">
    <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
</a>

<div class="container">
    <h2 class="text-center mb-4 fw-bold text-dark">Kelola Berita & Artikel</h2>
    <?= $alert ?>

    <div class="card mb-5">
        <div class="card-body p-5">
            <h4 class="mb-4 text-primary"><?= $edit ? 'Edit Berita' : 'Tambah Berita Baru' ?></h4>
            <form method="POST" enctype="multipart/form-data">
                <?php if ($edit): ?>
                    <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                    <input type="hidden" name="old_image" value="<?= $edit['image'] ?>">
                    <input type="hidden" name="old_thumbnail" value="<?= $edit['thumbnail'] ?>">
                <?php endif; ?>

                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Judul Berita</label>
                        <input type="text" name="title" class="form-control form-control-lg" value="<?= $edit['title']??'' ?>" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Kategori (bisa pilih lebih dari satu)</label>
                        <div class="row g-3">
                            <?php 
                            $selected = $edit ? json_decode($edit['categories'], true) : ['Latest'];
                            foreach($all_cats as $cat): ?>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="<?= $cat ?>" 
                                               id="cat_<?=strtolower(str_replace(' ','',$cat))?>" <?=in_array($cat,$selected)?'checked':''?>>
                                        <label class="form-check-label" for="cat_<?=strtolower(str_replace(' ','',$cat))?>"><?= $cat ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Isi Berita</label>
                        <textarea name="content" id="content" class="form-control" rows="15" required><?= $edit['content']??'' ?></textarea>
                        <small class="text-muted">Bisa pakai tag HTML sederhana: &lt;b&gt;, &lt;i&gt;, &lt;p&gt;, &lt;br&gt;, &lt;img src="..."&gt;, dll</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Gambar Utama (1920x1080 disarankan)</label>
                        <input type="file" name="image" class="form-control" accept="image/*" <?= $edit?'':'required' ?>>
                        <?php if ($edit && $edit['image']): ?>
                            <img src="<?= $edit['image'] ?>" class="img-fluid mt-3 preview-img" alt="">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Thumbnail Otomatis (600x400)</label>
                        <?php if ($edit && $edit['thumbnail']): ?>
                            <img src="<?= $edit['thumbnail'] ?>" class="img-fluid mt-3 preview-img" alt="">
                        <?php else: ?>
                            <p class="text-muted mt-3">Akan dibuat otomatis setelah upload</p>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Penulis (opsional)</label>
                        <input type="text" name="author" class="form-control" value="<?= $edit['author']??'' ?>" placeholder="HoopWave Team">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="fas fa-save me-2"></i> <?= $edit ? 'Update' : 'Publish' ?> Berita
                    </button>
                    <?php if($edit): ?>
                        <a href="admin_news.php" class="btn btn-secondary px-4">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Berita -->
    <div class="card">
        <div class="card-body p-5">
            <h4 class="mb-4 text-primary">Daftar Semua Berita</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr><th>Gambar</th><th>Judul</th><th>Kategori</th><th>Tanggal</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
                        while($n = $res->fetch_assoc()):
                            $cats = json_decode($n['categories'], true);
                        ?>
                        <tr>
                            <td><img src="<?= $n['thumbnail'] ?>" width="70" class="rounded" alt=""></td>
                            <td class="fw-bold"><?= htmlspecialchars($n['title']) ?></td>
                            <td><?php foreach($cats as $c): ?><span class="badge bg-primary me-1"><?= $c ?></span><?php endforeach; ?></td>
                            <td><?= date('d M Y', strtotime($n['created_at'])) ?></td>
                            <td>
                                <a href="?edit=<?= $n['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="?delete=<?= $n['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>