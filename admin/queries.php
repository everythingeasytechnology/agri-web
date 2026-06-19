<?php
require_once __DIR__ . '/auth.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'mark_status') {
        $id     = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        if ($id && in_array($status, ['new', 'read', 'replied'])) {
            db_query('UPDATE contact_queries SET status=? WHERE id=?', [$status, $id]);
            flash_set('Query marked as "' . $status . '".');
        }
        header('Location: queries.php');
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            db_query('DELETE FROM contact_queries WHERE id=?', [$id]);
            flash_set('Query deleted.');
        }
        header('Location: queries.php');
        exit;
    }

    if ($action === 'clear_all') {
        db_query('DELETE FROM contact_queries');
        flash_set('All queries cleared.');
        header('Location: queries.php');
        exit;
    }
}

$view_query = null;
if (isset($_GET['view'])) {
    $view_query = db_fetch('SELECT * FROM contact_queries WHERE id=?', [(int)$_GET['view']]);
    if ($view_query && $view_query['status'] === 'new') {
        db_query('UPDATE contact_queries SET status="read" WHERE id=?', [(int)$_GET['view']]);
        $view_query['status'] = 'read';
    }
}

$search   = trim($_GET['search'] ?? '');
$flash    = flash_get();
$unread_count = db_fetch('SELECT COUNT(*) AS c FROM contact_queries WHERE status = ?', ['new'])['c'] ?? 0;
$admin_name   = admin_username();
$total_count  = db_fetch('SELECT COUNT(*) AS c FROM contact_queries')['c'] ?? 0;

if ($search) {
    $queries = db_fetch_all(
        'SELECT * FROM contact_queries WHERE name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ? ORDER BY created_at DESC',
        ["%$search%", "%$search%", "%$search%", "%$search%"]
    );
} else {
    $queries = db_fetch_all('SELECT * FROM contact_queries ORDER BY created_at DESC');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Queries | Ficus International Admin</title>
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
            <a href="queries.php" class="active"><i class="fas fa-envelope-open-text"></i> Contact Queries
                <?php if ($unread_count > 0): ?>
                <span style="margin-left:auto;background:var(--danger);color:#fff;font-size:10px;padding:1px 7px;border-radius:10px"><?= $unread_count ?></span>
                <?php endif; ?>
            </a>
            <div class="nav-section-label" style="margin-top:12px">Content</div>
            <a href="products.php"><i class="fas fa-seedling"></i> Products</a>
            <a href="blogs.php"><i class="fas fa-blog"></i> Blog Posts</a>
            <a href="reels.php"><i class="fab fa-instagram"></i> Instagram Reels</a>
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
                <h1>Contact Queries</h1>
                <span>Messages from your website visitors</span>
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

            <!-- Filter & Search -->
            <div class="panel">
                <div class="panel-header">
                    <h2><i class="fas fa-filter"></i> Filter Queries <span class="badge badge-read" style="margin-left:8px"><?= $total_count ?> total</span></h2>
                    <div style="display:flex;gap:10px;align-items:center">
                        <form method="get" action="queries.php" style="display:flex;gap:0">
                            <div class="search-bar">
                                <i class="fas fa-search"></i>
                                <input type="text" name="search" placeholder="Search queries..." value="<?= html_escape($search) ?>" />
                            </div>
                        </form>
                        <?php if ($total_count > 0): ?>
                        <form method="post" action="queries.php" onsubmit="return confirm('Clear ALL queries? This cannot be undone.')">
                            <input type="hidden" name="action" value="clear_all" />
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Clear All</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Queries Table -->
            <div class="panel">
                <div class="panel-body" style="padding:0">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($queries)): ?>
                            <tr>
                                <td colspan="8" style="text-align:center;padding:50px 20px">
                                    <i class="fas fa-inbox" style="font-size:40px;opacity:.15;display:block;margin-bottom:12px;color:var(--primary)"></i>
                                    <p style="color:var(--text-mid);font-size:14px"><?= $search ? 'No queries match your search.' : 'No contact queries yet.' ?></p>
                                    <?php if (!$search): ?>
                                    <p style="color:var(--text-mid);font-size:12px;margin-top:6px">Queries submitted via the website contact form will appear here.</p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php else: foreach ($queries as $i => $q): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><strong><?= html_escape($q['name']) ?></strong></td>
                                <td><a href="mailto:<?= html_escape($q['email']) ?>" style="color:var(--secondary)"><?= html_escape($q['email']) ?></a></td>
                                <td><?= html_escape($q['phone'] ?: '—') ?></td>
                                <td><?= html_escape($q['subject']) ?></td>
                                <td><?= date('d M Y', strtotime($q['created_at'])) ?></td>
                                <td><span class="badge badge-<?= html_escape($q['status']) ?>"><?= ucfirst(html_escape($q['status'])) ?></span></td>
                                <td>
                                    <div style="display:flex;gap:5px;flex-wrap:wrap">
                                        <a href="queries.php?view=<?= $q['id'] ?>" class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></a>
                                        <form method="post" action="queries.php" style="display:inline">
                                            <input type="hidden" name="action" value="mark_status" />
                                            <input type="hidden" name="id" value="<?= $q['id'] ?>" />
                                            <input type="hidden" name="status" value="read" />
                                            <button type="submit" class="btn btn-sm btn-outline" title="Mark read"><i class="fas fa-check"></i></button>
                                        </form>
                                        <form method="post" action="queries.php" style="display:inline">
                                            <input type="hidden" name="action" value="mark_status" />
                                            <input type="hidden" name="id" value="<?= $q['id'] ?>" />
                                            <input type="hidden" name="status" value="replied" />
                                            <button type="submit" class="btn btn-sm btn-accent" title="Mark replied"><i class="fas fa-reply"></i></button>
                                        </form>
                                        <form method="post" action="queries.php" style="display:inline" onsubmit="return confirm('Delete this query?')">
                                            <input type="hidden" name="action" value="delete" />
                                            <input type="hidden" name="id" value="<?= $q['id'] ?>" />
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

<!-- Query Detail Modal -->
<?php if ($view_query): ?>
<div class="modal-overlay show" id="queryModal">
    <div class="modal-box" style="max-width:560px">
        <div class="modal-header">
            <h3><i class="fas fa-envelope" style="color:var(--secondary);margin-right:8px"></i> Query Details</h3>
            <a href="queries.php<?= $search ? '?search=' . urlencode($search) : '' ?>" class="modal-close"><i class="fas fa-times"></i></a>
        </div>
        <div class="modal-body">
            <div class="query-meta">
                <div class="query-meta-item"><i class="fas fa-user"></i> <?= html_escape($view_query['name']) ?></div>
                <div class="query-meta-item"><i class="fas fa-envelope"></i>
                    <a href="mailto:<?= html_escape($view_query['email']) ?>" style="color:var(--secondary)"><?= html_escape($view_query['email']) ?></a>
                </div>
                <?php if ($view_query['phone']): ?>
                <div class="query-meta-item"><i class="fas fa-phone"></i> <?= html_escape($view_query['phone']) ?></div>
                <?php endif; ?>
                <div class="query-meta-item"><i class="fas fa-calendar"></i> <?= date('d M Y \a\t H:i', strtotime($view_query['created_at'])) ?></div>
                <div class="query-meta-item">
                    <i class="fas fa-circle" style="color:<?= $view_query['status'] === 'new' ? 'var(--danger)' : ($view_query['status'] === 'replied' ? 'var(--info)' : 'var(--success)') ?>"></i>
                    <?= ucfirst(html_escape($view_query['status'])) ?>
                </div>
            </div>
            <p style="font-size:13px;font-weight:700;color:var(--text-mid);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px">Subject</p>
            <p style="font-size:15px;font-weight:600;color:var(--primary);margin-bottom:16px"><?= html_escape($view_query['subject']) ?></p>
            <p style="font-size:13px;font-weight:700;color:var(--text-mid);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px">Message</p>
            <div class="query-message"><?= nl2br(html_escape($view_query['message'])) ?></div>
            <div style="margin-top:20px;display:flex;gap:10px;flex-wrap:wrap">
                <a href="mailto:<?= html_escape($view_query['email']) ?>?subject=Re: <?= urlencode($view_query['subject']) ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-reply"></i> Reply via Email
                </a>
                <form method="post" action="queries.php" style="display:inline">
                    <input type="hidden" name="action" value="mark_status" />
                    <input type="hidden" name="id" value="<?= $view_query['id'] ?>" />
                    <input type="hidden" name="status" value="replied" />
                    <button type="submit" class="btn btn-accent btn-sm"><i class="fas fa-check-double"></i> Mark as Replied</button>
                </form>
            </div>
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

<script src="admin.js"></script>
</body>
</html>
