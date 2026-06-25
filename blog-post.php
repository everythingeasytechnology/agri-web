<?php
require_once __DIR__ . '/admin/db.php';

$post = null;

// Try slug first, then fall back to numeric id
$slug = trim($_GET['slug'] ?? '');
$id   = (int)($_GET['id'] ?? 0);

if ($slug !== '') {
    try {
        $post = db_fetch("SELECT * FROM blogs WHERE slug = ? AND status = 'published'", [$slug]);
    } catch (Exception $e) {
        $post = null;
    }
}

if (!$post && $id > 0) {
    $post = db_fetch("SELECT * FROM blogs WHERE id = ? AND status = 'published'", [$id]);
}

if (!$post) {
    header('Location: news.php');
    exit;
}

$recent = db_fetch_all(
    "SELECT id, slug, title, image_url, created_at FROM blogs WHERE status = 'published' AND id != ? ORDER BY created_at DESC LIMIT 3",
    [$post['id']]
);

$categories = db_fetch_all(
    "SELECT category, COUNT(*) as cnt FROM blogs WHERE status = 'published' GROUP BY category ORDER BY cnt DESC"
);

$featured_image = $post['image_url'] ?: 'assets/images/blog/blog-v1-img1.jpg';
$formatted_date = date('F j, Y', strtotime($post['created_at']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo html_escape($post['title']); ?> | Ficus International</title>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicons/favicon-16x16.png" />
    <link rel="manifest" href="assets/images/favicons/site.webmanifest" />
    <meta name="description" content="<?php echo html_escape($post['excerpt']); ?>" />
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;0,700;0,800;1,400;1,700&family=Lato:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/vendors/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/vendors/animate/animate.min.css" />
    <link rel="stylesheet" href="assets/vendors/animate/custom-animate.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="assets/vendors/jarallax/jarallax.css" />
    <link rel="stylesheet" href="assets/vendors/jquery-magnific-popup/jquery.magnific-popup.css" />
    <link rel="stylesheet" href="assets/vendors/nouislider/nouislider.min.css" />
    <link rel="stylesheet" href="assets/vendors/nouislider/nouislider.pips.css" />
    <link rel="stylesheet" href="assets/vendors/odometer/odometer.min.css" />
    <link rel="stylesheet" href="assets/vendors/swiper/swiper.min.css" />
    <link rel="stylesheet" href="assets/vendors/icomoon-icons/style.css">
    <link rel="stylesheet" href="assets/vendors/tiny-slider/tiny-slider.min.css" />
    <link rel="stylesheet" href="assets/vendors/reey-font/stylesheet.css" />
    <link rel="stylesheet" href="assets/vendors/owl-carousel/owl.carousel.min.css" />
    <link rel="stylesheet" href="assets/vendors/owl-carousel/owl.theme.default.min.css" />
    <link rel="stylesheet" href="assets/vendors/twentytwenty/twentytwenty.css" />
    <link rel="stylesheet" href="assets/css/agriox.css" />
    <link rel="stylesheet" href="assets/css/agriox-rtl.css">
    <link rel="stylesheet" id="jssMode" href="assets/css/modes/agriox-light.css">
    <link rel="stylesheet" href="assets/vendors/toolbar/css/toolbar.css">
    <style>
        .mobile-nav__contact li > i {
            font-size: 15px !important;
            flex-shrink: 0 !important;
            min-width: 40px !important;
            width: 40px !important;
            height: 40px !important;
            border-radius: 50% !important;
        }
        .mobile-nav__contact li { min-width: 0; }
        .mobile-nav__contact li a {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            min-width: 0;
            display: block;
            font-size: clamp(11px, 3.5vw, 14px);
        }
        .logo img, .stricky-one-logo img { height: 60px; width: auto; object-fit: contain; }
        .mobile-nav__container .logo img { height: 50px; width: auto; object-fit: contain; }
        @media (max-width: 768px) { .logo img, .stricky-one-logo img { height: 46px; } }
        @media (max-width: 480px) { .logo img, .stricky-one-logo img { height: 38px; } }

        /* Blog post content */
        .blog-post__featured-img {
            width: 100%;
            max-height: 480px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 32px;
        }
        .blog-post__meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
            align-items: center;
        }
        .blog-post__meta span {
            font-size: 14px;
            color: #777;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .blog-post__meta span i { color: var(--thm-base, #88b04b); }
        .blog-post__meta .meta-category {
            background: var(--thm-base, #88b04b);
            color: #fff;
            padding: 3px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .blog-post__title {
            font-size: clamp(22px, 4vw, 36px);
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.3;
            margin-bottom: 24px;
        }
        .blog-post__divider {
            border: none;
            border-top: 2px solid #e8f0da;
            margin: 28px 0;
        }
        .blog-post__content {
            font-size: 16px;
            line-height: 1.85;
            color: #4a4a4a;
        }
        .blog-post__content h1,
        .blog-post__content h2,
        .blog-post__content h3,
        .blog-post__content h4 {
            color: #1a1a1a;
            margin-top: 28px;
            margin-bottom: 12px;
            font-weight: 700;
        }
        .blog-post__content p { margin-bottom: 18px; }
        .blog-post__content ul,
        .blog-post__content ol { margin-bottom: 18px; padding-left: 24px; }
        .blog-post__content li { margin-bottom: 6px; }
        .blog-post__content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 15px;
        }
        .blog-post__content table th,
        .blog-post__content table td {
            border: 1px solid #ddd;
            padding: 10px 14px;
            text-align: left;
        }
        .blog-post__content table th {
            background: #f3f8e8;
            font-weight: 700;
            color: #1a1a1a;
        }
        .blog-post__content table tr:nth-child(even) td { background: #fafafa; }
        .blog-post__content img { max-width: 100%; height: auto; border-radius: 6px; margin: 10px 0; }
        .blog-post__content blockquote {
            border-left: 4px solid var(--thm-base, #88b04b);
            margin: 24px 0;
            padding: 16px 20px;
            background: #f8faf3;
            border-radius: 0 6px 6px 0;
            font-style: italic;
            color: #555;
        }

        /* Back to blog link */
        .blog-post__back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--thm-base, #88b04b);
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            margin-bottom: 28px;
            transition: gap 0.2s;
        }
        .blog-post__back:hover { gap: 12px; color: var(--thm-base, #88b04b); }

        /* Sidebar */
        .blog-sidebar__card {
            background: #fff;
            border: 1px solid #eef2e8;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 12px;
            display: flex;
            gap: 14px;
            padding: 12px;
            transition: box-shadow 0.2s;
            text-decoration: none;
        }
        .blog-sidebar__card:hover { box-shadow: 0 4px 18px rgba(136,176,75,0.12); text-decoration: none; }
        .blog-sidebar__card-img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            flex-shrink: 0;
        }
        .blog-sidebar__card-body h4 {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.4;
            margin-bottom: 4px;
        }
        .blog-sidebar__card-body span { font-size: 12px; color: #999; }
        .blog-sidebar__widget {
            background: #fff;
            border: 1px solid #eef2e8;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 28px;
        }
        .blog-sidebar__widget-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e8f0da;
        }
        .blog-sidebar__cat-list { list-style: none; padding: 0; margin: 0; }
        .blog-sidebar__cat-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid #f2f5ec;
        }
        .blog-sidebar__cat-list li:last-child { border-bottom: none; }
        .blog-sidebar__cat-list li a {
            color: #4a4a4a;
            font-size: 15px;
            text-decoration: none;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .blog-sidebar__cat-list li a:hover { color: var(--thm-base, #88b04b); }
        .blog-sidebar__cat-list li a i { color: var(--thm-base, #88b04b); font-size: 12px; }
        .blog-sidebar__cat-list li span {
            background: #f3f8e8;
            color: var(--thm-base, #88b04b);
            font-size: 12px;
            font-weight: 700;
            padding: 2px 9px;
            border-radius: 20px;
        }
    </style>
</head>
<body>

    <div class="preloader">
        <img class="preloader__image" width="60" src="assets/images/loader.png" alt="" />
    </div>

    <div class="page-wrapper">

        <!-- ==================== HEADER ==================== -->
<?php include 'header.php'; ?>

        <div class="stricky-header stricked-menu main-menu">
            <div class="sticky-header__content"></div>
        </div>

        <!-- ==================== PAGE HEADER ==================== -->
        <section class="page-header clearfix" style="background-image: url(assets/images/backgrounds/page-header-bg.jpg);">
            <div class="container">
                <div class="page-header__inner text-center clearfix">
                    <ul class="thm-breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="news.php">Blog</a></li>
                        <li><?php echo html_escape($post['title']); ?></li>
                    </ul>
                    <h2><?php echo html_escape($post['title']); ?></h2>
                </div>
            </div>
        </section>

        <!-- ==================== BLOG POST CONTENT ==================== -->
        <section class="blog-details" style="padding: 80px 0;">
            <div class="container">
                <div class="row">

                    <!-- Main Article -->
                    <div class="col-xl-8 col-lg-8">
                        <div class="wow fadeInUp" data-wow-delay="100ms" data-wow-duration="1000ms">

                            <a href="news.php" class="blog-post__back">
                                <i class="fas fa-arrow-left"></i> Back to Blog
                            </a>

                            <!-- Featured Image -->
                            <?php if ($featured_image): ?>
                            <img
                                src="<?php echo html_escape($featured_image); ?>"
                                alt="<?php echo html_escape($post['title']); ?>"
                                class="blog-post__featured-img"
                            />
                            <?php endif; ?>

                            <!-- Meta -->
                            <div class="blog-post__meta">
                                <span class="meta-category"><?php echo html_escape($post['category']); ?></span>
                                <span><i class="fas fa-user-circle"></i> <?php echo html_escape($post['author']); ?></span>
                                <span><i class="fas fa-calendar-alt"></i> <?php echo $formatted_date; ?></span>
                            </div>

                            <!-- Title -->
                            <h1 class="blog-post__title"><?php echo html_escape($post['title']); ?></h1>

                            <hr class="blog-post__divider" />

                            <!-- Full Content -->
                            <div class="blog-post__content">
                                <?php echo $post['content']; ?>
                            </div>

                            <hr class="blog-post__divider" />

                            <!-- Share Row -->
                            <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap; margin-top:10px;">
                                <span style="font-weight:700; color:#1a1a1a; font-size:15px;">Share:</span>
                                <a href="https://www.facebook.com/sharer/sharer ?u=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"
                                   target="_blank" rel="noopener"
                                   style="width:36px;height:36px;border-radius:50%;background:#3b5998;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:15px;">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($post['title']); ?>"
                                   target="_blank" rel="noopener"
                                   style="width:36px;height:36px;border-radius:50%;background:#1da1f2;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:15px;">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"
                                   target="_blank" rel="noopener"
                                   style="width:36px;height:36px;border-radius:50%;background:#0077b5;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:15px;">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="https://wa.me/?text=<?php echo urlencode($post['title'] . ' ' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"
                                   target="_blank" rel="noopener"
                                   style="width:36px;height:36px;border-radius:50%;background:#25d366;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:15px;">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-xl-4 col-lg-4" style="margin-top: 56px;">

                        <!-- Recent Posts -->
                        <?php if ($recent): ?>
                        <div class="blog-sidebar__widget wow fadeInRight" data-wow-delay="200ms" data-wow-duration="1000ms">
                            <h3 class="blog-sidebar__widget-title">Recent Posts</h3>
                            <?php foreach ($recent as $ri => $r):
                                $rn = $ri % 3 + 1;
                                $r_img = $r['image_url'] ?: "assets/images/blog/blog-v1-img{$rn}.jpg";
                            ?>
                            <a href="<?php echo !empty($r['slug']) ? 'blog-post.php?slug=' . urlencode($r['slug']) : 'blog-post.php?id=' . $r['id']; ?>" class="blog-sidebar__card">
                                <img src="<?php echo html_escape($r_img); ?>"
                                     alt="<?php echo html_escape($r['title']); ?>"
                                     class="blog-sidebar__card-img" />
                                <div class="blog-sidebar__card-body">
                                    <h4><?php echo html_escape($r['title']); ?></h4>
                                    <span><i class="fas fa-calendar-alt" style="margin-right:4px;"></i><?php echo date('M j, Y', strtotime($r['created_at'])); ?></span>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Categories -->
                        <?php if ($categories): ?>
                        <div class="blog-sidebar__widget wow fadeInRight" data-wow-delay="300ms" data-wow-duration="1000ms">
                            <h3 class="blog-sidebar__widget-title">Categories</h3>
                            <ul class="blog-sidebar__cat-list">
                                <?php foreach ($categories as $cat): ?>
                                <li>
                                    <a href="news.php?category=<?php echo urlencode($cat['category']); ?>">
                                        <i class="fas fa-angle-right"></i>
                                        <?php echo html_escape($cat['category']); ?>
                                    </a>
                                    <span><?php echo (int)$cat['cnt']; ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <!-- Contact CTA -->
                        <div class="blog-sidebar__widget wow fadeInRight" data-wow-delay="400ms" data-wow-duration="1000ms"
                             style="background: linear-gradient(135deg, #3a5c1a 0%, #5a8a2a 100%); border-color: transparent; text-align: center; padding: 30px 24px;">
                            <i class="fas fa-tractor" style="font-size:40px; color:#f1cf69; margin-bottom:14px; display:block;"></i>
                            <h3 style="color:#fff; font-size:18px; margin-bottom:10px;">Need Agro Commodities?</h3>
                            <p style="color:#c9e0a8; font-size:14px; margin-bottom:20px;">Get in touch with our sourcing team for pricing and availability.</p>
                            <a href="contact.php" class="thm-btn" style="padding: 10px 24px; font-size: 14px;">Contact Us</a>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== CTA ==================== -->
        <section class="cta-one" style="background-image: url(assets/images/backgrounds/cta-v1-bg.jpg);">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="cta-one__wrapper">
                            <div class="cta-one__left">
                                <div class="cta-one__left-icon" style="display:inline-flex;align-items:center;justify-content:center;"><i class="fas fa-tractor" style="font-size:52px;color:#f1cf69;"></i></div>
                                <div class="cta-one__left-title">
                                    <h2>Partner with Ficus International Today</h2>
                                </div>
                            </div>
                            <div class="cta-one__right">
                                <div class="cta-one__right-btn">
                                    <a href="contact.php" class="thm-btn">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== FOOTER ==================== -->
   <?php include 'footer.php'; ?>

    </div><!-- /.page-wrapper -->

    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>
            <div class="logo-box">
                <a href="index.php" aria-label="logo image"><img src="assets/images/logo.jpeg" width="155" alt="" /></a>
            </div>
            <div class="mobile-nav__container"></div>
            <ul class="mobile-nav__contact list-unstyled">
                <li><i class="fas fa-phone"></i><a href="tel:+919653530361">+91 96535 30361</a></li>
                <li><i class="fas fa-envelope"></i><a href="mailto:contact@ficusinternational.com">contact@ficusinternational.com</a></li>
            </ul>
            <div class="mobile-nav__top">
                <div class="mobile-nav__social">
                    <a href="https://x.com/FicusIntl" target="_blank" class="fab fa-twitter"></a>
                    <a href="https://www.facebook.com/share/1JcoEmGGfT/" target="_blank" class="fab fa-facebook-square"></a>
                    <a href="https://www.linkedin.com/company/ficus-international/" target="_blank" class="fab fa-linkedin"></a>
                    <a href="https://www.instagram.com/ficusinternational?igsh=MXA5dHowOXFjYnBlcg==" target="_blank" class="fab fa-instagram"></a>
                </div>
            </div>
        </div>
    </div>

    <a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fas fa-arrow-up"></i></a>

    <script src="assets/vendors/jquery/jquery-3.5.1.min.js"></script>
    <script src="assets/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendors/jarallax/jarallax.min.js"></script>
    <script src="assets/vendors/jquery-appear/jquery.appear.min.js"></script>
    <script src="assets/vendors/jquery-magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets/vendors/nouislider/nouislider.min.js"></script>
    <script src="assets/vendors/odometer/odometer.min.js"></script>
    <script src="assets/vendors/swiper/swiper.min.js"></script>
    <script src="assets/vendors/wow/wow.js"></script>
    <script src="assets/vendors/owl-carousel/owl.carousel.min.js"></script>
    <script src="assets/vendors/parallax/parallax.min.js"></script>
    <script src="assets/js/agriox.js"></script>
    <script src="assets/vendors/toolbar/js/js.cookie.min.js"></script>
    <script src="assets/vendors/toolbar/js/jQuery.style.switcher.min.js"></script>
    <script src="assets/vendors/toolbar/js/toolbar.js"></script>
</body>
</html>
