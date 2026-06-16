<?php
require_once __DIR__ . '/auth.php';
require_admin_login();

$upload_dir = __DIR__ . '/../uploads/blog/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

function make_slug($title) {
    $slug = mb_strtolower(trim($title), 'UTF-8');
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/\s+/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

function unique_slug($title, $exclude_id = 0) {
    $base = make_slug($title);
    $slug = $base ?: 'post';
    $i    = 1;
    while (db_fetch('SELECT id FROM blogs WHERE slug = ? AND id != ?', [$slug, $exclude_id])) {
        $slug = $base . '-' . $i++;
    }
    return $slug;
}

function handle_blog_image($upload_dir) {
    if (empty($_FILES['image_file']['name']) || $_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $ext     = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    if (!in_array($ext, $allowed)) {
        return null;
    }
    if ($_FILES['image_file']['size'] > 5 * 1024 * 1024) {
        return null;
    }
    $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (move_uploaded_file($_FILES['image_file']['tmp_name'], $upload_dir . $filename)) {
        return 'uploads/blog/' . $filename;
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $id       = $action === 'edit' ? (int)($_POST['id'] ?? 0) : 0;
        $title    = trim($_POST['title'] ?? '');
        $slug     = trim($_POST['slug'] ?? '') ?: unique_slug($title, $id);
        $author   = trim($_POST['author'] ?? '') ?: 'Ficus International Team';
        $category = trim($_POST['category'] ?? '');
        $status   = in_array($_POST['status'] ?? '', ['published', 'draft']) ? $_POST['status'] : 'published';
        $excerpt  = trim($_POST['excerpt'] ?? '');
        $content  = trim($_POST['content'] ?? '');

        $image_source   = $_POST['image_source'] ?? 'url';
        $existing_image = trim($_POST['existing_image'] ?? '');
        $image          = $existing_image ?: null;

        if ($image_source === 'upload') {
            $uploaded = handle_blog_image($upload_dir);
            if ($uploaded) {
                $image = $uploaded;
            } elseif (!empty($_FILES['image_file']['name'])) {
                $err = $_FILES['image_file']['error'] ?? -1;
                if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
                    flash_set('Upload failed: file too large. Max allowed is 5 MB (check php.ini upload_max_filesize).', 'error');
                } elseif ($err !== UPLOAD_ERR_NO_FILE && $err !== UPLOAD_ERR_OK) {
                    flash_set("Image upload failed (PHP error $err). Try again.", 'error');
                } elseif ($err === UPLOAD_ERR_OK) {
                    flash_set('Image upload failed: unsupported format or file too large. Use JPG, PNG, WebP or GIF under 5 MB.', 'error');
                }
                // keep $image = $existing_image
            }
            // no file chosen → keep $image = $existing_image
        } else {
            $url_input = trim($_POST['image_url'] ?? '');
            $image     = $url_input ?: ($existing_image ?: null);
        }

        if ($title && $excerpt && $content) {
            if ($action === 'add') {
                if (db_fetch('SELECT id FROM blogs WHERE slug = ?', [$slug])) {
                    $slug = $slug . '-' . time();
                }
                db_query(
                    'INSERT INTO blogs (title, slug, author, category, excerpt, content, image_url, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
                    [$title, $slug, $author, $category, $excerpt, $content, $image, $status]
                );
                flash_set('Blog post published!');
            } else {
                if ($id) {
                    db_query(
                        'UPDATE blogs SET title=?, slug=?, author=?, category=?, excerpt=?, content=?, image_url=?, status=? WHERE id=?',
                        [$title, $slug, $author, $category, $excerpt, $content, $image, $status, $id]
                    );
                    flash_set('Blog post updated successfully!');
                }
            }
        } else {
            flash_set('Please fill in all required fields (Title, Excerpt, Content).', 'error');
        }
        header('Location: blogs.php');
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $blog = db_fetch('SELECT image_url FROM blogs WHERE id=?', [$id]);
            if ($blog && $blog['image_url'] && strpos($blog['image_url'], 'uploads/blog/') === 0) {
                $path = __DIR__ . '/../' . $blog['image_url'];
                if (file_exists($path)) unlink($path);
            }
            db_query('DELETE FROM blogs WHERE id=?', [$id]);
            flash_set('Blog post deleted.');
        }
        header('Location: blogs.php');
        exit;
    }
}

$edit_blog = null;
if (isset($_GET['edit'])) {
    $edit_blog = db_fetch('SELECT * FROM blogs WHERE id=?', [(int)$_GET['edit']]);
}

$view_blog = null;
if (isset($_GET['view'])) {
    $view_blog = db_fetch('SELECT * FROM blogs WHERE id=?', [(int)$_GET['view']]);
}

$search       = trim($_GET['search'] ?? '');
$flash        = flash_get();
$unread_count = db_fetch('SELECT COUNT(*) AS c FROM contact_queries WHERE status = ?', ['new'])['c'] ?? 0;
$admin_name   = admin_username();

$blogs = $search
    ? db_fetch_all('SELECT * FROM blogs WHERE title LIKE ? OR author LIKE ? OR category LIKE ? OR excerpt LIKE ? ORDER BY created_at DESC', ["%$search%", "%$search%", "%$search%", "%$search%"])
    : db_fetch_all('SELECT * FROM blogs ORDER BY created_at DESC');

$blog_categories = ['Agro Commodities', 'Trade & Export', 'Sustainability', 'Industry News', 'Company Updates', 'Other'];

$current_image    = $edit_blog['image_url'] ?? '';
$image_is_upload  = $current_image && strpos($current_image, 'http') !== 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Blog Posts | Ficus International Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="admin.css" />
    <style>
        .img-source-tabs { display:flex; gap:0; margin-bottom:10px; border:1px solid var(--border); border-radius:8px; overflow:hidden; width:fit-content; }
        .img-source-tabs label { padding:7px 18px; font-size:13px; cursor:pointer; font-weight:500; color:var(--text-mid); background:var(--light-bg); transition:all .15s; }
        .img-source-tabs input[type=radio] { display:none; }
        .img-source-tabs input[type=radio]:checked + label { background:var(--primary); color:#fff; }
        .img-source-tabs label:hover { background:var(--border); }
        .slug-wrap { display:flex; align-items:center; border:1px solid var(--border); border-radius:8px; overflow:hidden; background:var(--light-bg); }
        .slug-prefix { padding:0 10px; font-size:12px; color:var(--text-mid); background:var(--border); height:40px; display:flex; align-items:center; white-space:nowrap; font-family:monospace; }
        .slug-wrap input { border:0; background:transparent; flex:1; height:40px; padding:0 10px; font-family:monospace; font-size:13px; color:var(--text-dark); }
        .slug-wrap input:focus { outline:none; }
        .ck-editor__editable { min-height: 340px !important; }
        .upload-preview-current { margin-top:8px; display:flex; align-items:center; gap:10px; font-size:12px; color:var(--text-mid); }
        .upload-preview-current img { width:60px; height:44px; object-fit:cover; border-radius:6px; border:1px solid var(--border); }
    </style>
</head>
<body>
<div class="admin-layout">
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="fas fa-leaf"></i></div>
            <div class="brand-text"><h2>Ficus Admin</h2><span>Control Panel</span></div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-label">Main</div>
            <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="queries.php"><i class="fas fa-envelope-open-text"></i> Contact Queries
                <?php if ($unread_count > 0): ?>
                <span style="margin-left:auto;background:var(--danger);color:#fff;font-size:10px;padding:1px 7px;border-radius:10px"><?= $unread_count ?></span>
                <?php endif; ?>
            </a>
            <div class="nav-section-label" style="margin-top:12px">Content</div>
            <a href="products.php"><i class="fas fa-seedling"></i> Products</a>
            <a href="blogs.php" class="active"><i class="fas fa-blog"></i> Blog Posts</a>
            <div class="nav-section-label" style="margin-top:12px">Site</div>
            <a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left" style="display:flex;align-items:center;gap:0;">
                <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h1>Blog Posts</h1>
                <span>Manage your blog content</span>
            </div>
            <div class="topbar-right">
                <div class="topbar-badge"><i class="fas fa-bell"></i><?php if ($unread_count > 0): ?><span class="badge-dot"></span><?php endif; ?></div>
                <div class="topbar-user">
                    <div class="avatar"><?= strtoupper(substr($admin_name, 0, 1)) ?></div>
                    <div class="user-info"><strong><?= html_escape($admin_name) ?></strong><span>Administrator</span></div>
                </div>
            </div>
        </div>

        <div class="page-content">

            <!-- Add / Edit Blog Form -->
            <div class="panel">
                <div class="panel-header">
                    <h2>
                        <?php if ($edit_blog): ?>
                        <i class="fas fa-edit"></i> Edit Blog Post
                        <?php else: ?>
                        <i class="fas fa-pen-nib"></i> Write New Blog Post
                        <?php endif; ?>
                    </h2>
                    <?php if ($edit_blog): ?>
                    <a href="blogs.php" class="btn btn-outline btn-sm"><i class="fas fa-times"></i> Cancel Edit</a>
                    <?php endif; ?>
                </div>
                <div class="panel-body">
                    <form class="admin-form" method="post" action="blogs.php" enctype="multipart/form-data" id="blogForm">
                        <input type="hidden" name="action" value="<?= $edit_blog ? 'edit' : 'add' ?>" />
                        <?php if ($edit_blog): ?>
                        <input type="hidden" name="id" value="<?= $edit_blog['id'] ?>" />
                        <?php endif; ?>
                        <input type="hidden" name="existing_image" value="<?= html_escape($current_image) ?>" />

                        <!-- Title -->
                        <div class="form-row">
                            <div>
                                <label>Blog Title *</label>
                                <input type="text" id="blogTitle" name="title"
                                    placeholder="Enter a descriptive blog title..." required
                                    value="<?= html_escape($edit_blog['title'] ?? '') ?>"
                                    oninput="autoSlug(this.value)" />
                            </div>
                        </div>

                        <!-- Slug -->
                        <div class="form-row" style="margin-top:-8px">
                            <div>
                                <label>
                                    URL Slug
                                    <span style="font-size:11px;color:var(--text-mid);font-weight:400"> — auto-generated from title, read-only</span>
                                </label>
                                <div class="slug-wrap">
                                    <span class="slug-prefix"><i class="fas fa-link" style="margin-right:5px;opacity:.5"></i>/blog/</span>
                                    <input type="text" id="blogSlug" name="slug" readonly
                                        value="<?= html_escape($edit_blog['slug'] ?? make_slug($edit_blog['title'] ?? '')) ?>"
                                        placeholder="auto-generated-from-title" />
                                </div>
                            </div>
                        </div>

                        <!-- Author / Category / Status -->
                        <div class="form-row cols-3">
                            <div>
                                <label>Author</label>
                                <input type="text" name="author" placeholder="e.g. Ficus International Team"
                                    value="<?= html_escape($edit_blog['author'] ?? 'Ficus International Team') ?>" />
                            </div>
                            <div>
                                <label>Category</label>
                                <select name="category">
                                    <?php foreach ($blog_categories as $cat): ?>
                                    <option value="<?= html_escape($cat) ?>"
                                        <?= (($edit_blog['category'] ?? 'Agro Commodities') === $cat) ? 'selected' : '' ?>>
                                        <?= html_escape($cat) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label>Status</label>
                                <select name="status">
                                    <option value="published" <?= (($edit_blog['status'] ?? 'published') === 'published') ? 'selected' : '' ?>>Published</option>
                                    <option value="draft" <?= (($edit_blog['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Draft</option>
                                </select>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="form-row cols-2">
                            <div>
                                <label>Featured Image</label>

                                <!-- Source toggle -->
                                <div class="img-source-tabs">
                                    <input type="radio" name="image_source" id="imgSrcUrl" value="url"
                                        <?= $image_is_upload ? '' : 'checked' ?>
                                        onchange="toggleImgSource('url')">
                                    <label for="imgSrcUrl"><i class="fas fa-link"></i> Paste URL</label>

                                    <input type="radio" name="image_source" id="imgSrcUpload" value="upload"
                                        <?= $image_is_upload ? 'checked' : '' ?>
                                        onchange="toggleImgSource('upload')">
                                    <label for="imgSrcUpload"><i class="fas fa-upload"></i> Upload File</label>
                                </div>

                                <!-- URL input -->
                                <div id="img-url-section" style="<?= $image_is_upload ? 'display:none' : '' ?>">
                                    <input type="url" name="image_url" id="blogImageUrl"
                                        placeholder="https://images.unsplash.com/photo-..."
                                        value="<?= (!$image_is_upload) ? html_escape($current_image) : '' ?>"
                                        oninput="previewFromUrl(this.value)" />
                                    <p class="field-hint"><i class="fas fa-info-circle"></i> Paste a direct image URL</p>
                                </div>

                                <!-- File upload input -->
                                <div id="img-upload-section" style="<?= $image_is_upload ? '' : 'display:none' ?>">
                                    <input type="file" name="image_file" id="blogImageFile"
                                        accept="image/jpeg,image/png,image/webp,image/gif"
                                        onchange="previewFromFile(this)" />
                                    <p class="field-hint"><i class="fas fa-info-circle"></i> JPG, PNG, WebP, GIF — max 5 MB. Leave blank to keep existing image.</p>
                                    <?php if ($image_is_upload && $current_image): ?>
                                    <div class="upload-preview-current">
                                        <img src="../<?= html_escape($current_image) ?>" alt="Current">
                                        <span>Current image — upload a new file to replace it</span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Preview -->
                            <div>
                                <label>Preview</label>
                                <div style="border:2px dashed var(--border);border-radius:8px;height:120px;display:flex;align-items:center;justify-content:center;overflow:hidden;background:var(--light-bg)">
                                    <img id="blogImgPreview"
                                        src="<?= $current_image ? (strpos($current_image, 'http') === 0 ? html_escape($current_image) : '../' . html_escape($current_image)) : '' ?>"
                                        alt=""
                                        style="max-height:116px;max-width:100%;border-radius:6px;<?= $current_image ? '' : 'display:none' ?>" />
                                    <span id="blogImgPlaceholder" style="color:var(--text-mid);font-size:12px;text-align:center;<?= $current_image ? 'display:none' : '' ?>">
                                        <i class="fas fa-image" style="display:block;font-size:26px;margin-bottom:6px;opacity:.2"></i>Preview
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Excerpt -->
                        <div class="form-row">
                            <div>
                                <label>Short Excerpt *</label>
                                <textarea name="excerpt" rows="2"
                                    placeholder="A brief summary shown in blog listing (1–2 sentences)" required><?= html_escape($edit_blog['excerpt'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- Full Content — CKEditor -->
                        <div class="form-row">
                            <div>
                                <label>Full Content * <span style="font-size:11px;color:var(--text-mid);font-weight:400"> — use the toolbar for headings, bold, tables, lists, etc.</span></label>
                                <textarea id="content" name="content" rows="12"><?= html_escape($edit_blog['content'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?= $edit_blog ? 'Update Post' : 'Publish Post' ?>
                            </button>
                            <a href="blogs.php" class="btn btn-outline">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Blogs List -->
            <div class="panel">
                <div class="panel-header">
                    <h2><i class="fas fa-list"></i> All Blog Posts <span class="badge badge-read" style="margin-left:8px"><?= count($blogs) ?></span></h2>
                    <form method="get" action="blogs.php">
                        <div class="search-bar">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Search posts..." value="<?= html_escape($search) ?>" />
                        </div>
                    </form>
                </div>
                <div style="padding:0">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($blogs)): ?>
                            <tr>
                                <td colspan="7" style="text-align:center;padding:50px 20px">
                                    <i class="fas fa-blog" style="font-size:40px;opacity:.15;display:block;margin-bottom:12px;color:var(--primary)"></i>
                                    <p style="color:var(--text-mid);font-size:14px"><?= $search ? 'No posts match your search.' : 'No blog posts yet.' ?></p>
                                </td>
                            </tr>
                            <?php else: foreach ($blogs as $b): ?>
                            <?php $thumb = $b['image_url'] ? (strpos($b['image_url'], 'http') === 0 ? $b['image_url'] : '../' . $b['image_url']) : ''; ?>
                            <tr>
                                <td><?php if ($thumb): ?><img class="blog-row-thumb" src="<?= html_escape($thumb) ?>" alt="" loading="lazy"><?php else: ?><i class="fas fa-image" style="color:var(--text-mid);opacity:.3;font-size:22px"></i><?php endif; ?></td>
                                <td style="max-width:220px">
                                    <strong><?= html_escape($b['title']) ?></strong>
                                    <?php if (!empty($b['slug'])): ?>
                                    <div style="font-size:11px;color:var(--text-mid);font-family:monospace;margin-top:2px">/blog/<?= html_escape($b['slug']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= html_escape($b['author']) ?></td>
                                <td><span class="badge badge-read"><?= html_escape($b['category']) ?></span></td>
                                <td><?= date('d M Y', strtotime($b['created_at'])) ?></td>
                                <td><span class="badge badge-<?= html_escape($b['status']) ?>"><?= ucfirst(html_escape($b['status'])) ?></span></td>
                                <td>
                                    <div style="display:flex;gap:6px">
                                        <a href="blogs.php?view=<?= $b['id'] ?>" class="btn btn-sm btn-secondary" title="Preview"><i class="fas fa-eye"></i></a>
                                        <a href="blogs.php?edit=<?= $b['id'] ?>" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form method="post" action="blogs.php" style="display:inline" onsubmit="return confirm('Delete this blog post?')">
                                            <input type="hidden" name="action" value="delete" />
                                            <input type="hidden" name="id" value="<?= $b['id'] ?>" />
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Blog Preview Modal -->
<?php if ($view_blog): ?>
<?php $vthumb = $view_blog['image_url'] ? (strpos($view_blog['image_url'], 'http') === 0 ? $view_blog['image_url'] : '../' . $view_blog['image_url']) : ''; ?>
<div class="modal-overlay show" id="blogViewModal">
    <div class="modal-box" style="max-width:720px">
        <div class="modal-header">
            <h3><i class="fas fa-eye" style="color:var(--secondary);margin-right:8px"></i> Blog Preview</h3>
            <a href="blogs.php<?= $search ? '?search='.urlencode($search) : '' ?>" class="modal-close"><i class="fas fa-times"></i></a>
        </div>
        <div class="modal-body">
            <?php if ($vthumb): ?>
            <img src="<?= html_escape($vthumb) ?>" alt=""
                style="width:100%;height:220px;object-fit:cover;border-radius:8px;margin-bottom:18px">
            <?php endif; ?>
            <div style="display:flex;gap:10px;align-items:center;margin-bottom:12px;flex-wrap:wrap">
                <span class="badge badge-read"><?= html_escape($view_blog['category']) ?></span>
                <span class="badge badge-<?= html_escape($view_blog['status']) ?>"><?= ucfirst(html_escape($view_blog['status'])) ?></span>
                <span style="font-size:12px;color:var(--text-mid)"><i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($view_blog['created_at'])) ?></span>
                <span style="font-size:12px;color:var(--text-mid)"><i class="fas fa-user"></i> <?= html_escape($view_blog['author']) ?></span>
            </div>
            <h2 style="font-size:20px;color:var(--primary);margin-bottom:12px;line-height:1.35"><?= html_escape($view_blog['title']) ?></h2>
            <?php if (!empty($view_blog['slug'])): ?>
            <p style="font-size:11px;color:var(--text-mid);font-family:monospace;margin-bottom:10px">/blog/<?= html_escape($view_blog['slug']) ?></p>
            <?php endif; ?>
            <p style="font-size:13px;color:var(--text-mid);font-style:italic;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--border)"><?= html_escape($view_blog['excerpt']) ?></p>
            <div style="font-size:14px;line-height:1.8;color:var(--text-dark)"><?= $view_blog['content'] ?></div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="toast-container" id="toastContainer"></div>

<?php if ($flash): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    showToast(<?= json_encode($flash['message']) ?>, <?= json_encode($flash['type']) ?>);
});
</script>
<?php endif; ?>

<!-- CKEditor 4 Full (tables, headings, all formatting) -->
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script src="admin.js"></script>
<script>
/* ---- CKEditor init ---- */
CKEDITOR.replace('content', {
    height: 380,
    toolbar: [
        { name: 'document',    items: ['Source', '-', 'Undo', 'Redo'] },
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
        { name: 'paragraph',   items: ['NumberedList', 'BulletedList', '-', 'Blockquote'] },
        { name: 'links',       items: ['Link', 'Unlink'] },
        { name: 'insert',      items: ['Image', 'Table', 'HorizontalRule'] },
        '/',
        { name: 'styles',      items: ['Format', 'FontSize'] },
        { name: 'colors',      items: ['TextColor', 'BGColor'] },
        { name: 'align',       items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] }
    ],
    format_tags: 'p;h1;h2;h3;h4;pre',
    removePlugins: 'elementspath',
    resize_enabled: true
});

/* Make sure CKEditor flushes content to textarea on submit */
document.getElementById('blogForm').addEventListener('submit', function() {
    if (CKEDITOR.instances['content']) {
        document.getElementById('content').value = CKEDITOR.instances['content'].getData();
    }
});

/* ---- Slug auto-generation ---- */
function autoSlug(val) {
    var slug = val
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
    document.getElementById('blogSlug').value = slug;
}

/* ---- Image source toggle ---- */
function toggleImgSource(mode) {
    var urlSection    = document.getElementById('img-url-section');
    var uploadSection = document.getElementById('img-upload-section');
    if (mode === 'url') {
        urlSection.style.display    = '';
        uploadSection.style.display = 'none';
        /* Clear file input so existing image is not overwritten unintentionally */
        document.getElementById('blogImageFile').value = '';
        previewFromUrl(document.getElementById('blogImageUrl').value);
    } else {
        urlSection.style.display    = 'none';
        uploadSection.style.display = '';
        /* Clear URL field so the hidden input doesn't override the upload on submit */
        document.getElementById('blogImageUrl').value = '';
    }
}

/* ---- Image preview helpers ---- */
function previewFromUrl(url) {
    var img = document.getElementById('blogImgPreview');
    var ph  = document.getElementById('blogImgPlaceholder');
    if (url && url.startsWith('http')) {
        img.src = url; img.style.display = 'block'; ph.style.display = 'none';
    } else {
        img.style.display = 'none'; ph.style.display = 'block';
    }
}

function previewFromFile(input) {
    if (!input.files || !input.files[0]) return;
    var img = document.getElementById('blogImgPreview');
    var ph  = document.getElementById('blogImgPlaceholder');
    var reader = new FileReader();
    reader.onload = function(e) {
        img.src = e.target.result; img.style.display = 'block'; ph.style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
</body>
</html>
