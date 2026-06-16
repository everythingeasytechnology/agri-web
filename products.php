<?php
require_once __DIR__ . '/admin/db.php';
$all_products = db_fetch_all('SELECT * FROM products ORDER BY category ASC, created_at ASC');

// Group by category
$grouped = [];
foreach ($all_products as $p) {
    $grouped[$p['category']][] = $p;
}

$icons = ['fa-seedling', 'fa-leaf', 'fa-chart-line', 'fa-box-open'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Our Products | Ficus International</title>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicons/favicon-16x16.png" />
    <link rel="manifest" href="assets/images/favicons/site.webmanifest" />
    <meta name="description" content="Explore Ficus International's range of premium agro commodities sourced directly from their countries of origin." />
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css2?family=Averia+Sans+Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&family=Shadows+Into+Light&display=swap" rel="stylesheet">
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
        /* Center arrow icon inside read-more-btn circle */
        .services-one__single-content .read-more-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .services-one__single-content .read-more-btn i {
            color: var(--agriox-primary, #334b35);
            font-size: 18px;
            line-height: 1;
            transition: color 200ms linear;
        }
        .services-one__single-content .read-more-btn:hover i { color: #fff; }
        /* Center icons inside services icon circle */
        .services-one__single-img-icon {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }
        /* Interested in Our Products CTA icon */
        .cta-one__left-icon {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
        }
        .cta-one__left-icon i { color: var(--agriox-base, #f1cf69); font-size: 52px; }
        .mobile-nav__contact li > i {
            font-size: 15px !important;
            flex-shrink: 0 !important;
            min-width: 40px !important;
            width: 40px !important;
            height: 40px !important;
            border-radius: 50% !important;
        }
        .mobile-nav__contact li {
            min-width: 0;
        }
        .mobile-nav__contact li a {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            min-width: 0;
            display: block;
            font-size: clamp(11px, 3.5vw, 14px);
        }
        /* Logo sizing — use logo.jpeg at all breakpoints */
        .logo img, .stricky-one-logo img {
            height: 60px;
            width: auto;
            object-fit: contain;
        }
        .mobile-nav__container .logo img {
            height: 50px;
            width: auto;
            object-fit: contain;
        }
        @media (max-width: 768px) {
            .logo img, .stricky-one-logo img { height: 46px; }
        }
        @media (max-width: 480px) {
            .logo img, .stricky-one-logo img { height: 38px; }
        }
    </style>
</head>
<body>

    <div class="preloader">
        <img class="preloader__image" width="60" src="assets/images/loader.png" alt="" />
    </div>

    <div class="page-wrapper">

        <!-- ==================== HEADER ==================== -->
        <header class="main-header main-header--one clearfix">
            <div class="main-header--one__wrapper">
                <div class="main-header--one__top clearfix">
                    <div class="auto-container">
                        <div class="main-header--one__top-left">
                            <div class="text"><p>Global Agro Commodity Sourcing &amp; Supply</p></div>
                            <div class="social-link clearfix">
                                <ul>
                                    <li><a href="https://x.com/FicusIntl" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="https://www.facebook.com/share/1JcoEmGGfT/" target="_blank"><i class="fab fa-facebook"></i></a></li>
                                    <li><a href="https://www.linkedin.com/company/ficus-international/" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                                    <li><a href="https://www.instagram.com/ficusinternational?igsh=MXA5dHowOXFjYnBlcg==" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="main-header--one__top-right clearfix">
                            <ul>
                                <li>
                                    <div class="icon"><i class="fa fa-envelope"></i></div>
                                    <div class="text"><p><a href="mailto:contact@ficusinternational.com">contact@ficusinternational.com</a></p></div>
                                </li>
                                <li>
                                    <div class="icon"><i class="fa fa-phone"></i></div>
                                    <div class="text"><p><a href="tel:+919653530361">+91 96535 30361</a></p></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="main-header--one__bottom clearfix">
                    <div class="auto-container">
                        <div class="main-header--one__bottom-inner">
                            <nav class="main-menu main-menu--1">
                                <div class="main-menu__inner">
                                    <a href="#" class="mobile-nav__toggler"><i class="fa fa-bars"></i></a>
                                    <div class="stricky-one-logo">
                                        <div class="logo">
                                            <a href="index.php">
                                                <img class="dark-logo" src="assets/images/logo.jpeg" alt="Ficus International">
                                                <img class="light-logo" src="assets/images/logo.jpeg" alt="Ficus International">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="main-header--one__bottom-left">
                                        <ul class="main-menu__list">
                                            <li><a href="index.php">Home</a></li>
                                            <li><a href="about.php">About</a></li>
                                            <li class="current"><a href="products.php">Products</a></li>
                                            <li><a href="news.php">Blog</a></li>
                                            <li><a href="contact.php">Contact</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                            <div class="main-header--one__bottom-middel">
                                <div class="logo">
                                    <a href="index.php">
                                        <img class="dark-logo" src="assets/images/logo.jpeg" alt="Ficus International">
                                        <img class="light-logo" src="assets/images/logo.jpeg" alt="Ficus International">
                                    </a>
                                </div>
                            </div>
                            <div class="main-header--one__bottom-right clearfix">
                                <div class="contact-box">
                                    <div class="icon" style="display:flex;align-items:center;justify-content:center;"><i class="fas fa-phone-alt" style="color:#fff;font-size:20px;"></i></div>
                                    <div class="text">
                                        <p>Call Anytime</p>
                                        <a href="tel:+919653530361">+91 96535 30361</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="stricky-header stricked-menu main-menu">
            <div class="sticky-header__content"></div>
        </div>

        <!-- ==================== PAGE HEADER ==================== -->
        <section class="page-header clearfix" style="background-image: url(assets/images/backgrounds/page-header-bg.jpg);">
            <div class="container">
                <div class="page-header__inner text-center clearfix">
                    <ul class="thm-breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li>Products</li>
                    </ul>
                    <h2>Our Products</h2>
                </div>
            </div>
        </section>

        <!-- ==================== PRODUCTS INTRO ==================== -->
        <section class="about-one" style="padding-bottom: 0;">
            <div class="container">
                <div class="sec-title text-center">
                    <div class="icon"><img src="assets/images/resources/sec-title-icon1.png" alt=""></div>
                    <span class="sec-title__tagline">Origin-Sourced Quality</span>
                    <h2 class="sec-title__title">Premium Agro Commodities</h2>
                    <p style="max-width:700px; margin: 0 auto 10px; font-size:16px; line-height:1.8;">Every product we offer is carefully sourced directly from its country of origin, ensuring the highest standards of quality, freshness, and authenticity. We supply businesses across international markets with a comprehensive range of agro commodities.</p>
                </div>
            </div>
        </section>

        <?php if (empty($grouped)): ?>
        <!-- No products in DB yet -->
        <section class="services-one">
            <div class="container">
                <p style="text-align:center;color:#888;padding:60px 0;font-size:16px;">
                    No products found. Add products from the <a href="admin/products.php">admin panel</a>.
                </p>
            </div>
        </section>

        <?php else:
            $section_index = 0;
            foreach ($grouped as $category => $products):
                $bg = $section_index % 2 === 1 ? ' style="background:#f5f5f5;"' : '';
                $show_bg_bar = $section_index === 0;
        ?>
        <!-- ==================== <?= html_escape(strtoupper($category)) ?> ==================== -->
        <section class="services-one"<?= $bg ?>>
            <?php if ($show_bg_bar): ?>
            <div class="services-one__bg wow slideInDown" data-wow-delay="100ms" data-wow-duration="2500ms"></div>
            <?php endif; ?>
            <div class="container">
                <div class="sec-title text-center">
                    <h3 class="sec-title__title" style="font-size:28px;"><?= html_escape($category) ?></h3>
                </div>
                <div class="row">
                    <?php foreach ($products as $pi => $p):
                        $icon  = $icons[$pi % 4];
                        $anim  = ($pi % 4 < 2) ? 'fadeInLeft' : 'fadeInRight';
                        $delay = ($pi % 2) * 100;
                        $img   = $p['image_url'] ?: 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=280&fit=crop&auto=format&q=80';
                    ?>
                    <div class="col-xl-3 col-lg-6 wow <?= $anim ?>" data-wow-delay="<?= $delay ?>ms" data-wow-duration="1000ms">
                        <div class="services-one__single">
                            <div class="services-one__single-img">
                                <div class="services-one__single-img-inner">
                                    <img src="<?= html_escape($img) ?>" alt="<?= html_escape($p['name']) ?>" />
                                </div>
                            </div>
                            <div class="services-one__single-content text-center">
                                <div class="services-one__single-img-icon"><i class="fas <?= $icon ?>" style="font-size:38px"></i></div>
                                <h3><?= html_escape($p['name']) ?></h3>
                                <p><?= html_escape($p['description']) ?></p>
                                <a href="contact.php" class="read-more-btn"><i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
                $section_index++;
            endforeach;
        endif;
        ?>

        <!-- ==================== CTA ==================== -->
        <section class="cta-one" style="background-image: url(assets/images/backgrounds/cta-v1-bg.jpg);">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="cta-one__wrapper">
                            <div class="cta-one__left">
                                <div class="cta-one__left-icon" style="display:inline-flex;align-items:center;justify-content:center;"><i class="fas fa-tractor" style="font-size:52px;color:#f1cf69;"></i></div>
                                <div class="cta-one__left-title">
                                    <h2>Interested in Our Products? Get a Quote</h2>
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
        <footer class="footer-one">
            <div class="footer-one__top">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="footer-one__top-wrapper">
                                <div class="footer-one__bg"><img src="assets/images/backgrounds/footer-one-bg.png" alt="" /></div>
                                <div class="row">
                                    <div class="col-xl-4 col-lg-6 col-md-6 wow animated fadeInUp" data-wow-delay="0.1s">
                                        <div class="footer-widget__column footer-widget__about">
                                            <div class="footer-widget__about-logo">
                                                <a href="index.php" style="text-decoration:none;"><span style="font-family:'Reey',cursive,sans-serif;font-size:28px;color:#f1cf69;letter-spacing:0.5px;line-height:1.2;display:inline-block;">Ficus<br><span style="font-size:14px;color:#fff;letter-spacing:3px;font-family:'DM Sans',sans-serif;font-weight:600;text-transform:uppercase;">International</span></span></a>
                                            </div>
                                            <p class="footer-widget__about-text">Ficus International — global agro commodity sourcing and supply, delivering quality from origin to markets worldwide.</p>
                                            <div class="footer-widget__about-contact-box">
                                                <p class="phone"><a href="tel:+919653530361"><i class="fas fa-phone-square-alt"></i>+91 96535 30361</a></p>
                                                <p><a href="mailto:contact@ficusinternational.com"><i class="fa fa-envelope"></i>contact@ficusinternational.com</a></p>
                                                <p class="text"><i class="fas fa-map-marker-alt"></i>Karnal, Haryana, India</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-6 col-md-6 wow animated fadeInUp" data-wow-delay="0.3s">
                                        <div class="footer-widget__column footer-widget__explore">
                                            <h2 class="footer-widget__title">Quick Links</h2>
                                            <ul class="footer-widget__explore-list">
                                                <li class="footer-widget__explore-list-item"><a href="index.php">Home</a></li>
                                                <li class="footer-widget__explore-list-item"><a href="about.php">About Us</a></li>
                                                <li class="footer-widget__explore-list-item"><a href="products.php">Products</a></li>
                                                <li class="footer-widget__explore-list-item"><a href="news.php">Blog</a></li>
                                                <li class="footer-widget__explore-list-item"><a href="contact.php">Contact</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-md-6 wow animated fadeInUp" data-wow-delay="0.5s">
                                        <div class="footer-widget__column footer-widget__explore">
                                            <h2 class="footer-widget__title">Our Products</h2>
                                            <ul class="footer-widget__explore-list">
                                                <li class="footer-widget__explore-list-item"><a href="products.php">Seeds &amp; Grains</a></li>
                                                <li class="footer-widget__explore-list-item"><a href="products.php">Spices</a></li>
                                                <li class="footer-widget__explore-list-item"><a href="products.php">Raw Nuts</a></li>
                                                <li class="footer-widget__explore-list-item"><a href="products.php">Timber</a></li>
                                                <li class="footer-widget__explore-list-item"><a href="products.php">Rice</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-md-6 wow animated fadeInUp" data-wow-delay="0.7s">
                                        <div class="footer-widget__column footer-widget__newletter">
                                            <h2 class="footer-widget__title">Follow Us</h2>
                                            <p class="footer-widget__newletter-text">Stay connected with Ficus International</p>
                                            <div style="display:flex; gap:12px; margin-top:15px; flex-wrap:wrap;">
                                                <a href="https://www.facebook.com/share/1JcoEmGGfT/" target="_blank" style="font-size:28px; color:#fff;"><i class="fab fa-facebook-square"></i></a>
                                                <a href="https://www.instagram.com/ficusinternational?igsh=MXA5dHowOXFjYnBlcg==" target="_blank" style="font-size:28px; color:#fff;"><i class="fab fa-instagram"></i></a>
                                                <a href="https://www.linkedin.com/company/ficus-international/" target="_blank" style="font-size:28px; color:#fff;"><i class="fab fa-linkedin"></i></a>
                                                <a href="https://x.com/FicusIntl" target="_blank" style="font-size:28px; color:#fff;"><i class="fab fa-twitter"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-one__bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="footer-one__bottom-inner">
                                <div class="footer-one__bottom-text">
                                    <p>&copy; Copyright Ficus International &mdash; Propelled by <a href="https://www.rccsglobal.com" target="_blank">Royal Crown Consultancy Services</a></p>
                                </div>
                                <div class="footer-one__bottom-social-links">
                                    <ul>
                                        <li><a href="https://x.com/FicusIntl" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="https://www.facebook.com/share/1JcoEmGGfT/" target="_blank"><i class="fab fa-facebook"></i></a></li>
                                        <li><a href="https://www.linkedin.com/company/ficus-international/" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                                        <li><a href="https://www.instagram.com/ficusinternational?igsh=MXA5dHowOXFjYnBlcg==" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

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
    <script src="assets/js/agriox.js"></script>
    <script src="assets/vendors/toolbar/js/js.cookie.min.js"></script>
    <script src="assets/vendors/toolbar/js/jQuery.style.switcher.min.js"></script>
    <script src="assets/vendors/toolbar/js/toolbar.js"></script>
</body>
</html>
