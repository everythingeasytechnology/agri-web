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
        .product-description-toggle {
            border: 0;
            background: transparent;
            padding: 0;
            color: inherit;
            cursor: pointer;
            font: inherit;
            line-height: inherit;
        }
        .product-description-toggle:hover,
        .product-description-toggle:focus {
            color: var(--thm-primary);
        }
        .product-description-toggle:focus {
            outline: 2px solid var(--thm-primary);
            outline-offset: 4px;
        }
        .product-image-toggle {
            display: block;
            width: 100%;
            border: 0;
            background: transparent;
            padding: 0;
            cursor: pointer;
        }
        .product-image-toggle img {
            width: 100%;
        }
        .services-one__single-img::before,
        .services-one__single-img-inner::before {
            display: none !important;
        }
        .services-one__single:hover .services-one__single-img-inner img {
            -webkit-transform: scale(1) !important;
            transform: scale(1) !important;
        }
        .product-card-row {
            align-items: flex-start;
        }
        .product-card-description[hidden] {
            display: none !important;
        }
        .cta-one__right-btn .thm-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 160px;
            white-space: nowrap;
        }
        @media (max-width: 380px) {
            .cta-one__right-btn .thm-btn {
                padding-left: 32px;
                padding-right: 32px;
            }
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
                        <li>Products</li>
                    </ul>
                    <h2>Our Products</h2>
                </div>
            </div>
        </section>

        <!-- ==================== PRODUCTS INTRO ==================== -->
        <!-- <section class="about-one" style="padding-bottom: 0;">
            <div class="container">
                <div class="sec-title text-center">
                   
                    <span class="sec-title__tagline">Origin-Sourced Quality</span>
                    <h2 class="sec-title__title"> Agro Commodities</h2>
                    <p style="max-width:700px; margin: 0 auto 10px; font-size:16px; line-height:1.8;">Every product we offer is carefully sourced directly from its country of origin, ensuring the highest standards of quality, freshness, and authenticity. We supply businesses across international markets with a comprehensive range of agro commodities.</p>
                </div>
            </div>
        </section> -->

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
                <div class="row product-card-row">
                    <?php foreach ($products as $pi => $p):
                        $icon  = $icons[$pi % 4];
                        $anim  = ($pi % 4 < 2) ? 'fadeInLeft' : 'fadeInRight';
                        $delay = ($pi % 2) * 100;
                        $desc_id = 'product-desc-' . $section_index . '-' . $pi;
                        $img   = $p['image_url'] ?: 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=280&fit=crop&auto=format&q=80';
                    ?>
                    <div class="col-xl-3 col-lg-6 wow <?= $anim ?>" data-wow-delay="<?= $delay ?>ms" data-wow-duration="1000ms">
                        <div class="services-one__single">
                            <div class="services-one__single-img">
                                <div class="services-one__single-img-inner">
                                    <button
                                        type="button"
                                        class="product-description-toggle product-image-toggle"
                                        aria-expanded="false"
                                        aria-controls="<?= $desc_id ?>"
                                    >
                                        <img src="<?= html_escape($img) ?>" alt="<?= html_escape($p['name']) ?>" />
                                    </button>
                                </div>
                            </div>
                            <div class="services-one__single-content text-center">
                                <!-- <div class="services-one__single-img-icon"><i class="fas <?= $icon ?>" style="font-size:38px"></i></div> -->
                                <h3>
                                    <button
                                        type="button"
                                        class="product-description-toggle"
                                        aria-expanded="false"
                                        aria-controls="<?= $desc_id ?>"
                                    ><?= html_escape($p['name']) ?></button>
                                </h3>
                                <p id="<?= $desc_id ?>" class="product-card-description" hidden><?= html_escape($p['description']) ?></p>
                                <!-- <a href="contact.php" class="read-more-btn"><i class="fas fa-arrow-right"></i></a> -->
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
                                    <h2>Interested in Our Products? <br> Get a Quote</h2>
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

    <!-- <a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fas fa-arrow-up"></i></a> -->

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
    <script>
        document.querySelectorAll('.product-description-toggle').forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                var controls = toggle.getAttribute('aria-controls');
                var description = document.getElementById(controls);

                if (!description) {
                    return;
                }

                var shouldOpen = description.hidden;

                document.querySelectorAll('.product-card-description').forEach(function (item) {
                    item.hidden = true;
                });

                document.querySelectorAll('.product-description-toggle').forEach(function (item) {
                    item.setAttribute('aria-expanded', 'false');
                });

                if (shouldOpen) {
                    description.hidden = false;
                    document.querySelectorAll('.product-description-toggle[aria-controls="' + controls + '"]').forEach(function (linkedToggle) {
                        linkedToggle.setAttribute('aria-expanded', 'true');
                    });
                }
            });
        });
    </script>
    <script src="assets/vendors/toolbar/js/js.cookie.min.js"></script>
    <script src="assets/vendors/toolbar/js/jQuery.style.switcher.min.js"></script>
    <script src="assets/vendors/toolbar/js/toolbar.js"></script>
</body>
</html>
