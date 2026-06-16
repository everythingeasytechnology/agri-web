<?php
require_once __DIR__ . '/auth.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name     = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $desc     = trim($_POST['description'] ?? '');
        $image    = trim($_POST['image_url'] ?? '');
        if ($name && $category && $desc) {
            db_query(
                'INSERT INTO products (name, category, description, image_url) VALUES (?, ?, ?, ?)',
                [$name, $category, $desc, $image ?: null]
            );
            flash_set('Product added successfully!');
        } else {
            flash_set('Please fill in all required fields.', 'error');
        }
        header('Location: products.php');
        exit;
    }

    if ($action === 'edit') {
        $id       = (int)($_POST['id'] ?? 0);
        $name     = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $desc     = trim($_POST['description'] ?? '');
        $image    = trim($_POST['image_url'] ?? '');
        if ($id && $name && $category && $desc) {
            db_query(
                'UPDATE products SET name=?, category=?, description=?, image_url=? WHERE id=?',
                [$name, $category, $desc, $image ?: null, $id]
            );
            flash_set('Product updated successfully!');
        }
        header('Location: products.php');
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            db_query('DELETE FROM products WHERE id=?', [$id]);
            flash_set('Product deleted.');
        }
        header('Location: products.php');
        exit;
    }
}

$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_product = db_fetch('SELECT * FROM products WHERE id=?', [(int)$_GET['edit']]);
}

$search   = trim($_GET['search'] ?? '');
$flash    = flash_get();
$unread_count = db_fetch('SELECT COUNT(*) AS c FROM contact_queries WHERE status = ?', ['new'])['c'] ?? 0;
$admin_name   = admin_username();

if ($search) {
    $products = db_fetch_all(
        'SELECT * FROM products WHERE name LIKE ? OR category LIKE ? OR description LIKE ? ORDER BY created_at DESC',
        ["%$search%", "%$search%", "%$search%"]
    );
} else {
    $products = db_fetch_all('SELECT * FROM products ORDER BY created_at DESC');
}

$categories = ['Seeds & Grains', 'Spices', 'Nuts', 'Specialty', 'Wood & Timber', 'Other'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Products | Ficus International Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="admin.css" />
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
            <a href="products.php" class="active"><i class="fas fa-seedling"></i> Products</a>
            <a href="blogs.php"><i class="fas fa-blog"></i> Blog Posts</a>
            <!-- <a href="reels.php"><i class="fab fa-instagram"></i> Instagram Reels</a> -->
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
                <h1>Products</h1>
                <span>Manage your product catalog</span>
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

            <!-- Add / Edit Product Form -->
            <div class="panel">
                <div class="panel-header">
                    <h2 id="product-form-title">
                        <?php if ($edit_product): ?>
                        <i class="fas fa-edit"></i> Edit Product
                        <?php else: ?>
                        <i class="fas fa-plus-circle"></i> Add New Product
                        <?php endif; ?>
                    </h2>
                    <?php if ($edit_product): ?>
                    <a href="products.php" class="btn btn-outline btn-sm"><i class="fas fa-times"></i> Cancel Edit</a>
                    <?php endif; ?>
                </div>
                <div class="panel-body">
                    <form class="admin-form" method="post" action="products.php">
                        <input type="hidden" name="action" value="<?= $edit_product ? 'edit' : 'add' ?>" />
                        <?php if ($edit_product): ?>
                        <input type="hidden" name="id" value="<?= $edit_product['id'] ?>" />
                        <?php endif; ?>

                        <div class="form-row cols-2">
                            <div>
                                <label>Product Name *</label>
                                <input type="text" name="name" placeholder="e.g. Chia Seeds" required
                                    value="<?= html_escape($edit_product['name'] ?? '') ?>" />
                            </div>
                            <div>
                                <label>Category *</label>
                                <select name="category" required>
                                    <option value="">Select category...</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?= html_escape($cat) ?>"
                                        <?= (($edit_product['category'] ?? '') === $cat) ? 'selected' : '' ?>>
                                        <?= html_escape($cat) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div>
                                <label>Short Description *</label>
                                <textarea name="description" rows="3" placeholder="Brief product description (1–2 sentences)" required><?= html_escape($edit_product['description'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="form-row cols-2">
                            <div>
                                <label>Image URL</label>
                                <input type="url" name="image_url" id="productImage"
                                    placeholder="https://images.unsplash.com/photo-..."
                                    value="<?= html_escape($edit_product['image_url'] ?? '') ?>"
                                    oninput="previewImage(this.value)" />
                                <p class="field-hint"><i class="fas fa-info-circle"></i> Paste an Unsplash or any direct image URL</p>
                            </div>
                            <div>
                                <label>Image Preview</label>
                                <div style="border:2px dashed var(--border);border-radius:8px;height:100px;display:flex;align-items:center;justify-content:center;overflow:hidden;background:var(--light-bg)">
                                    <img id="imgPreviewBox" src="<?= html_escape($edit_product['image_url'] ?? '') ?>" alt="Preview"
                                        style="max-height:96px;max-width:100%;border-radius:6px;<?= empty($edit_product['image_url']) ? 'display:none' : '' ?>" />
                                    <span id="imgPreviewPlaceholder" style="color:var(--text-mid);font-size:12px;<?= !empty($edit_product['image_url']) ? 'display:none' : '' ?>">
                                        <i class="fas fa-image" style="display:block;text-align:center;font-size:22px;margin-bottom:6px;opacity:.3"></i>Preview
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?= $edit_product ? 'Update Product' : 'Save Product' ?>
                            </button>
                            <button type="reset" class="btn btn-outline" onclick="resetImgPreview()">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products List -->
            <div class="panel">
                <div class="panel-header">
                    <h2><i class="fas fa-list"></i> All Products <span class="badge badge-read" style="margin-left:8px"><?= count($products) ?></span></h2>
                    <div style="display:flex;gap:10px;align-items:center">
                        <form method="get" action="products.php" style="display:flex;gap:0">
                            <div class="search-bar">
                                <i class="fas fa-search"></i>
                                <input type="text" name="search" placeholder="Search products..." value="<?= html_escape($search) ?>" />
                            </div>
                        </form>
                        <div class="view-toggle">
                            <button class="view-btn active" id="gridViewBtn" onclick="setView('grid')" title="Grid view"><i class="fas fa-th"></i></button>
                            <button class="view-btn" id="listViewBtn" onclick="setView('list')" title="List view"><i class="fas fa-list"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Grid View -->
                <div class="panel-body" id="products-grid-view">
                    <?php if (empty($products)): ?>
                    <div class="empty-state" style="grid-column:1/-1">
                        <i class="fas fa-seedling"></i>
                        <p><?= $search ? 'No products match your search.' : 'No products added yet. Use the form above to add your first product.' ?></p>
                    </div>
                    <?php else: ?>
                    <div class="products-grid">
                        <?php foreach ($products as $p): ?>
                        <div class="product-card-admin">
                            <div class="card-img">
                                <?php if ($p['image_url']): ?>
                                <img src="<?= html_escape($p['image_url']) ?>" alt="<?= html_escape($p['name']) ?>" loading="lazy" />
                                <?php else: ?>
                                <div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--text-mid);font-size:30px;opacity:.2"><i class="fas fa-image"></i></div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <span class="card-category"><?= html_escape($p['category']) ?></span>
                                <h3><?= html_escape($p['name']) ?></h3>
                                <p><?= html_escape($p['description']) ?></p>
                                <div class="card-actions">
                                    <a href="products.php?edit=<?= $p['id'] ?>" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i> Edit</a>
                                    <form method="post" action="products.php" style="display:inline" onsubmit="return confirm('Delete this product?')">
                                        <input type="hidden" name="action" value="delete" />
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>" />
                                        <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- List View -->
                <div id="products-list-view" style="display:none;padding:0">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                            <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-mid)">No products yet.</td></tr>
                            <?php else: foreach ($products as $p): ?>
                            <tr>
                                <td><?php if ($p['image_url']): ?><img src="<?= html_escape($p['image_url']) ?>" alt="<?= html_escape($p['name']) ?>" style="width:60px;height:44px;object-fit:cover;border-radius:5px" loading="lazy"><?php else: ?><span style="color:var(--text-mid);font-size:20px"><i class="fas fa-image"></i></span><?php endif; ?></td>
                                <td><strong><?= html_escape($p['name']) ?></strong></td>
                                <td><span class="badge badge-read"><?= html_escape($p['category']) ?></span></td>
                                <td style="max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= html_escape($p['description']) ?></td>
                                <td>
                                    <div style="display:flex;gap:6px">
                                        <a href="products.php?edit=<?= $p['id'] ?>" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i> Edit</a>
                                        <form method="post" action="products.php" style="display:inline" onsubmit="return confirm('Delete this product?')">
                                            <input type="hidden" name="action" value="delete" />
                                            <input type="hidden" name="id" value="<?= $p['id'] ?>" />
                                            <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
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

<div class="toast-container" id="toastContainer"></div>

<?php if ($flash): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    showToast(<?= json_encode($flash['message']) ?>, <?= json_encode($flash['type']) ?>);
});
</script>
<?php endif; ?>

<script src="admin.js"></script>
<script>
    let currentView = 'grid';

    function setView(view) {
        currentView = view;
        document.getElementById('products-grid-view').style.display = (view === 'grid') ? 'block' : 'none';
        document.getElementById('products-list-view').style.display = (view === 'list') ? 'block' : 'none';
        document.getElementById('gridViewBtn').classList.toggle('active', view === 'grid');
        document.getElementById('listViewBtn').classList.toggle('active', view === 'list');
    }

    function previewImage(url) {
        const img = document.getElementById('imgPreviewBox');
        const ph  = document.getElementById('imgPreviewPlaceholder');
        if (url && url.startsWith('http')) {
            img.src = url;
            img.style.display = 'block';
            ph.style.display  = 'none';
        } else {
            img.style.display = 'none';
            ph.style.display  = 'block';
        }
    }

    function resetImgPreview() {
        document.getElementById('imgPreviewBox').style.display = 'none';
        document.getElementById('imgPreviewPlaceholder').style.display = 'block';
    }
</script>
</body>
</html>
