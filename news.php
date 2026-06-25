<?php require_once __DIR__ . '/admin/db.php'; $blogs = db_fetch_all("SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC"); ?>
<!DOCTYPE html>
<html lang="en">



<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Blog | Ficus International</title>
    <!-- favicons Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicons/favicon-16x16.png" />
    <link rel="manifest" href="assets/images/favicons/site.webmanifest" />
    <meta name="description" content="Agriox HTML Template For Agriculture Farming Services" />

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;0,700;0,800;1,400;1,700&family=Lato:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">



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

    <!-- template styles -->
	<link rel="stylesheet" href="assets/css/agriox.css" />

	<!-- RTL CSS -->
	<link rel="stylesheet" href="assets/css/agriox-rtl.css">


	<!-- mode css -->
	<link rel="stylesheet" id="jssMode" href="assets/css/modes/agriox-light.css">

    <!-- toolbar css -->
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

    <!-- style switcher -->
    <div class="style-switcher">
        <a href="#" id="switcher-toggler"><i class="fa fa-cog"></i></a>
        <h3>Layout Options</h3>
        <div class="language-feature">
            <button class="ltr-switcher" data-href="#googtrans(en|en)">LTR</button><!-- /.ltr-switcher -->
            <button class="rtl-switcher" data-href="#googtrans(en|ar)">RTL</button><!-- /.rtl-switcher -->
        </div><!-- /.language-feature -->
        <div class="layout-feature" id="colorMode">
            <a href="#" class="dark-switcher" data-theme="agriox-dark">Dark</a>
            <a href="#" class="light-switcher" data-theme="agriox-light">Light</a>
            <button class="boxed-switcher">Boxed</button><!-- /.ltr-switcher -->
        </div><!-- /.language-feature -->
    </div>
    <!-- end style switcher -->

    <div class="preloader">
        <img class="preloader__image" width="60" src="assets/images/loader.png" alt="" />
    </div> <!-- /.preloader -->
    <div class="page-wrapper">

 <?php include 'header.php'; ?>


        <div class="stricky-header stricked-menu main-menu">
            <div class="sticky-header__content">

            </div><!-- /.sticky-header__content -->
        </div><!-- /.stricky-header -->

        <!--Page Header Start-->
        <section class="page-header clearfix"
            style="background-image: url(assets/images/backgrounds/page-header-bg  );">
            <div class="container">
                <div class="page-header__inner text-center clearfix">
                    <ul class="thm-breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li>News</li>
                    </ul>
                    <h2>Blog</h2>
                </div>
            </div>
        </section>
        <!--Page Header End-->

        <!--Blog One Start-->
        <section class="blog-one blog-one--news">
            <div class="blog-one__bg wow slideInDown" data-wow-delay="100ms" data-wow-duration="2500ms"></div>
            <div class="container">
                <?php if (empty($blogs)): ?>
                <div class="row">
                    <div class="col-lg-12 text-center" style="padding: 60px 0">
                        <i class="fas fa-blog" style="font-size:48px;color:#ccc;display:block;margin-bottom:16px"></i>
                        <p style="color:#888;font-size:16px">No blog posts published yet. Check back soon.</p>
                    </div>
                </div>
                <?php else: ?>
                <div class="row">
                    <?php foreach ($blogs as $i => $b):
                        $n      = $i % 3 + 1;
                        $delay  = $i % 3 * 300;
                        $img    = $b['image_url'] ?: "assets/images/blog/blog-v1-img{$n}.jpg";
                        $link   = !empty($b['slug'])
                            ? 'blog-post.php?slug=' . urlencode($b['slug'])
                            : 'blog-post.php?id=' . $b['id'];
                    ?>
                    <div class="col-xl-4 col-lg-4 wow fadeInLeft" data-wow-delay="<?= $delay ?>ms" data-wow-duration="1500ms">
                        <div class="blog-one__single">
                            <div class="blog-one__single-img">
                                <img src="<?= html_escape($img) ?>" alt="<?= html_escape($b['title']) ?>" />
                                <div class="overlay-icon">
                                    <a href="<?= $link ?>"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                            <div class="blog-one__single-content">
                                <ul class="meta-info">
                                    <li><a href="#"><i class="far fa-user-circle"></i><?= html_escape($b['author']) ?></a></li>
                                    <li><a href="#"><i class="far fa-calendar-alt"></i><?= date('d M Y', strtotime($b['created_at'])) ?></a></li>
                                    <li><a href="#"><i class="fas fa-tag"></i><?= html_escape($b['category']) ?></a></li>
                                </ul>
                                <h2><a href="<?= $link ?>"><?= html_escape($b['title']) ?></a></h2>
                                <p><?= html_escape($b['excerpt']) ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <!--Blog One End-->


        <!--Start Footer One-->
  <?php include 'footer.php'; ?>
        <!--End Footer One-->


    </div><!-- /.page-wrapper -->



    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <!-- /.mobile-nav__overlay -->
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>

            <div class="logo-box">
                <a href="index.php" aria-label="logo image"><img src="assets/images/logo.jpeg"
                        width="155" alt="" /></a>
            </div>
            <!-- /.logo-box -->
            <div class="mobile-nav__container"></div>
            <!-- /.mobile-nav__container -->

            <ul class="mobile-nav__contact list-unstyled">
                <li>
                    <i class="fas fa-phone"></i>
                    <a href="mailto:needhelp@packageName__.com">needhelp@agriox.com</a>
                </li>
                <li>
                    <i class="fas fa-envelope"></i>
                    <a href="tel:666-888-0000">666 888 0000</a>
                </li>
            </ul><!-- /.mobile-nav__contact -->
            <div class="mobile-nav__top">
                <div class="mobile-nav__social">
                    <a href="https://x.com/FicusIntl" target="_blank" class="fab fa-twitter"></a>
                    <a href="https://www.facebook.com/share/1JcoEmGGfT/" target="_blank" class="fab fa-facebook-square"></a>
                    <a href="https://www.linkedin.com/company/ficus-international/" target="_blank" class="fab fa-linkedin"></a>
                    <a href="https://www.instagram.com/ficusinternational?igsh=MXA5dHowOXFjYnBlcg==" target="_blank" class="fab fa-instagram"></a>
                </div>
            </div><!-- /.mobile-nav__top -->
        </div>
        <!-- /.mobile-nav__content -->
    </div>
    <!-- /.mobile-nav__wrapper -->



    <div class="search-popup">
        <div class="search-popup__overlay search-toggler"></div>
        <!-- /.search-popup__overlay -->
        <div class="search-popup__content">
            <form action="#">
                <label for="search" class="sr-only">search here</label><!-- /.sr-only -->
                <input type="text" id="search" placeholder="Search Here..." />
                <button type="submit" aria-label="search submit" class="thm-btn2">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </form>
        </div>
        <!-- /.search-popup__content -->
    </div>
    <!-- /.search-popup -->



    <a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fas fa-arrow-up"></i></a>


    <script src="assets/vendors/jquery/jquery-3.5.1.min.js"></script>
    <script src="assets/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendors/jarallax/jarallax.min.js"></script>
    <script src="assets/vendors/jquery-ajaxchimp/jquery.ajaxchimp.min.js"></script>
    <script src="assets/vendors/jquery-appear/jquery.appear.min.js"></script>
    <script src="assets/vendors/jquery-circle-progress/jquery.circle-progress.min.js"></script>
    <script src="assets/vendors/jquery-magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets/vendors/jquery-validate/jquery.validate.min.js"></script>
    <script src="assets/vendors/nouislider/nouislider.min.js"></script>
    <script src="assets/vendors/odometer/odometer.min.js"></script>
    <script src="assets/vendors/swiper/swiper.min.js"></script>
    <script src="assets/vendors/tiny-slider/tiny-slider.min.js"></script>
    <script src="assets/vendors/wnumb/wNumb.min.js"></script>
    <script src="assets/vendors/wow/wow.js"></script>
    <script src="assets/vendors/isotope/isotope.js"></script>
    <script src="assets/vendors/countdown/countdown.min.js"></script>
    <script src="assets/vendors/owl-carousel/owl.carousel.min.js"></script>
    <script src="assets/vendors/twentytwenty/twentytwenty.js"></script>
    <script src="assets/vendors/twentytwenty/jquery.event.move.js"></script>
    <script src="assets/vendors/parallax/parallax.min.js"></script>
    <script src="assets/vendors/tilt.js/tilt.jquery.js"></script>


    <script src="http://maps.google.com/maps/api/js?key=AIzaSyATY4Rxc8jNvDpsK8ZetC7JyN4PFVYGCGM"></script>

    <!-- template js -->
    <script src="assets/js/agriox.js"></script>

    <!-- toolbar js -->
    <script src="assets/vendors/toolbar/js/js.cookie.min.js"></script>
    <script src="assets/vendors/toolbar/js/jQuery.style.switcher.min.js"></script>
    <script src="assets/vendors/toolbar/js/toolbar.lang.js"></script>
    <script src="../../translate.google.com/translate_a/elementa0d8.js?cb=googleTranslateElementInit"></script>
    <script src="assets/vendors/toolbar/js/toolbar.js"></script>


</body>



</html>