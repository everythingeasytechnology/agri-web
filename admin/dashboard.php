<?php
require_once __DIR__ . '/auth.php';
require_admin_login();

$flash          = flash_get();
$admin_name     = admin_username();
$total_queries  = db_fetch('SELECT COUNT(*) AS c FROM contact_queries')['c'] ?? 0;
$unread_count   = db_fetch('SELECT COUNT(*) AS c FROM contact_queries WHERE status = ?', ['new'])['c'] ?? 0;
$total_products = db_fetch('SELECT COUNT(*) AS c FROM products')['c'] ?? 0;
$total_blogs    = db_fetch('SELECT COUNT(*) AS c FROM blogs')['c'] ?? 0;
$recent_queries  = db_fetch_all('SELECT * FROM contact_queries ORDER BY created_at DESC LIMIT 5');
$recent_products = db_fetch_all('SELECT * FROM products ORDER BY created_at DESC LIMIT 5');
$recent_blogs    = db_fetch_all('SELECT * FROM blogs ORDER BY created_at DESC LIMIT 5');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | Ficus International Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="admin.css" />
</head>
<body>
<div class="admin-layout">
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="fas fa-leaf"></i></div>
            <div class="brand-text">
                <h2>Ficus Admin</h2>
                <span>Control Panel</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-label">Main</div>
            <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="queries.php"><i class="fas fa-envelope-open-text"></i> Contact Queries
                <?php if ($unread_count > 0): ?>
                <span style="margin-left:auto;background:var(--danger);color:#fff;font-size:10px;padding:1px 7px;border-radius:10px"><?= $unread_count ?></span>
                <?php endif; ?>
            </a>
            <div class="nav-section-label" style="margin-top:12px">Content</div>
            <a href="products.php"><i class="fas fa-seedling"></i> Products</a>
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
                <h1>Dashboard</h1>
                <span>Welcome back, <?= html_escape($admin_name) ?></span>
            </div>
            <div class="topbar-right">
                <div class="topbar-badge">
                    <i class="fas fa-bell"></i>
                    <?php if ($unread_count > 0): ?><span class="badge-dot"></span><?php endif; ?>
                </div>
                <div class="topbar-user">
                    <div class="avatar"><?= strtoupper(substr($admin_name, 0, 1)) ?></div>
                    <div class="user-info">
                        <strong><?= html_escape($admin_name) ?></strong>
                        <span>Administrator</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content">

            <div class="stats-grid">
                <div class="stat-card queries">
                    <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $total_queries ?></div>
                        <div class="stat-label">Total Queries</div>
                    </div>
                </div>
                <div class="stat-card products">
                    <div class="stat-icon"><i class="fas fa-seedling"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $total_products ?></div>
                        <div class="stat-label">Products</div>
                    </div>
                </div>
                <div class="stat-card blogs">
                    <div class="stat-icon"><i class="fas fa-blog"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $total_blogs ?></div>
                        <div class="stat-label">Blog Posts</div>
                    </div>
                </div>
            </div>

            <!-- Recent Queries -->
            <div class="panel">
                <div class="panel-header">
                    <h2><i class="fas fa-envelope-open-text"></i> Recent Contact Queries</h2>
                    <a href="queries.php" class="btn btn-outline btn-sm">View All</a>
                </div>
                <div class="panel-body" style="padding:0">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_queries)): ?>
                            <tr>
                                <td colspan="7" style="text-align:center;padding:30px;color:var(--text-mid);font-size:13px">
                                    <i class="fas fa-inbox" style="font-size:28px;opacity:.2;display:block;margin-bottom:8px"></i>
                                    No queries yet.
                                </td>
                            </tr>
                            <?php else: foreach ($recent_queries as $i => $q): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><strong><?= html_escape($q['name']) ?></strong></td>
                                <td><?= html_escape($q['email']) ?></td>
                                <td><?= html_escape($q['subject']) ?></td>
                                <td><?= date('d M Y', strtotime($q['created_at'])) ?></td>
                                <td><span class="badge badge-<?= html_escape($q['status']) ?>"><?= ucfirst(html_escape($q['status'])) ?></span></td>
                                <td><a href="queries.php?view=<?= $q['id'] ?>" class="btn btn-secondary btn-xs"><i class="fas fa-eye"></i> View</a></td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bottom Grid -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">

                <div class="panel">
                    <div class="panel-header">
                        <h2><i class="fas fa-seedling"></i> Products</h2>
                        <a href="products.php" class="btn btn-outline btn-sm">Manage</a>
                    </div>
                    <div class="panel-body" style="padding:0">
                        <?php if (empty($recent_products)): ?>
                        <div style="text-align:center;padding:30px;color:var(--text-mid);font-size:13px">
                            <i class="fas fa-seedling" style="font-size:28px;opacity:.2;display:block;margin-bottom:8px"></i>
                            No products added yet.
                        </div>
                        <?php else: ?>
                        <table class="admin-table">
                            <?php foreach ($recent_products as $p): ?>
                            <tr>
                                <td><?php if ($p['image_url']): ?><img src="<?= html_escape($p['image_url']) ?>" style="width:44px;height:36px;object-fit:cover;border-radius:4px"><?php else: ?><i class="fas fa-image" style="opacity:.2;font-size:20px"></i><?php endif; ?></td>
                                <td><strong><?= html_escape($p['name']) ?></strong></td>
                                <td><span class="badge badge-read"><?= html_escape($p['category']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header">
                        <h2><i class="fas fa-blog"></i> Blog Posts</h2>
                        <a href="blogs.php" class="btn btn-outline btn-sm">Manage</a>
                    </div>
                    <div class="panel-body" style="padding:0">
                        <?php if (empty($recent_blogs)): ?>
                        <div style="text-align:center;padding:30px;color:var(--text-mid);font-size:13px">
                            <i class="fas fa-blog" style="font-size:28px;opacity:.2;display:block;margin-bottom:8px"></i>
                            No blog posts yet.
                        </div>
                        <?php else: ?>
                        <table class="admin-table">
                            <?php foreach ($recent_blogs as $b): ?>
                            <tr>
                                <td><?php if ($b['image_url']): ?><img src="<?= html_escape($b['image_url']) ?>" style="width:44px;height:36px;object-fit:cover;border-radius:4px"><?php else: ?><i class="fas fa-image" style="opacity:.2;font-size:20px"></i><?php endif; ?></td>
                                <td><strong><?= html_escape($b['title']) ?></strong></td>
                                <td><span class="badge badge-<?= html_escape($b['status']) ?>"><?= ucfirst(html_escape($b['status'])) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Query Detail Modal -->
<div class="modal-overlay" id="queryModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-envelope" style="color:var(--secondary);margin-right:8px"></i> Query Details</h3>
            <div class="modal-close" onclick="closeModal('queryModal')"><i class="fas fa-times"></i></div>
        </div>
        <div class="modal-body" id="queryModalBody"></div>
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
</body>
</html>
