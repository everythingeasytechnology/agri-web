<?php
require_once __DIR__ . '/auth.php';
require_admin_login();

// ── Helpers ─────────────────────────────────────────────────────────────────
function extract_reel_id(string $url): string {
    // https://www.instagram.com/reel/ABC123xyz/  →  ABC123xyz
    if (preg_match('#instagram\.com/(?:reel|p)/([A-Za-z0-9_-]+)#', $url, $m)) {
        return $m[1];
    }
    return '';
}

function embed_url(string $url): string {
    $id = extract_reel_id($url);
    return $id ? "https://www.instagram.com/reel/{$id}/embed/" : '';
}

// ── POST handlers ─────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $label    = trim($_POST['label'] ?? '');
        $reel_url = trim($_POST['reel_url'] ?? '');
        if (!$label || !$reel_url) {
            flash_set('error', 'Label and Reel URL are required.');
        } elseif (!extract_reel_id($reel_url)) {
            flash_set('error', 'Invalid Instagram Reel URL. Use format: https://www.instagram.com/reel/REEL_ID/');
        } else {
            $count = db_fetch('SELECT COUNT(*) AS c FROM instagram_reels')['c'] ?? 0;
            db_query('INSERT INTO instagram_reels (label, reel_url, sort_order) VALUES (?, ?, ?)',
                [$label, $reel_url, (int)$count]);
            flash_set('success', 'Reel added successfully.');
        }
        header('Location: reels.php');
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            db_query('DELETE FROM instagram_reels WHERE id = ?', [$id]);
            flash_set('success', 'Reel deleted.');
        }
        header('Location: reels.php');
        exit;
    }

    if ($action === 'reorder') {
        $ids = $_POST['order'] ?? [];
        foreach ($ids as $sort => $rid) {
            db_query('UPDATE instagram_reels SET sort_order = ? WHERE id = ?', [(int)$sort, (int)$rid]);
        }
        header('Location: reels.php');
        exit;
    }
}

$flash = flash_get();
$reels = db_fetch_all('SELECT * FROM instagram_reels ORDER BY sort_order ASC, id ASC');
$unread_count = db_fetch('SELECT COUNT(*) AS c FROM contact_queries WHERE status = ?', ['new'])['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Instagram Reels | Ficus Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="admin.css" />
    <style>
        .reel-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin-top: 24px; }
        .reel-card { background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.08); }
        .reel-card__embed { position:relative; width:100%; aspect-ratio:9/16; background:#111; }
        .reel-card__embed iframe { width:100%; height:100%; border:none; display:block; }
        .reel-card__body { padding:12px 14px; }
        .reel-card__label { font-weight:700; font-size:14px; color:#1a1a1a; margin-bottom:6px; display:flex; align-items:center; gap:6px; }
        .reel-card__label i { color:#e1306c; }
        .reel-card__url { font-size:11px; color:#999; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:10px; display:block; }
        .reel-card__actions { display:flex; gap:8px; }
        .btn-del { background:#fee2e2; color:#dc2626; border:none; padding:6px 14px; border-radius:6px; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:5px; }
        .btn-del:hover { background:#fecaca; }
        .add-form-card { background:#fff; border-radius:14px; padding:24px; box-shadow:0 2px 12px rgba(0,0,0,0.07); margin-bottom:28px; }
        .add-form-card h3 { font-size:16px; font-weight:700; margin-bottom:16px; display:flex; align-items:center; gap:8px; color:#1a1a1a; }
        .add-form-card h3 i { color:#e1306c; }
        .form-row { display:grid; grid-template-columns:1fr 2fr auto; gap:12px; align-items:end; }
        .form-group label { display:block; font-size:12px; font-weight:700; color:#555; margin-bottom:5px; text-transform:uppercase; letter-spacing:.4px; }
        .form-group input { width:100%; padding:10px 14px; border:1.5px solid #e5e7eb; border-radius:8px; font-size:14px; }
        .form-group input:focus { outline:none; border-color:#88b04b; }
        .btn-add { background:#88b04b; color:#fff; border:none; padding:10px 20px; border-radius:8px; font-size:14px; font-weight:700; cursor:pointer; white-space:nowrap; display:flex; align-items:center; gap:6px; height:42px; }
        .btn-add:hover { background:#6a8f35; }
        .empty-state { text-align:center; padding:60px 20px; color:#aaa; }
        .empty-state i { font-size:48px; margin-bottom:14px; display:block; }
        .hint-box { background:#f0f7e6; border:1px solid #c6e09a; border-radius:8px; padding:12px 16px; font-size:13px; color:#4a7a1e; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px; }
        .hint-box i { margin-top:2px; flex-shrink:0; }
        @media(max-width:600px){ .form-row{ grid-template-columns:1fr; } }
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
            <a href="blogs.php"><i class="fas fa-blog"></i> Blog Posts</a>
            <!-- <a href="reels.php" class="active"><i class="fab fa-instagram"></i> Instagram Reels</a> -->
            <div class="nav-section-label" style="margin-top:12px">Site</div>
            <a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h1>Instagram Reels</h1>
                <span>Manage homepage reel embeds</span>
            </div>
            <div class="topbar-right">
                <div class="topbar-badge"><i class="fas fa-bell"></i>
                    <?php if ($unread_count > 0): ?><span class="badge-dot"></span><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="content-area">

            <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?>">
                <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                <?= html_escape($flash['message']) ?>
            </div>
            <?php endif; ?>

            <!-- Add Reel Form -->
            <div class="add-form-card">
                <h3><i class="fab fa-instagram"></i> Add New Reel</h3>

                <div class="hint-box">
                    <i class="fas fa-info-circle"></i>
                    <span>Instagram reel URL ka format: <strong>https://www.instagram.com/reel/REEL_ID/</strong></span>
                </div>

                <form method="post" action="reels.php">
                    <input type="hidden" name="action" value="add">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Label</label>
                            <input type="text" name="label" placeholder="e.g. Chia Seeds Reel" required />
                        </div>
                        <div class="form-group">
                            <label>Instagram Reel URL</label>
                            <input type="url" name="reel_url" placeholder="https://www.instagram.com/reel/ABC123/" required />
                        </div>
                        <button type="submit" class="btn-add"><i class="fas fa-plus"></i> Add Reel</button>
                    </div>
                </form>
            </div>

            <!-- Reels List -->
            <?php if (empty($reels)): ?>
            <div class="empty-state">
                <i class="fab fa-instagram" style="color:#e1306c;opacity:0.4;"></i>
                <p>No reels added yet.</p>
            </div>
            <?php else: ?>
            <p style="color:#777;font-size:14px;margin-bottom:4px;"><?= count($reels) ?> reel(s) — homepage pe max 4 dikhenge</p>
            <div class="reel-grid">
                <?php foreach ($reels as $r):
                    $embed = embed_url($r['reel_url']);
                ?>
                <div class="reel-card">
                    <div class="reel-card__embed">
                        <?php if ($embed): ?>
                        <blockquote
                            class="instagram-media"
                            data-instgrm-permalink="<?= html_escape($r['reel_url']) ?>"
                            data-instgrm-version="14"
                            style="background:#fff;border:0;margin:0;padding:0;width:100%;min-width:100%;">
                        </blockquote>
                        <?php else: ?>
                        <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#aaa;flex-direction:column;gap:8px;padding:20px;">
                            <i class="fas fa-exclamation-triangle" style="font-size:24px;"></i>
                            <span style="font-size:12px;text-align:center;">Invalid Instagram URL</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="reel-card__body">
                        <div class="reel-card__label"><i class="fab fa-instagram"></i> <?= html_escape($r['label']) ?></div>
                        <a href="<?= html_escape($r['reel_url']) ?>" target="_blank" class="reel-card__url"><?= html_escape($r['reel_url']) ?></a>
                        <div class="reel-card__actions">
                            <form method="post" action="reels.php" onsubmit="return confirm('Delete this reel?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <button type="submit" class="btn-del"><i class="fas fa-trash"></i> Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<div id="toast" class="toast"></div>
<script src="admin.js"></script>
<?php if (!empty($reels)): ?>
<script async src="//www.instagram.com/embed.js"></script>
<?php endif; ?>
</body>
</html>
