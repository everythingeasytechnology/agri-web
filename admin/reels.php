<?php
require_once __DIR__ . '/auth.php';
require_admin_login();

// ── Helpers ─────────────────────────────────────────────────────────────────
function extract_reel_id(string $url): string {
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
        /* ── Add Form ── */
        .add-form-card { background:#fff; border-radius:16px; padding:28px 32px; box-shadow:0 2px 16px rgba(0,0,0,0.07); margin-bottom:32px; }
        .add-form-card h3 { font-size:16px; font-weight:700; margin-bottom:18px; display:flex; align-items:center; gap:10px; color:#1a1a1a; }
        .ig-gradient { background:linear-gradient(135deg,#833ab4,#fd1d1d,#fcb045); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .hint-box { background:#f0f7e6; border:1px solid #c6e09a; border-radius:10px; padding:12px 16px; font-size:13px; color:#4a7a1e; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px; }
        .hint-box i { margin-top:2px; flex-shrink:0; }
        .form-row { display:grid; grid-template-columns:1fr 2fr auto; gap:14px; align-items:end; }
        .form-group label { display:block; font-size:11px; font-weight:700; color:#888; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px; }
        .form-group input { width:100%; padding:11px 14px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; transition:border-color .2s,box-shadow .2s; }
        .form-group input:focus { outline:none; border-color:#88b04b; box-shadow:0 0 0 3px rgba(136,176,75,.12); }
        .btn-add { background:linear-gradient(135deg,#88b04b,#5a8a2a); color:#fff; border:none; padding:11px 22px; border-radius:10px; font-size:14px; font-weight:700; cursor:pointer; white-space:nowrap; display:flex; align-items:center; gap:8px; height:44px; transition:opacity .2s,transform .15s; }
        .btn-add:hover { opacity:.9; transform:translateY(-1px); }

        /* ── Stats bar ── */
        .reels-stats { display:flex; align-items:center; gap:12px; margin-bottom:20px; }
        .reels-stats__count { background:linear-gradient(135deg,#833ab4,#fd1d1d,#fcb045); color:#fff; font-size:13px; font-weight:700; padding:5px 14px; border-radius:20px; display:inline-flex; align-items:center; gap:6px; }
        .reels-stats__note { font-size:13px; color:#aaa; }

        /* ── Reel Grid ── */
        .reel-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:24px; padding:4px 2px; }
        .reel-card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08); display:flex; flex-direction:column; transition:transform .2s,box-shadow .2s; max-width:320px; }
        .reel-card:hover { transform:translateY(-4px); box-shadow:0 10px 32px rgba(0,0,0,0.13); }
        @media(max-width:768px){
            .reel-grid { grid-template-columns:1fr; }
            .reel-card { max-width:100%; }
        }

        .reel-card__preview { position:relative; width:100%; aspect-ratio:9/16; background:#111; overflow:hidden; }
        .reel-card__preview iframe { position:absolute; top:0; left:0; width:100%; height:100%; border:none; }
        .reel-card__badge { position:absolute; top:10px; left:10px; background:rgba(0,0,0,.55); color:#fff; font-size:11px; font-weight:700; padding:3px 10px; border-radius:20px; backdrop-filter:blur(4px); z-index:2; pointer-events:none; }
        .reel-card__invalid { display:flex; align-items:center; justify-content:center; height:100%; color:#555; flex-direction:column; gap:8px; padding:20px; background:#1a1a1a; }
        .reel-card__invalid i { font-size:28px; color:#e1306c; }
        .reel-card__invalid span { font-size:12px; color:#aaa; text-align:center; }

        .reel-card__body { padding:14px 16px 16px; flex:1; display:flex; flex-direction:column; gap:6px; }
        .reel-card__label { font-weight:700; font-size:14px; color:#1a1a1a; display:flex; align-items:center; gap:7px; }
        .reel-card__url { font-size:11px; color:#bbb; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; text-decoration:none; }
        .reel-card__url:hover { color:#88b04b; }

        .reel-card__actions { display:flex; gap:8px; margin-top:10px; }
        .btn-open { flex:1; background:#f0f7e6; color:#5a8a2a; border:none; padding:9px 10px; border-radius:9px; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; text-decoration:none; transition:background .2s; }
        .btn-open:hover { background:#dff0c8; color:#3d6b1a; text-decoration:none; }
        .btn-del { flex:1; background:#fee2e2; color:#dc2626; border:none; padding:9px 10px; border-radius:9px; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; transition:background .2s; }
        .btn-del:hover { background:#fecaca; }

        /* ── Empty ── */
        .empty-state { text-align:center; padding:70px 20px; background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.05); }
        .empty-state .empty-icon { font-size:52px; margin-bottom:14px; display:block; background:linear-gradient(135deg,#833ab4,#fd1d1d,#fcb045); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .empty-state p { color:#aaa; font-size:15px; margin:0; }

        @media(max-width:600px){
            .form-row{ grid-template-columns:1fr; }
            .add-form-card { padding:20px 18px; }
        }
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
            <a href="reels.php" class="active"><i class="fab fa-instagram"></i> Instagram Reels</a>
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
                <h3>
                    <i class="fab fa-instagram ig-gradient" style="font-size:20px;"></i>
                    Add New Reel
                </h3>
                <div class="hint-box">
                    <i class="fas fa-info-circle"></i>
                    <span>Instagram reel URL format: <strong>https://www.instagram.com/reel/REEL_ID/</strong></span>
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
                <i class="fab fa-instagram empty-icon"></i>
                <p>No reels added yet. Use the form above to add one.</p>
            </div>
            <?php else: ?>

            <div class="reels-stats">
                <span class="reels-stats__count"><i class="fab fa-instagram"></i> <?= count($reels) ?> Reel<?= count($reels) > 1 ? 's' : '' ?></span>
                <span class="reels-stats__note">All reels will appear on the homepage</span>
            </div>

            <div class="reel-grid">
                <?php foreach ($reels as $i => $r):
                    $embed = embed_url($r['reel_url']);
                    $reel_id = extract_reel_id($r['reel_url']);
                ?>
                <div class="reel-card">
                    <div class="reel-card__preview">
                        <span class="reel-card__badge">#<?= $i + 1 ?></span>
                        <?php if ($embed): ?>
                        <iframe
                            src="<?= html_escape($embed) ?>"
                            allowtransparency="true"
                            allowfullscreen="true"
                            scrolling="no"
                            loading="lazy"
                        ></iframe>
                        <?php else: ?>
                        <div class="reel-card__invalid">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Invalid Instagram URL</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="reel-card__body">
                        <div class="reel-card__label">
                            <i class="fab fa-instagram ig-gradient"></i>
                            <?= html_escape($r['label']) ?>
                        </div>
                        <a href="<?= html_escape($r['reel_url']) ?>" target="_blank" rel="noopener" class="reel-card__url">
                            <?= html_escape($r['reel_url']) ?>
                        </a>
                        <div class="reel-card__actions">
                            <a href="<?= html_escape($r['reel_url']) ?>" target="_blank" rel="noopener" class="btn-open">
                                <i class="fas fa-external-link-alt"></i> Open
                            </a>
                            <form method="post" action="reels.php" onsubmit="return confirm('Are you sure you want to delete this reel?')" style="flex:1;display:flex;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <button type="submit" class="btn-del" style="width:100%;">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
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
</body>
</html>
