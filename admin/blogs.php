<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Blog Posts | Ficus International Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="admin.css" />
</head>
<body>
<div class="admin-layout">
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="fas fa-leaf"></i></div>
            <div class="brand-text"><h2>Ficus Admin</h2><span>Control Panel</span></div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-label">Main</div>
            <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="queries.php"><i class="fas fa-envelope-open-text"></i> Contact Queries</a>
            <div class="nav-section-label" style="margin-top:12px">Content</div>
            <a href="products.php"><i class="fas fa-seedling"></i> Products</a>
            <a href="blogs.php" class="active"><i class="fas fa-blog"></i> Blog Posts</a>
            <div class="nav-section-label" style="margin-top:12px">Site</div>
            <a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a>
        </nav>
        <div class="sidebar-footer">
            <a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
                <div class="topbar-badge"><i class="fas fa-bell"></i><span class="badge-dot"></span></div>
                <div class="topbar-user">
                    <div class="avatar">A</div>
                    <div class="user-info"><strong>Admin</strong><span>Administrator</span></div>
                </div>
            </div>
        </div>

        <div class="page-content">

            <!-- Add / Edit Blog Form -->
            <div class="panel">
                <div class="panel-header">
                    <h2 id="blog-form-title"><i class="fas fa-pen-nib"></i> Write New Blog Post</h2>
                    <button class="btn btn-outline btn-sm" id="cancelBlogEditBtn" onclick="cancelBlogEdit()" style="display:none">
                        <i class="fas fa-times"></i> Cancel Edit
                    </button>
                </div>
                <div class="panel-body">
                    <form class="admin-form" id="blogForm" onsubmit="saveBlog(event)">
                        <input type="hidden" id="blogEditId" value="" />

                        <div class="form-row">
                            <div>
                                <label>Blog Title *</label>
                                <input type="text" id="blogTitle" placeholder="Enter a descriptive blog title..." required />
                            </div>
                        </div>

                        <div class="form-row cols-3">
                            <div>
                                <label>Author</label>
                                <input type="text" id="blogAuthor" placeholder="e.g. Ficus International Team" value="Ficus International Team" />
                            </div>
                            <div>
                                <label>Category</label>
                                <select id="blogCategory">
                                    <option value="Agro Commodities">Agro Commodities</option>
                                    <option value="Trade & Export">Trade &amp; Export</option>
                                    <option value="Sustainability">Sustainability</option>
                                    <option value="Industry News">Industry News</option>
                                    <option value="Company Updates">Company Updates</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label>Status</label>
                                <select id="blogStatus">
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row cols-2">
                            <div>
                                <label>Featured Image URL</label>
                                <input type="url" id="blogImage" placeholder="https://images.unsplash.com/photo-..." oninput="previewBlogImage(this.value)" />
                                <p class="field-hint"><i class="fas fa-info-circle"></i> Paste a direct image URL (Unsplash, etc.)</p>
                            </div>
                            <div>
                                <label>Image Preview</label>
                                <div style="border:2px dashed var(--border);border-radius:8px;height:100px;display:flex;align-items:center;justify-content:center;overflow:hidden;background:var(--light-bg)">
                                    <img id="blogImgPreview" src="" alt="" style="max-height:96px;max-width:100%;border-radius:6px;display:none" />
                                    <span id="blogImgPlaceholder" style="color:var(--text-mid);font-size:12px;text-align:center"><i class="fas fa-image" style="display:block;font-size:22px;margin-bottom:6px;opacity:.3"></i>Preview</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div>
                                <label>Short Excerpt *</label>
                                <textarea id="blogExcerpt" rows="2" placeholder="A brief summary shown in blog listing (1–2 sentences)" required></textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div>
                                <label>Full Content *</label>
                                <textarea id="blogContent" rows="8" placeholder="Write your full blog post content here..." required></textarea>
                                <p class="field-hint"><i class="fas fa-info-circle"></i> Write the complete article. HTML tags are supported.</p>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="blogSubmitBtn">
                                <i class="fas fa-save"></i> Publish Post
                            </button>
                            <button type="reset" class="btn btn-outline" onclick="resetBlogForm()">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Blogs List -->
            <div class="panel">
                <div class="panel-header">
                    <h2><i class="fas fa-list"></i> All Blog Posts <span id="blogs-count" class="badge badge-read" style="margin-left:8px">0</span></h2>
                    <div style="display:flex;gap:10px;align-items:center">
                        <div class="search-bar">
                            <i class="fas fa-search"></i>
                            <input type="text" id="blogSearch" placeholder="Search posts..." oninput="renderBlogsList()" />
                        </div>
                    </div>
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
                        <tbody id="blogs-table-body">
                            <tr>
                                <td colspan="7" style="text-align:center;padding:50px 20px">
                                    <i class="fas fa-blog" style="font-size:40px;opacity:.15;display:block;margin-bottom:12px;color:var(--primary)"></i>
                                    <p style="color:var(--text-mid);font-size:14px">No blog posts yet.</p>
                                    <p style="color:var(--text-mid);font-size:12px;margin-top:6px">Use the form above to write your first blog post.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- View Post Modal -->
<div class="modal-overlay" id="blogViewModal">
    <div class="modal-box" style="max-width:700px">
        <div class="modal-header">
            <h3><i class="fas fa-eye" style="color:var(--secondary);margin-right:8px"></i> Blog Preview</h3>
            <div class="modal-close" onclick="closeModal('blogViewModal')"><i class="fas fa-times"></i></div>
        </div>
        <div class="modal-body" id="blogViewBody"></div>
    </div>
</div>

<!-- Confirm Delete -->
<div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-box">
        <div class="confirm-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Delete Blog Post?</h3>
        <p>This blog post will be permanently deleted from the admin panel.</p>
        <div class="confirm-actions">
            <button class="btn btn-outline" onclick="closeConfirm()">Cancel</button>
            <button class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>

<script src="admin.js"></script>
<script>
    function previewBlogImage(url) {
        const img = document.getElementById('blogImgPreview');
        const ph  = document.getElementById('blogImgPlaceholder');
        if (url && url.startsWith('http')) {
            img.src = url;
            img.style.display = 'block';
            ph.style.display  = 'none';
        } else {
            img.style.display = 'none';
            ph.style.display  = 'block';
        }
    }

    function cancelBlogEdit() {
        resetBlogForm();
        document.getElementById('blogEditId').value = '';
        document.getElementById('blog-form-title').innerHTML = '<i class="fas fa-pen-nib"></i> Write New Blog Post';
        document.getElementById('blogSubmitBtn').innerHTML = '<i class="fas fa-save"></i> Publish Post';
        document.getElementById('cancelBlogEditBtn').style.display = 'none';
    }

    function resetBlogForm() {
        document.getElementById('blogForm').reset();
        document.getElementById('blogImgPreview').style.display = 'none';
        document.getElementById('blogImgPlaceholder').style.display = 'block';
        document.getElementById('blogAuthor').value = 'Ficus International Team';
    }

    renderBlogsPage();
</script>
</body>
</html>
