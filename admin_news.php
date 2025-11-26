<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include "connection.php";

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
    $category = $_POST['category'];
    $author = trim($_POST['author']) ?: 'HoopWave Team';

    // Slug otomatis
    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title) . '-' . time());

    // Upload gambar utama
    $image = $_POST['old_image'] ?? '';
    $thumbnail = $_POST['old_thumbnail'] ?? '';
    
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'news-' . time() . '.' . $ext;
        $target = "upload/news/" . $filename;
        $thumb_target = "upload/news/thumbnail/thumb-" . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // Buat thumbnail otomatis
            $src = imagecreatefromstring(file_get_contents($target));
            $width = imagesx($src);
            $height = imagesy($src);
            $new_width = 600;
            $new_height = 400;
            $thumb = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($thumb, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($thumb, $thumb_target, 85);
            imagedestroy($src); imagedestroy($thumb);

            $image = $target;
            $thumbnail = $thumb_target;

            // Hapus gambar lama kalau edit
            if ($id && $_POST['old_image']) {
                @unlink($_POST['old_image']);
                @unlink($_POST['old_thumbnail']);
            }
        }
    }

    if ($id > 0) {
        $sql = "UPDATE news SET title=?, slug=?, content=?, image=?, thumbnail=?, category=?, author=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $title, $slug, $content, $image, $thumbnail, $category, $author, $id);
    } else {
        $sql = "INSERT INTO news (title, slug, content, image, thumbnail, category, author) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $title, $slug, $content, $image, $thumbnail, $category, $author);
    }

    if ($stmt->execute()) {
        $alert = '<div class="alert alert-success">Berita berhasil disimpan!</div>';
    } else {
        $alert = '<div class="alert alert-danger">Gagal menyimpan berita.</div>';
    }
}

// Edit mode
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = $conn->query("SELECT * FROM news WHERE id = $id");
    $edit = $res->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - Admin HoopWave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        body { background:#f8f9fa; padding-top:120px; font-family:'Helvetica Neue',Arial,sans-serif; }
        .card { border:none; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.08); }
        .form-control, .form-select { border-radius:12px; }
        .btn-primary { background:#c8102e; border:none; border-radius:50px; padding:0.75rem 2rem; }
        .btn-primary:hover { background:#a00d24; }
        .preview-img { max-height:300px; object-fit:cover; border-radius:12px; }
        .table thead { background:#f1f1f1; }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container">
    <h2 class="text-center mb-4 fw-bold text-dark">Kelola Berita & Artikel</h2>
    <?= $alert ?>

    <!-- Form Tambah/Edit -->
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
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Judul Berita</label>
                        <input type="text" name="title" class="form-control form-control-lg" 
                               value="<?= $edit['title']??'' ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="category" class="form-select" required>
                            <option value="News" <?= ($edit['category']??'')=='News'?'selected':'' ?>>News</option>
                            <option value="Highlights" <?= ($edit['category']??'')=='Highlights'?'selected':'' ?>>Highlights</option>
                            <option value="Trade" <?= ($edit['category']??'')=='Trade'?'selected':'' ?>>Trade</option>
                            <option value="Injury" <?= ($edit['category']??'')=='Injury'?'selected':'' ?>>Injury</option>
                            <option value="Rumor" <?= ($edit['category']??'')=='Rumor'?'selected':'' ?>>Rumor</option>
                            <option value="Opinion" <?= ($edit['category']??'')=='Opinion'?'selected':'' ?>>Opinion</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Isi Berita</label>
                        <textarea name="content" id="editor"><?= $edit['content']??'' ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Gambar Utama (1920x1080 disarankan)</label>
                        <input type="file" name="image" class="form-control" accept="image/*" <?= $edit?'':'required' ?>>
                        <?php if ($edit && $edit['image']): ?>
                            <img src="../<?= $edit['image'] ?>" class="img-fluid mt-3 preview-img" alt="Current">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Thumbnail Otomatis (600x400)</label>
                        <?php if ($edit && $edit['thumbnail']): ?>
                            <img src="../<?= $edit['thumbnail'] ?>" class="img-fluid mt-3 preview-img" alt="Thumbnail">
                        <?php else: ?>
                            <p class="text-muted mt-3">Akan otomatis dibuat setelah upload gambar</p>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Penulis (opsional)</label>
                        <input type="text" name="author" class="form-control" value="<?= $edit['author']??'' ?>" placeholder="HoopWave Team">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="fas fa-save"></i> <?= $edit ? 'Update' : 'Publish' ?> Berita
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
                        <tr>
                            <th width="80">Gambar</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
                        while($n = $res->fetch_assoc()):
                        ?>
                        <tr>
                            <td><img src="../<?= $n['thumbnail'] ?>" width="70" class="rounded" alt=""></td>
                            <td class="fw-bold"><?= htmlspecialchars($n['title']) ?></td>
                            <td><span class="badge bg-primary"><?= $n['category'] ?></span></td>
                            <td><?= date('d M Y', strtotime($n['created_at'])) ?></td>
                            <td>
                                <a href="?edit=<?= $n['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="?delete=<?= $n['id'] ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Yakin hapus berita ini?')"><i class="fas fa-trash"></i></a>
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
<script>
tinymce.init({
    selector: '#editor',
    height: 500,
    plugins: 'lists link image table code fullscreen',
    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code fullscreen',
    branding: false
});
</script>
</body>
</html>