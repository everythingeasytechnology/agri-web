<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us | Ficus International</title>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicons/favicon-16x16.png" />
    <link rel="manifest" href="assets/images/favicons/site.webmanifest" />
    <meta name="description" content="Contact Ficus International — reach us for agro commodity sourcing, supply, and export enquiries." />
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
        /* Mobile nav icons — force perfect circle on both phone & email */
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

        /* Our Offices — clean 2-per-row CSS grid */
        .contact-page__contact-info-list ul {
            display: grid !important;
            grid-template-columns: 1fr 1fr;
            gap: 30px 0;
            align-items: start;
            flex-wrap: unset;
        }
        .contact-page__contact-info-list ul li {
            width: auto !important;
            margin-left: 0 !important;
            margin-bottom: 0 !important;
            padding-right: 40px !important;
            border-right: 1px solid #eceae0 !important;
        }
        /* Remove right border from 2nd and 4th items (right column) */
        .contact-page__contact-info-list ul li:nth-child(2n) {
            border-right: none !important;
            padding-right: 0 !important;
        }
        @media (max-width: 640px) {
            .contact-page__contact-info-list ul {
                grid-template-columns: 1fr;
            }
            .contact-page__contact-info-list ul li {
                border-right: none !important;
                padding-right: 0 !important;
            }
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
                        <li>Contact</li>
                    </ul>
                    <h2>Contact Us</h2>
                </div>
            </div>
        </section>

        <!-- ==================== CONTACT FORM ==================== -->
        <section class="contact-page">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-4">
                        <div class="contact-page__left">
                            <div class="sec-title">
                                <div class="icon"><img src="assets/images/resources/sec-title-icon1.png" alt=""></div>
                                <span class="sec-title__tagline">Reach Out</span>
                                <h2 class="sec-title__title">Let's Discuss Your  <br>Requirements</h2>
                            </div>
                            <p class="contact-page__left-text">We specialize in sourcing quality products from their origin and delivering them to global markets. Tell us your requirements, and we'll guide the process from start to finish.</p>
                            <div class="contact-page__social-link" style="margin-top: 25px;">
                                <ul>
                                    <li><a href="https://www.facebook.com/share/1JcoEmGGfT/" target="_blank"><i class="fab fa-facebook"></i></a></li>
                                    <li><a href="https://x.com/FicusIntl" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="https://www.instagram.com/ficusinternational?igsh=MXA5dHowOXFjYnBlcg==" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                    <li><a href="https://www.linkedin.com/company/ficus-international/" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8 col-lg-8">
                        <div class="contact-page__right">
                            <?php
                            $contact_status = $_GET['status'] ?? '';
                            if ($contact_status === 'success'): ?>
                            <div style="background:#f0fdf4;border:1px solid #86efac;color:#15803d;padding:14px 18px;border-radius:8px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:14px">
                                <i class="fas fa-check-circle" style="font-size:18px"></i>
                                <span><strong>Message sent!</strong> Thank you for reaching out. We'll get back to you shortly.</span>
                            </div>
                            <?php elseif ($contact_status === 'error'): ?>
                            <div style="background:#fff1f1;border:1px solid #fca5a5;color:#b91c1c;padding:14px 18px;border-radius:8px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:14px">
                                <i class="fas fa-exclamation-circle" style="font-size:18px"></i>
                                <span>Please fill in all required fields (name, email, message) and try again.</span>
                            </div>
                            <?php endif; ?>
                            <form action="contact-submit.php" method="post" class="comment-one__form">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="comment-form__input-box">
                                            <input type="text" placeholder="Your name" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="comment-form__input-box">
                                            <input type="email" placeholder="Email address" name="email" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="comment-form__input-box">
                                            <input type="text" placeholder="Phone number" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="comment-form__input-box">
                                            <input type="text" placeholder="Subject" name="subject">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12">
                                        <div class="comment-form__input-box">
                                            <textarea name="message" placeholder="Write your message" required></textarea>
                                        </div>
                                        <button type="submit" class="thm-btn comment-form__btn">Send a Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== CONTACT INFO ==================== -->
        <section class="contact-page__contact-info clearfix">
            <div class="auto-container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="contact-page__contact-info-wrapper">
                            <div class="contact-page__contact-info-title">
                                <h2>Our Offices</h2>
                            </div>
                            <div class="contact-page__contact-info-list">
                                <ul style="flex-wrap:wrap;">
                                    <li>
                                        <div class="icon" style="display:inline-flex;align-items:center;justify-content:center;width:50px;height:50px;border-radius:50%;flex-shrink:0;"><i class="fas fa-map-marker-alt" style="font-size:20px;color:#fff;line-height:1;"></i></div>
                                        <div class="title">
                                            <span>India Office</span>
                                            <p>Karnal, Haryana, India</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="icon" style="display:inline-flex;align-items:center;justify-content:center;width:50px;height:50px;border-radius:50%;flex-shrink:0;"><i class="fas fa-map-marker-alt" style="font-size:20px;color:#fff;line-height:1;"></i></div>
                                        <div class="title">
                                            <span>Africa Office</span>
                                            <p>Plateux Vallon, Abidjan, Cote d'Ivoire</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="icon" style="display:inline-flex;align-items:center;justify-content:center;width:50px;height:50px;border-radius:50%;flex-shrink:0;"><i class="fas fa-envelope" style="font-size:20px;color:#fff;line-height:1;"></i></div>
                                        <div class="title">
                                            <span>Send Email</span>
                                            <p><a href="mailto:contact@ficusinternational.com">contact@ficusinternational.com</a></p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="icon phone" style="display:inline-flex;align-items:center;justify-content:center;width:50px;height:50px;border-radius:50%;flex-shrink:0;"><i class="fas fa-phone-alt" style="font-size:20px;color:#fff;line-height:1;"></i></div>
                                        <div class="title">
                                            <!-- <span>Call Anytime</span> -->
                                            <p><a href="tel:+919653530361">+91 96535 30361</a></p>
                                        </div>
                                    </li>
                                </ul>
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
    <script src="assets/vendors/jquery-validate/jquery.validate.min.js"></script>
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
