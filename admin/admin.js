/* ===================================================
   FICUS INTERNATIONAL — ADMIN PANEL JAVASCRIPT
   Shared logic for all admin pages (localStorage)
   =================================================== */

/* ---- Storage Helpers ---- */
const DB = {
    get: (key, fallback = []) => {
        try { return JSON.parse(localStorage.getItem('ficus_' + key)) || fallback; }
        catch { return fallback; }
    },
    set: (key, val) => localStorage.setItem('ficus_' + key, JSON.stringify(val)),
    nextId: (key) => {
        const items = DB.get(key);
        return items.length ? Math.max(...items.map(i => i.id)) + 1 : 1;
    }
};

/* ---- Date Formatter ---- */
function fmtDate(ts) {
    return new Date(ts).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
}
function fmtTime(ts) {
    return new Date(ts).toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' });
}

/* ---- Toast ---- */
function showToast(msg, type = 'success') {
    const c = document.getElementById('toastContainer');
    if (!c) return;
    const t = document.createElement('div');
    t.className = 'toast toast-' + type;
    t.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i><p>${msg}</p>`;
    c.appendChild(t);
    setTimeout(() => t.remove(), 3200);
}

/* ---- Modal ---- */
function openModal(id)  { document.getElementById(id).classList.add('show'); }
function closeModal(id) { document.getElementById(id).classList.remove('show'); }

/* ---- Confirm Dialog ---- */
let _confirmCallback = null;
function openConfirm(callback) {
    _confirmCallback = callback;
    document.getElementById('confirmOverlay').classList.add('show');
    document.getElementById('confirmDeleteBtn').onclick = () => {
        if (_confirmCallback) _confirmCallback();
        closeConfirm();
    };
}
function closeConfirm() {
    document.getElementById('confirmOverlay').classList.remove('show');
    _confirmCallback = null;
}

/* ================================================================
   CONTACT QUERIES
   ================================================================ */

/* Called from contact form on the main website via a localStorage bridge */
window.submitContactQuery = function(data) {
    const queries = DB.get('queries');
    queries.unshift({
        id:      DB.nextId('queries'),
        name:    data.name    || '',
        email:   data.email   || '',
        phone:   data.phone   || '',
        subject: data.subject || 'General Enquiry',
        message: data.message || '',
        status:  'new',
        date:    Date.now()
    });
    DB.set('queries', queries);
};

/* Seed with demo data if empty so the UI has content on first open */
function seedDemoQueries() {
    if (DB.get('queries').length > 0) return;
    const demos = [
        { id:1, name:'Priya Sharma', email:'priya@example.com', phone:'+91 98765 43210', subject:'Quinoa Seeds Inquiry', message:'Hello, I am interested in bulk orders for quinoa seeds for our organic food store. Could you please share pricing and minimum order quantities? We operate across 5 cities in India.', status:'new', date: Date.now() - 3600000 },
        { id:2, name:'James Okonkwo', email:'james@trafri.ci', phone:'+225 07 12 34 56', subject:'Cashew Nut Export', message:'We are a distributor based in Abidjan. We would like to discuss a long-term partnership for sourcing raw cashew nuts from your network. Please contact us at your earliest.', status:'read', date: Date.now() - 86400000 },
        { id:3, name:'Maria Santos', email:'maria@biosupply.eu', phone:'+34 612 345 678', subject:'Organic Turmeric Supply', message:'Our company sources certified organic spices for European retailers. We are keen to understand your turmeric sourcing origin and certifications. Kindly revert with your product brochure.', status:'replied', date: Date.now() - 172800000 }
    ];
    DB.set('queries', demos);
}

function renderQueriesPage() {
    seedDemoQueries();
    updateQueryCounts();
    renderQueriesTable();
}

function updateQueryCounts() {
    const queries = DB.get('queries');
    const all     = queries.length;
    const newQ    = queries.filter(q => q.status === 'new').length;
    const readQ   = queries.filter(q => q.status === 'read').length;
    const replied = queries.filter(q => q.status === 'replied').length;

    const safe = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
    safe('count-all',     all);
    safe('count-new',     newQ);
    safe('count-read',    readQ);
    safe('count-replied', replied);
}

function renderQueriesTable() {
    const tbody = document.getElementById('queries-table-body');
    if (!tbody) return;
    updateQueryCounts();

    const search = (document.getElementById('querySearch')?.value || '').toLowerCase();
    const status = window.currentStatusFilter || 'all';
    let queries  = DB.get('queries');

    if (status !== 'all') queries = queries.filter(q => q.status === status);
    if (search) queries = queries.filter(q =>
        (q.name + q.email + q.subject + q.message).toLowerCase().includes(search)
    );

    if (!queries.length) {
        tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-mid)">
            <i class="fas fa-search" style="display:block;font-size:28px;opacity:.2;margin-bottom:10px"></i>
            No queries found.</td></tr>`;
        return;
    }

    tbody.innerHTML = queries.map((q, i) => `
        <tr>
            <td>${i + 1}</td>
            <td><strong>${esc(q.name)}</strong></td>
            <td><a href="mailto:${esc(q.email)}" style="color:var(--secondary)">${esc(q.email)}</a></td>
            <td>${esc(q.phone || '—')}</td>
            <td>${esc(q.subject)}</td>
            <td>${fmtDate(q.date)}</td>
            <td><span class="badge badge-${q.status}">${q.status.charAt(0).toUpperCase()+q.status.slice(1)}</span></td>
            <td>
                <div style="display:flex;gap:6px">
                    <button class="btn btn-sm btn-secondary" onclick="viewQuery(${q.id})" title="View"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-sm btn-outline" onclick="markQueryStatus(${q.id},'read')" title="Mark read"><i class="fas fa-check"></i></button>
                    <button class="btn btn-sm btn-accent" onclick="markQueryStatus(${q.id},'replied')" title="Mark replied"><i class="fas fa-reply"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteQueryConfirm(${q.id})" title="Delete"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>`).join('');
}

function viewQuery(id) {
    const q = DB.get('queries').find(x => x.id === id);
    if (!q) return;
    if (q.status === 'new') markQueryStatus(id, 'read', true);

    const body = document.getElementById('queryModalBody');
    if (body) body.innerHTML = `
        <div class="query-meta">
            <div class="query-meta-item"><i class="fas fa-user"></i> ${esc(q.name)}</div>
            <div class="query-meta-item"><i class="fas fa-envelope"></i> <a href="mailto:${esc(q.email)}" style="color:var(--secondary)">${esc(q.email)}</a></div>
            ${q.phone ? `<div class="query-meta-item"><i class="fas fa-phone"></i> ${esc(q.phone)}</div>` : ''}
            <div class="query-meta-item"><i class="fas fa-calendar"></i> ${fmtDate(q.date)} at ${fmtTime(q.date)}</div>
            <div class="query-meta-item"><i class="fas fa-circle" style="color:${q.status==='new'?'var(--danger)':q.status==='replied'?'var(--info)':'var(--success)'}"></i> ${q.status.charAt(0).toUpperCase()+q.status.slice(1)}</div>
        </div>
        <p style="font-size:13px;font-weight:700;color:var(--text-mid);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px">Subject</p>
        <p style="font-size:15px;font-weight:600;color:var(--primary);margin-bottom:16px">${esc(q.subject)}</p>
        <p style="font-size:13px;font-weight:700;color:var(--text-mid);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px">Message</p>
        <div class="query-message">${esc(q.message).replace(/\n/g,'<br>')}</div>
        <div style="margin-top:20px;display:flex;gap:10px">
            <a href="mailto:${esc(q.email)}?subject=Re: ${esc(q.subject)}" class="btn btn-primary btn-sm"><i class="fas fa-reply"></i> Reply via Email</a>
            <button class="btn btn-accent btn-sm" onclick="markQueryStatus(${q.id},'replied');closeModal('queryModal')"><i class="fas fa-check-double"></i> Mark as Replied</button>
        </div>`;
    openModal('queryModal');
}

function markQueryStatus(id, status, silent = false) {
    const queries = DB.get('queries');
    const idx = queries.findIndex(q => q.id === id);
    if (idx < 0) return;
    queries[idx].status = status;
    DB.set('queries', queries);
    renderQueriesTable();
    if (!silent) showToast(`Query marked as "${status}".`);
}

function deleteQueryConfirm(id) {
    openConfirm(() => {
        const queries = DB.get('queries').filter(q => q.id !== id);
        DB.set('queries', queries);
        renderQueriesTable();
        showToast('Query deleted.', 'success');
    });
}

function clearAllQueries() {
    if (!DB.get('queries').length) { showToast('No queries to clear.', 'error'); return; }
    openConfirm(() => {
        DB.set('queries', []);
        renderQueriesTable();
        showToast('All queries cleared.');
    });
}

/* ================================================================
   PRODUCTS
   ================================================================ */

function seedDemoProducts() {
    if (DB.get('products').length > 0) return;
    const demos = [
        { id:1,  name:'Chia Seeds',      category:'Seeds & Grains',  desc:'Nutrient-rich ancient seeds valued for their versatility and natural wellness benefits.',                        image:'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=280&fit=crop&q=80', date: Date.now()-500000 },
        { id:2,  name:'Quinoa Seeds',    category:'Seeds & Grains',  desc:'Premium protein-rich grains known for their exceptional nutritional profile.',                                   image:'https://images.unsplash.com/photo-1574323347407-f5e1ad6d0b6f?w=400&h=280&fit=crop&q=80', date: Date.now()-480000 },
        { id:3,  name:'Flaxseeds',       category:'Seeds & Grains',  desc:'Naturally rich seeds widely appreciated for their nutritional value and diverse applications.',                   image:'https://images.unsplash.com/photo-1490885578174-acda8905c2c1?w=400&h=280&fit=crop&q=80', date: Date.now()-460000 },
        { id:4,  name:'Pumpkin Seeds',   category:'Seeds & Grains',  desc:'Carefully sourced seeds offering a rich taste and excellent nutritional content.',                               image:'https://images.unsplash.com/photo-1601648764658-cf37e8c89b70?w=400&h=280&fit=crop&q=80', date: Date.now()-440000 },
        { id:5,  name:'Sunflower Seeds', category:'Seeds & Grains',  desc:'High-quality seeds recognized for their mild flavor and wide range of uses.',                                    image:'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=400&h=280&fit=crop&q=80', date: Date.now()-420000 },
        { id:6,  name:'Sesame Seeds',    category:'Seeds & Grains',  desc:'Premium-grade seeds prized for their distinctive aroma, flavor, and versatility.',                               image:'https://images.unsplash.com/photo-1509358271058-acd22cc93c52?w=400&h=280&fit=crop&q=80', date: Date.now()-400000 },
        { id:7,  name:'Soyabean Seeds',  category:'Seeds & Grains',  desc:'Globally demanded agricultural commodity known for its extensive industrial and food applications.',              image:'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=400&h=280&fit=crop&q=80', date: Date.now()-380000 },
        { id:8,  name:'Rice',            category:'Seeds & Grains',  desc:'Carefully sourced rice varieties delivering quality, consistency, and global appeal.',                           image:'https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?w=400&h=280&fit=crop&q=80', date: Date.now()-360000 },
        { id:9,  name:'Raw Cashew Nuts', category:'Nuts',            desc:'Carefully sourced raw cashews selected for quality, freshness, and consistency.',                                image:'https://images.unsplash.com/photo-1563412885-139e4045703d?w=400&h=280&fit=crop&q=80', date: Date.now()-340000 },
        { id:10, name:'Ivory Teakwood',  category:'Wood & Timber',   desc:'Premium hardwood renowned for its durability, strength, and natural elegance.',                                  image:'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=400&h=280&fit=crop&q=80', date: Date.now()-320000 },
        { id:11, name:'Cloves',          category:'Spices',          desc:'Aromatic spice celebrated for its rich flavor, fragrance, and culinary value.',                                 image:'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=400&h=280&fit=crop&q=80', date: Date.now()-300000 },
        { id:12, name:'Turmeric Powder', category:'Spices',          desc:'Finely processed turmeric known for its vibrant color and authentic quality.',                                  image:'https://images.unsplash.com/photo-1615485500704-8e990f9900f7?w=400&h=280&fit=crop&q=80', date: Date.now()-280000 },
        { id:13, name:'Cumin Seed',      category:'Spices',          desc:'Premium cumin seeds valued for their distinctive aroma and bold flavor profile.',                               image:'https://images.unsplash.com/photo-1570197788417-0e82375c9371?w=400&h=280&fit=crop&q=80', date: Date.now()-260000 },
        { id:14, name:'Coriander Seed',  category:'Spices',          desc:'Naturally aromatic seeds widely used in culinary and food processing industries.',                              image:'https://images.unsplash.com/photo-1599940824399-b87987ceb72a?w=400&h=280&fit=crop&q=80', date: Date.now()-240000 }
    ];
    DB.set('products', demos);
}

function renderProductsPage() {
    seedDemoProducts();
    renderProductsList();
}

function renderProductsList() {
    const search   = (document.getElementById('productSearch')?.value || '').toLowerCase();
    let products   = DB.get('products');
    if (search) products = products.filter(p => (p.name + p.category + p.desc).toLowerCase().includes(search));

    const count = document.getElementById('products-count');
    if (count) count.textContent = products.length;

    const view = window.productView || 'grid';

    if (view === 'grid') {
        const grid = document.getElementById('products-grid');
        if (!grid) return;
        if (!products.length) {
            grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><i class="fas fa-seedling"></i><p>No products found.</p></div>`;
            return;
        }
        grid.innerHTML = products.map(p => `
            <div class="product-card-admin">
                <div class="card-img">
                    ${p.image ? `<img src="${esc(p.image)}" alt="${esc(p.name)}" loading="lazy" />` : `<div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--text-mid);font-size:30px;opacity:.2"><i class="fas fa-image"></i></div>`}
                </div>
                <div class="card-body">
                    <span class="card-category">${esc(p.category)}</span>
                    <h3>${esc(p.name)}</h3>
                    <p>${esc(p.desc)}</p>
                    <div class="card-actions">
                        <button class="btn btn-primary btn-xs" onclick="editProduct(${p.id})"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn btn-danger btn-xs" onclick="deleteProductConfirm(${p.id})"><i class="fas fa-trash"></i> Delete</button>
                    </div>
                </div>
            </div>`).join('');

    } else {
        const tbody = document.getElementById('products-table-body');
        if (!tbody) return;
        if (!products.length) {
            tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-mid)">No products found.</td></tr>`;
            return;
        }
        tbody.innerHTML = products.map(p => `
            <tr>
                <td>${p.image ? `<img src="${esc(p.image)}" alt="${esc(p.name)}" style="width:60px;height:44px;object-fit:cover;border-radius:5px" loading="lazy">` : '<span style="color:var(--text-mid);font-size:20px"><i class="fas fa-image"></i></span>'}</td>
                <td><strong>${esc(p.name)}</strong></td>
                <td><span class="badge badge-read">${esc(p.category)}</span></td>
                <td style="max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${esc(p.desc)}</td>
                <td>
                    <div style="display:flex;gap:6px">
                        <button class="btn btn-primary btn-xs" onclick="editProduct(${p.id})"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn btn-danger btn-xs" onclick="deleteProductConfirm(${p.id})"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>`).join('');
    }
}

function saveProduct(e) {
    e.preventDefault();
    const editId   = document.getElementById('productEditId').value;
    const name     = document.getElementById('productName').value.trim();
    const category = document.getElementById('productCategory').value;
    const desc     = document.getElementById('productDesc').value.trim();
    const image    = document.getElementById('productImage').value.trim();

    const products = DB.get('products');

    if (editId) {
        const idx = products.findIndex(p => p.id === parseInt(editId));
        if (idx >= 0) { products[idx] = { ...products[idx], name, category, desc, image }; }
        DB.set('products', products);
        showToast('Product updated successfully!');
        cancelProductEdit();
    } else {
        products.unshift({ id: DB.nextId('products'), name, category, desc, image, date: Date.now() });
        DB.set('products', products);
        showToast('Product added successfully!');
        document.getElementById('productForm').reset();
        document.getElementById('imgPreviewBox').style.display  = 'none';
        document.getElementById('imgPreviewPlaceholder').style.display = 'block';
    }
    renderProductsList();
}

function editProduct(id) {
    const p = DB.get('products').find(x => x.id === id);
    if (!p) return;
    document.getElementById('productEditId').value   = p.id;
    document.getElementById('productName').value     = p.name;
    document.getElementById('productCategory').value = p.category;
    document.getElementById('productDesc').value     = p.desc;
    document.getElementById('productImage').value    = p.image || '';
    if (p.image) {
        document.getElementById('imgPreviewBox').src           = p.image;
        document.getElementById('imgPreviewBox').style.display = 'block';
        document.getElementById('imgPreviewPlaceholder').style.display = 'none';
    }
    document.getElementById('product-form-title').innerHTML = '<i class="fas fa-edit"></i> Edit Product';
    document.getElementById('productSubmitBtn').innerHTML   = '<i class="fas fa-save"></i> Update Product';
    document.getElementById('cancelEditBtn').style.display  = 'inline-flex';
    document.getElementById('productForm').scrollIntoView({ behavior:'smooth', block:'start' });
}

function cancelProductEdit() {
    document.getElementById('productForm').reset();
    document.getElementById('productEditId').value = '';
    document.getElementById('imgPreviewBox').style.display  = 'none';
    document.getElementById('imgPreviewPlaceholder').style.display = 'block';
    document.getElementById('product-form-title').innerHTML = '<i class="fas fa-plus-circle"></i> Add New Product';
    document.getElementById('productSubmitBtn').innerHTML   = '<i class="fas fa-save"></i> Save Product';
    document.getElementById('cancelEditBtn').style.display  = 'none';
}

function deleteProductConfirm(id) {
    openConfirm(() => {
        const products = DB.get('products').filter(p => p.id !== id);
        DB.set('products', products);
        renderProductsList();
        showToast('Product deleted.');
    });
}

/* ================================================================
   BLOGS
   ================================================================ */

function seedDemoBlogs() {
    if (DB.get('blogs').length > 0) return;
    const demos = [
        {
            id:1, title:'The Rising Global Demand for Chia Seeds and Quinoa',
            author:'Ficus International Team', category:'Agro Commodities',
            status:'published',
            image:'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=700&h=350&fit=crop&q=80',
            excerpt:'Global health trends are accelerating demand for nutrient-dense seeds like chia and quinoa — here\'s what traders and buyers need to know.',
            content:`<p>Over the past decade, chia seeds and quinoa have transformed from niche superfoods into mainstream staples in households and food manufacturers across Europe, North America, and Asia-Pacific. This shift is creating substantial export opportunities for source countries in South Asia and Latin America.</p>
<p><strong>Key drivers of demand:</strong></p>
<ul>
  <li>Growing consumer awareness of plant-based protein sources</li>
  <li>Rising incidence of lifestyle diseases driving health-conscious eating</li>
  <li>Popularity of organic and clean-label products in developed markets</li>
</ul>
<p>At Ficus International, we work directly with farming communities to source high-quality chia and quinoa, ensuring strict adherence to international food safety standards while supporting fair trade practices.</p>`,
            date: Date.now() - 604800000
        },
        {
            id:2, title:'Sourcing Spices Directly from Origin — Why It Matters',
            author:'Ficus International Team', category:'Trade & Export',
            status:'published',
            image:'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=700&h=350&fit=crop&q=80',
            excerpt:'Direct-origin sourcing of spices like turmeric, cumin, and coriander ensures better quality, traceability, and fair prices for all stakeholders.',
            content:`<p>The global spice trade is worth billions of dollars annually, yet a significant portion of the value is captured by intermediaries rather than farmers or end consumers. At Ficus International, our sourcing model is built on eliminating unnecessary layers and building direct relationships with spice-growing communities.</p>
<p><strong>Benefits of origin-direct sourcing:</strong></p>
<ul>
  <li><strong>Traceability:</strong> Full farm-to-shipment visibility for quality assurance</li>
  <li><strong>Freshness:</strong> Shorter supply chains preserve aroma and potency</li>
  <li><strong>Fair pricing:</strong> Farmers receive better returns, buyers get competitive rates</li>
  <li><strong>Consistency:</strong> Long-term partnerships ensure predictable supply</li>
</ul>
<p>Our spice portfolio includes turmeric, cumin, coriander, and cloves — all sourced from certified farms with documented growing and processing practices.</p>`,
            date: Date.now() - 1209600000
        },
        {
            id:3, title:'Ficus International Expands Its Footprint in West Africa',
            author:'Ficus International Team', category:'Company Updates',
            status:'published',
            image:'https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?w=700&h=350&fit=crop&q=80',
            excerpt:'With a growing presence in Côte d\'Ivoire and partnerships across West Africa, Ficus International is building robust supply chains for cashew nuts and agro commodities.',
            content:`<p>Ficus International is strengthening its West African operations with expanded partnerships in Côte d\'Ivoire — one of the world\'s largest producers of raw cashew nuts. Our Abidjan office serves as the hub for coordinating procurement, quality inspection, and export logistics for raw cashews and other regional commodities.</p>
<p><strong>Our West Africa presence includes:</strong></p>
<ul>
  <li>Direct partnerships with cashew farmer cooperatives</li>
  <li>Pre-shipment inspection facilities in Abidjan</li>
  <li>Relationships with licensed exporters for timber and agricultural produce</li>
</ul>
<p>This expansion underscores our commitment to being a truly global agro commodity bridge — connecting origin markets in Africa and Asia with buyers worldwide.</p>`,
            date: Date.now() - 1814400000
        }
    ];
    DB.set('blogs', demos);
}

function renderBlogsPage() {
    seedDemoBlogs();
    renderBlogsList();
}

function renderBlogsList() {
    const tbody   = document.getElementById('blogs-table-body');
    if (!tbody) return;

    const search  = (document.getElementById('blogSearch')?.value || '').toLowerCase();
    let   blogs   = DB.get('blogs');
    if (search) blogs = blogs.filter(b => (b.title + b.author + b.category + b.excerpt).toLowerCase().includes(search));

    const count   = document.getElementById('blogs-count');
    if (count) count.textContent = blogs.length;

    if (!blogs.length) {
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-mid)">
            <i class="fas fa-blog" style="display:block;font-size:28px;opacity:.2;margin-bottom:10px"></i>
            No blog posts found.</td></tr>`;
        return;
    }

    tbody.innerHTML = blogs.map(b => `
        <tr>
            <td>${b.image ? `<img class="blog-row-thumb" src="${esc(b.image)}" alt="${esc(b.title)}" loading="lazy">` : '<i class="fas fa-image" style="color:var(--text-mid);opacity:.3;font-size:22px"></i>'}</td>
            <td style="max-width:240px"><strong>${esc(b.title)}</strong></td>
            <td>${esc(b.author)}</td>
            <td><span class="badge badge-read">${esc(b.category)}</span></td>
            <td>${fmtDate(b.date)}</td>
            <td><span class="badge badge-${b.status}">${b.status.charAt(0).toUpperCase()+b.status.slice(1)}</span></td>
            <td>
                <div style="display:flex;gap:6px">
                    <button class="btn btn-sm btn-secondary" onclick="viewBlog(${b.id})" title="Preview"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-sm btn-primary" onclick="editBlog(${b.id})" title="Edit"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteBlogConfirm(${b.id})" title="Delete"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>`).join('');
}

function saveBlog(e) {
    e.preventDefault();
    const editId   = document.getElementById('blogEditId').value;
    const title    = document.getElementById('blogTitle').value.trim();
    const author   = document.getElementById('blogAuthor').value.trim() || 'Ficus International Team';
    const category = document.getElementById('blogCategory').value;
    const status   = document.getElementById('blogStatus').value;
    const image    = document.getElementById('blogImage').value.trim();
    const excerpt  = document.getElementById('blogExcerpt').value.trim();
    const content  = document.getElementById('blogContent').value.trim();

    const blogs = DB.get('blogs');

    if (editId) {
        const idx = blogs.findIndex(b => b.id === parseInt(editId));
        if (idx >= 0) blogs[idx] = { ...blogs[idx], title, author, category, status, image, excerpt, content };
        DB.set('blogs', blogs);
        showToast('Blog post updated successfully!');
        cancelBlogEdit();
    } else {
        blogs.unshift({ id: DB.nextId('blogs'), title, author, category, status, image, excerpt, content, date: Date.now() });
        DB.set('blogs', blogs);
        showToast('Blog post published!');
        resetBlogForm();
    }
    renderBlogsList();
}

function editBlog(id) {
    const b = DB.get('blogs').find(x => x.id === id);
    if (!b) return;
    document.getElementById('blogEditId').value   = b.id;
    document.getElementById('blogTitle').value    = b.title;
    document.getElementById('blogAuthor').value   = b.author;
    document.getElementById('blogCategory').value = b.category;
    document.getElementById('blogStatus').value   = b.status;
    document.getElementById('blogImage').value    = b.image || '';
    document.getElementById('blogExcerpt').value  = b.excerpt;
    document.getElementById('blogContent').value  = b.content;
    if (b.image) {
        const img = document.getElementById('blogImgPreview');
        const ph  = document.getElementById('blogImgPlaceholder');
        if (img) { img.src = b.image; img.style.display = 'block'; }
        if (ph)  ph.style.display = 'none';
    }
    document.getElementById('blog-form-title').innerHTML   = '<i class="fas fa-edit"></i> Edit Blog Post';
    document.getElementById('blogSubmitBtn').innerHTML     = '<i class="fas fa-save"></i> Update Post';
    document.getElementById('cancelBlogEditBtn').style.display = 'inline-flex';
    document.getElementById('blogForm').scrollIntoView({ behavior:'smooth', block:'start' });
}

function cancelBlogEdit() {
    document.getElementById('blogForm').reset();
    document.getElementById('blogEditId').value = '';
    const img = document.getElementById('blogImgPreview');
    const ph  = document.getElementById('blogImgPlaceholder');
    if (img) img.style.display = 'none';
    if (ph)  ph.style.display  = 'block';
    document.getElementById('blogAuthor').value = 'Ficus International Team';
    document.getElementById('blog-form-title').innerHTML   = '<i class="fas fa-pen-nib"></i> Write New Blog Post';
    document.getElementById('blogSubmitBtn').innerHTML     = '<i class="fas fa-save"></i> Publish Post';
    document.getElementById('cancelBlogEditBtn').style.display = 'none';
}

function resetBlogForm() {
    document.getElementById('blogForm').reset();
    const img = document.getElementById('blogImgPreview');
    const ph  = document.getElementById('blogImgPlaceholder');
    if (img) img.style.display = 'none';
    if (ph)  ph.style.display  = 'block';
    document.getElementById('blogAuthor').value = 'Ficus International Team';
}

function viewBlog(id) {
    const b = DB.get('blogs').find(x => x.id === id);
    if (!b) return;
    const body = document.getElementById('blogViewBody');
    if (body) body.innerHTML = `
        ${b.image ? `<img src="${esc(b.image)}" alt="${esc(b.title)}" style="width:100%;height:220px;object-fit:cover;border-radius:8px;margin-bottom:18px">` : ''}
        <div style="display:flex;gap:10px;align-items:center;margin-bottom:12px;flex-wrap:wrap">
            <span class="badge badge-read">${esc(b.category)}</span>
            <span class="badge badge-${b.status}">${b.status.charAt(0).toUpperCase()+b.status.slice(1)}</span>
            <span style="font-size:12px;color:var(--text-mid)"><i class="fas fa-calendar"></i> ${fmtDate(b.date)}</span>
            <span style="font-size:12px;color:var(--text-mid)"><i class="fas fa-user"></i> ${esc(b.author)}</span>
        </div>
        <h2 style="font-size:20px;color:var(--primary);margin-bottom:12px;line-height:1.35">${esc(b.title)}</h2>
        <p style="font-size:13px;color:var(--text-mid);font-style:italic;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--border)">${esc(b.excerpt)}</p>
        <div style="font-size:14px;line-height:1.75;color:var(--text-dark)">${b.content}</div>`;
    openModal('blogViewModal');
}

function deleteBlogConfirm(id) {
    openConfirm(() => {
        const blogs = DB.get('blogs').filter(b => b.id !== id);
        DB.set('blogs', blogs);
        renderBlogsList();
        showToast('Blog post deleted.');
    });
}

/* ================================================================
   DASHBOARD
   ================================================================ */

function renderDashboard() {
    seedDemoQueries();
    seedDemoProducts();
    seedDemoBlogs();

    const queries  = DB.get('queries');
    const products = DB.get('products');
    const blogs    = DB.get('blogs');
    const unread   = queries.filter(q => q.status === 'new').length;

    const safe = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
    safe('total-queries',   queries.length);
    safe('unread-queries',  unread);
    safe('total-products',  products.length);
    safe('total-blogs',     blogs.length);
    if (document.getElementById('unread-badge')) {
        document.getElementById('unread-badge').textContent = unread || 0;
    }

    /* Recent Queries */
    const tbody = document.getElementById('recent-queries-body');
    if (tbody) {
        const recent = queries.slice(0, 5);
        if (!recent.length) {
            tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:30px;color:var(--text-mid);font-size:13px">No queries yet.</td></tr>`;
        } else {
            tbody.innerHTML = recent.map((q, i) => `
                <tr>
                    <td>${i+1}</td>
                    <td><strong>${esc(q.name)}</strong></td>
                    <td>${esc(q.email)}</td>
                    <td>${esc(q.subject)}</td>
                    <td>${fmtDate(q.date)}</td>
                    <td><span class="badge badge-${q.status}">${q.status.charAt(0).toUpperCase()+q.status.slice(1)}</span></td>
                    <td><button class="btn btn-secondary btn-xs" onclick="viewQueryDashboard(${q.id})"><i class="fas fa-eye"></i> View</button></td>
                </tr>`).join('');
        }
    }

    /* Recent Products */
    const pList = document.getElementById('recent-products-list');
    if (pList) {
        const recent = products.slice(0, 5);
        if (!recent.length) {
            pList.innerHTML = `<div style="text-align:center;padding:30px;color:var(--text-mid);font-size:13px"><i class="fas fa-seedling" style="font-size:28px;opacity:.2;display:block;margin-bottom:8px"></i>No products.</div>`;
        } else {
            pList.innerHTML = `<table class="admin-table">${recent.map(p => `
                <tr>
                    <td>${p.image ? `<img src="${esc(p.image)}" style="width:44px;height:36px;object-fit:cover;border-radius:4px">` : '<i class="fas fa-image" style="opacity:.2;font-size:20px"></i>'}</td>
                    <td><strong>${esc(p.name)}</strong></td>
                    <td><span class="badge badge-read">${esc(p.category)}</span></td>
                </tr>`).join('')}</table>`;
        }
    }

    /* Recent Blogs */
    const bList = document.getElementById('recent-blogs-list');
    if (bList) {
        const recent = blogs.slice(0, 5);
        if (!recent.length) {
            bList.innerHTML = `<div style="text-align:center;padding:30px;color:var(--text-mid);font-size:13px"><i class="fas fa-blog" style="font-size:28px;opacity:.2;display:block;margin-bottom:8px"></i>No blog posts.</div>`;
        } else {
            bList.innerHTML = `<table class="admin-table">${recent.map(b => `
                <tr>
                    <td>${b.image ? `<img src="${esc(b.image)}" style="width:44px;height:36px;object-fit:cover;border-radius:4px">` : '<i class="fas fa-image" style="opacity:.2;font-size:20px"></i>'}</td>
                    <td><strong>${esc(b.title)}</strong></td>
                    <td><span class="badge badge-${b.status}">${b.status.charAt(0).toUpperCase()+b.status.slice(1)}</span></td>
                </tr>`).join('')}</table>`;
        }
    }
}

function viewQueryDashboard(id) {
    const q = DB.get('queries').find(x => x.id === id);
    if (!q) return;
    const body = document.getElementById('queryModalBody');
    if (body) body.innerHTML = `
        <div class="query-meta">
            <div class="query-meta-item"><i class="fas fa-user"></i> ${esc(q.name)}</div>
            <div class="query-meta-item"><i class="fas fa-envelope"></i> <a href="mailto:${esc(q.email)}" style="color:var(--secondary)">${esc(q.email)}</a></div>
            ${q.phone ? `<div class="query-meta-item"><i class="fas fa-phone"></i> ${esc(q.phone)}</div>` : ''}
            <div class="query-meta-item"><i class="fas fa-calendar"></i> ${fmtDate(q.date)}</div>
        </div>
        <p style="font-size:15px;font-weight:600;color:var(--primary);margin-bottom:16px">${esc(q.subject)}</p>
        <div class="query-message">${esc(q.message).replace(/\n/g,'<br>')}</div>
        <div style="margin-top:20px">
            <a href="mailto:${esc(q.email)}?subject=Re: ${esc(q.subject)}" class="btn btn-primary btn-sm"><i class="fas fa-reply"></i> Reply via Email</a>
        </div>`;
    openModal('queryModal');
}

/* ---- Utility ---- */
function esc(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

/* Close modals on overlay click */
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) closeModal(e.target.id);
    if (e.target.classList.contains('confirm-overlay')) closeConfirm();
});

/* Keyboard esc */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.show').forEach(m => m.classList.remove('show'));
        closeConfirm();
    }
});
