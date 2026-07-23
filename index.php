<?php
require_once __DIR__ . '/admin/db.php';
$home_products   = db_fetch_all('SELECT * FROM products ORDER BY created_at ASC LIMIT 8');
$home_blogs      = db_fetch_all("SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
$contact_status  = $_GET['status'] ?? '';
$home_reels      = db_fetch_all('SELECT * FROM instagram_reels ORDER BY sort_order ASC, id ASC');

function reel_embed_url(string $url): string {
    if (preg_match('#instagram\.com/(?:reel|p)/([A-Za-z0-9_-]+)#', $url, $m)) {
        return "https://www.instagram.com/reel/{$m[1]}/embed/";
    }
    return '';
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ficus International | Agro Commodity Sourcing & Supply</title>
    <!-- favicons Icons -->
    <link
      rel="apple-touch-icon"
      sizes="180x180"
      href="assets/images/favicons/apple-touch-icon.png"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="32x32"
      href="assets/images/favicons/favicon-32x32.png"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="16x16"
      href="assets/images/favicons/favicon-16x16.png"
    />
    <link rel="manifest" href="assets/images/favicons/site.webmanifest" />
    <meta
      name="description"
      content="Ficus International specializes in sourcing agro commodities directly from their countries of origin and delivering them to customers across global markets."
    />

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com/" />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;0,700;0,800;1,400;1,700&family=Lato:ital,wght@0,400;0,700;1,400;1,700&display=swap"
      rel="stylesheet"
    />

    <link
      rel="stylesheet"
      href="assets/vendors/bootstrap/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="assets/vendors/animate/animate.min.css" />
    <link rel="stylesheet" href="assets/vendors/animate/custom-animate.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />
    <link rel="stylesheet" href="assets/vendors/jarallax/jarallax.css" />
    <link
      rel="stylesheet"
      href="assets/vendors/jquery-magnific-popup/jquery.magnific-popup.css"
    />
    <link
      rel="stylesheet"
      href="assets/vendors/nouislider/nouislider.min.css"
    />
    <link
      rel="stylesheet"
      href="assets/vendors/nouislider/nouislider.pips.css"
    />
    <link rel="stylesheet" href="assets/vendors/odometer/odometer.min.css" />
    <link rel="stylesheet" href="assets/vendors/swiper/swiper.min.css" />
    <link rel="stylesheet" href="assets/vendors/icomoon-icons/style.css" />
    <link
      rel="stylesheet"
      href="assets/vendors/tiny-slider/tiny-slider.min.css"
    />
    <link rel="stylesheet" href="assets/vendors/reey-font/stylesheet.css" />
    <link
      rel="stylesheet"
      href="assets/vendors/owl-carousel/owl.carousel.min.css"
    />
    <link
      rel="stylesheet"
      href="assets/vendors/owl-carousel/owl.theme.default.min.css"
    />
    <link
      rel="stylesheet"
      href="assets/vendors/twentytwenty/twentytwenty.css"
    />

    <!-- template styles -->
    <link rel="stylesheet" href="assets/css/agriox.css" />
    <link rel="stylesheet" href="assets/css/agriox-rtl.css" />
    <link
      rel="stylesheet"
      id="jssMode"
      href="assets/css/modes/agriox-light.css"
    />
    <link rel="stylesheet" href="assets/vendors/toolbar/css/toolbar.css" />
    <style>
      /* Center and color icon in Global/Agro Commodity Experts overlay */
      .about-one__left-overlay {
        display: flex;
        align-items: center;
      }
      /* Center icons inside services icon circle */
      .services-one__single-img-icon {
        display: flex !important;
        align-items: center;
        justify-content: center;
      }
      /* Quality section icons already have flex from CSS — ensure FA icon inherits color */
      .providing-quality-one__content-box-list-item .icon i {
        color: #f1cf69;
        font-size: 32px;
      }
      /* Mobile nav icons same size */
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
        .main-slider.main-slider-one .image-layer,
        .main-slider.main-slider-one .swiper-slide-active .image-layer {
          -webkit-transform: scale(1) !important;
          transform: scale(1) !important;
          -webkit-transition: none !important;
          transition: none !important;
        }
        .main-slider-one,
        .main-slider-one .swiper-container,
        .main-slider-one .swiper-wrapper,
        .main-slider-one .swiper-slide {
          overflow: hidden;
        }
        .main-slider-one .image-layer {
          width: 100%;
          height: 100%;
        }
        .main-slider-one .swiper-wrapper,
        .main-slider-one .swiper-slide {
          -webkit-transition-duration: 0ms !important;
          transition-duration: 0ms !important;
        }
        .main-slider-one .image-layer {
          z-index: 1;
        }
        .main-slider-one .image-layer-overlay {
          z-index: 2;
        }
        .main-slider-one .container {
          z-index: 3;
        }
        .main-slider-one .main-slider-tagline,
        .main-slider-one .main-slider__title,
        .main-slider-one .main-slider__text,
        .main-slider-one .main-slider__button-box {
          opacity: 1 !important;
          -webkit-transform: none !important;
          transform: none !important;
          -webkit-transition: none !important;
          transition: none !important;
          -webkit-transition-delay: 0s !important;
          transition-delay: 0s !important;
        }
        .main-slider-one .main-slider-tagline::before {
          -webkit-transition: none !important;
          transition: none !important;
          -webkit-transition-delay: 0s !important;
          transition-delay: 0s !important;
        }
        .home-product-toggle {
          border: 0;
          background: transparent;
          padding: 0;
          color: inherit;
          cursor: pointer;
          font: inherit;
          line-height: inherit;
        }
        .home-product-toggle:hover,
        .home-product-toggle:focus {
          color: var(--thm-primary);
        }
        .home-product-toggle:focus {
          outline: 2px solid var(--thm-primary);
          outline-offset: 4px;
        }
        .home-product-description[hidden] {
          display: none !important;
        }
    </style>
  </head>

  <body>
    <div class="preloader">
      <img
        class="preloader__image"
        width="60"
        src="assets/images/loader.png"
        alt=""
      />
    </div>

    <div class="page-wrapper">
      <!-- ==================== HEADER ==================== -->
<?php include 'header.php'; ?>

      <div class="stricky-header stricked-menu main-menu">
        <div class="sticky-header__content"></div>
      </div>

      <!-- ==================== MAIN SLIDER ==================== -->
      <section class="main-slider main-slider-one">
        <div
          class="swiper-container thm-swiper__slider"
          data-swiper-options='{"slidesPerView": 1, "loop": true, "effect": "fade", "speed": 0, "pagination": {"el": "#main-slider-pagination", "type": "bullets", "clickable": true}, "navigation": {"nextEl": "#main-slider__swiper-button-next", "prevEl": "#main-slider__swiper-button-prev"}, "autoplay": {"delay": 7000}}'
        >
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <div
                class="image-layer"
                style="
                  background-image: url(assets/images/backgrounds/1.jpeg);
                "
              ></div>
              <div class="image-layer-overlay"></div>
              <div class="container">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="main-slider-inner">
                      <div class="main-slider__content">
                        <span class="main-slider-tagline"
                          >From Origin to Global Markets</span
                        >
                        <h2 class="main-slider__title">
                          Welcome to <br />
                          Ficus
                          <span
                            ><span class="leaf"
                              ></span
                            >International</span
                          >
                        </h2>
                        <p class="main-slider__text">
                          Sourcing premium agro commodities directly from
                          their<br />
                          countries of origin and delivering them worldwide.
                        </p>
                      </div>
                      <div class="main-slider__button-box">
                        <div class="arrow-icon">
                          <img
                            src="assets/images/icon/main-slider__button-arrow.png"
                            alt=""
                          />
                        </div>
                        <a href="products.php" class="thm-btn"
                          >Explore Products</a
                        >
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="swiper-slide">
              <div
                class="image-layer"
                style="
                  background-image: url(assets/images/backgrounds/13.jpeg);
                "
              ></div>
              <div class="image-layer-overlay"></div>
              <div class="container">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="main-slider-inner">
                      <div class="main-slider__content">
                        <span class="main-slider-tagline"
                          >Quality at the Source</span
                        >
                        <h2 class="main-slider__title">
                          Global <br />
                          Agro
                          <span
                            ><span class="leaf"
                              ><img
                                src="assets/images/resources/leaf.png"
                                alt="" /></span
                            >Commodity</span
                          >
                        </h2>
                        <p class="main-slider__text">
                          Strict quality standards with reliable delivery<br />
                          to customers across global markets.
                        </p>
                      </div>
                      <div class="main-slider__button-box">
                        <div class="arrow-icon">
                          <img
                            src="assets/images/icon/main-slider__button-arrow.png"
                            alt=""
                          />
                        </div>
                        <a href="about.php" class="thm-btn">About Us</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> 

            <div class="swiper-slide">
              <div
                class="image-layer"
                style="
                  background-image: url(assets/images/backgrounds/11.jpeg);
                "
              ></div>
              <div class="image-layer-overlay"></div>
              <div class="container">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="main-slider-inner">
                      <div class="main-slider__content">
                        <span class="main-slider-tagline"
                          >Seamless Supply Experience</span
                        >
                        <h2 class="main-slider__title">
                          Trusted <br />
                          Agro
                          <span
                            ><span class="leaf"
                              ><img
                                src="assets/images/resources/leaf.png"
                                alt="" /></span
                            >Sourcing</span
                          >
                        </h2>
                        <p class="main-slider__text">
                          Managing procurement, supply and logistics<br />
                          under one roof for your business needs.
                        </p>
                      </div>
                      <div class="main-slider__button-box">
                        <div class="arrow-icon">
                          <img
                            src="assets/images/icon/main-slider__button-arrow.png"
                            alt=""
                          />
                        </div>
                        <a href="contact.php" class="thm-btn">Get in Touch</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="swiper-pagination" id="main-slider-pagination"></div>
          <div class="main-slider__nav">
            <div
              class="swiper-button-prev"
              id="main-slider__swiper-button-next"
            >
              <i class="fas fa-arrow-right"></i>
            </div>
            <div
              class="swiper-button-next"
              id="main-slider__swiper-button-prev"
            >
              <i class="fas fa-arrow-right"></i>
            </div>
          </div>
        </div>
      </section>

      <!-- ==================== WHY CHOOSE FICUS INTERNATIONAL ==================== -->
      <section class="about-one">
        <div
          class="about-one__bg wow slideInRight"
          data-wow-delay="100ms"
          data-wow-duration="2500ms"
        >
          <img
            class="float-bob-y"
            src="assets/images/backgrounds/about-v1-bg.png"
            alt=""
          />
        </div>
        <div class="container">
          <div class="row">
            

            <div class="col-xl-6">
              <div class="about-one__content">
                <div class="sec-title">
                  <!-- <div class="icon">
                    <img
                      src="assets/images/resources/sec-title-icon1.png"
                      alt=""
                    />
                  </div> -->
                  <span class="sec-title__tagline">Why Choose Us</span>
                  <h2 class="sec-title__title">
                    Why Choose Ficus International
                  </h2>
                </div>
                <p class="about-one__content-text">
                  Our strength lies in sourcing products at their origin,
                  maintaining strict quality standards, and ensuring dependable
                  delivery worldwide. By managing procurement, supply, and
                  logistics under one roof, we provide our customers with a
                  reliable and efficient solution tailored to their needs.
                </p>
                <!-- <ul class="about-one__content-list">
                  <li>
                    <div class="icon">
                      <i class="fa fa-check-circle" aria-hidden="true"></i>
                    </div>
                    <div class="text">
                      <p>Direct origin sourcing for superior quality</p>
                    </div>
                  </li>
                  <li>
                    <div class="icon">
                      <i class="fa fa-check-circle" aria-hidden="true"></i>
                    </div>
                    <div class="text">
                      <p>Strict quality standards and compliance</p>
                    </div>
                  </li>
                  <li>
                    <div class="icon">
                      <i class="fa fa-check-circle" aria-hidden="true"></i>
                    </div>
                    <div class="text"><p>Dependable worldwide delivery</p></div>
                  </li>
                  <li>
                    <div class="icon">
                      <i class="fa fa-check-circle" aria-hidden="true"></i>
                    </div>
                    <div class="text">
                      <p>Procurement, supply &amp; logistics under one roof</p>
                    </div>
                  </li>
                </ul> -->
                <div
                  class="about-one__content-video-box"
                  style="margin-top: 30px"
                >
                  <a href="products.php" class="thm-btn"
                    >Explore Our Products</a
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ==================== THREE PILLARS ==================== -->
      <!-- <section class="features-one clearfix">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-4 wow fadeInUp animated">
              <div class="features-one__single">
                <div class="features-one__single-img">
                  <img
                    src="assets/images/feauters/features-v1-img1.jpg"
                    alt=""
                  />
                  <div class="features-one__single-title text-center">
                    <h3>
                      <a href="#">Origin <br />Sourcing</a>
                    </h3>
                  </div>
                </div>
                <a href="about.php" class="features-one__single__more"
                  ><i class="fas fa-arrow-right"></i
                ></a>
              </div>
            </div>

            <div class="col-xl-4 col-lg-4 wow fadeInUp animated">
              <div class="features-one__single">
                <div class="features-one__single-img">
                  <img
                    src="assets/images/feauters/features-v1-img2.jpg"
                    alt=""
                  />
                  <div class="features-one__single-title text-center">
                    <h3>
                      <a href="#">Global Export <br />Solutions</a>
                    </h3>
                  </div>
                </div>
                <a href="about.php" class="features-one__single__more"
                  ><i class="fas fa-arrow-right"></i
                ></a>
              </div>
            </div>

            <div class="col-xl-4 col-lg-4 wow fadeInUp animated">
              <div class="features-one__single style2 text-center">
                <div
                  class="features-one__single-bg"
                  style="
                    background-image: url(assets/images/backgrounds/features-one-single-bg.png);
                  "
                ></div>
                <div class="features-one__single-img">
                  <img
                    src="assets/images/feauters/features-v1-img3.jpg"
                    alt=""
                  />
                </div>
                <div class="features-one__single-title text-center">
                  <h3>
                    <a href="#">Quality <br />Assurance</a>
                  </h3>
                </div>
                <div class="button">
                  <a href="products.php" class="thm-btn">Discover More</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section> -->

      <!-- ==================== GLOBAL EXPORT SOLUTION ==================== -->
      <section
        class="video-one jarallax clearfix"
        data-jarallax
        data-speed="0.2"
        data-imgPosition="50% 0%"
        style="
          background-image: url(assets/images/backgrounds/video-one-bg.jpg);
        "
      >
        <div class="video-one-border"></div>
        <div class="video-one-border video-one-border-two"></div>
        <div class="video-one-border video-one-border-three"></div>
        <div class="video-one-border video-one-border-four"></div>
        <div class="video-one-border video-one-border-five"></div>
        <div class="video-one-border video-one-border-six"></div>
        <div class="container">
          <div class="row">
            <div class="col-xl-12">
              <div class="video-one__wrpper">
                <div class="video-one__left">
                  <div class="video-one__leaf"></div>
                  <h2 class="video-one__title">Global Export Solution</h2>
                  <p
                    style="
                      color: #fff;
                      margin-top: 15px;
                      font-size: 16px;
                      line-height: 1.8;
                    "
                  >
                    From origin sourcing to final delivery, Ficus International
                    manages every stage of the export process with precision and
                    professionalism. Our global capabilities and logistics
                    expertise enable us to deliver agro commodities efficiently
                    to businesses across international markets.
                  </p>
                  <div class="video-one__btn" style="margin-top: 25px">
                    <a href="contact.php" class="thm-btn">Get in Touch</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ==================== PRODUCTS SECTION ==================== -->
      <section class="services-one" id="products">
        <div
          class="services-one__bg wow slideInDown"
          data-wow-delay="100ms"
          data-wow-duration="2500ms"
        ></div>
        <div class="container">
          <div class="sec-title text-center">
            <!-- <div class="icon">
              <img src="assets/images/resources/sec-title-icon1.png" alt="" />
            </div> -->
            <span class="sec-title__tagline">What we offer</span>
            <h2 class="sec-title__title">Our Products</h2>
          </div>
          <div class="row">
            <?php
            $icons      = ['fa-seedling', 'fa-leaf', 'fa-chart-line', 'fa-box-open'];
            $animations = ['fadeInLeft', 'fadeInLeft', 'fadeInRight', 'fadeInRight'];
            $delays     = ['0ms', '100ms', '0ms', '100ms'];
            foreach ($home_products as $i => $p):
                $pos  = $i % 4;
                $icon = $icons[$pos];
                $anim = $animations[$pos];
                $dly  = $delays[$pos];
                $desc_id = 'home-product-desc-' . $i;
                $img  = $p['image_url']
                    ? (strpos($p['image_url'], 'http') === 0 ? $p['image_url'] : $p['image_url'])
                    : '';
            ?>
            <div class="col-xl-3 col-lg-6 wow <?= $anim ?>" data-wow-delay="<?= $dly ?>" data-wow-duration="1000ms" style="display:flex;flex-direction:column;">
              <div class="services-one__single" style="height:100%;display:flex;flex-direction:column;">
                <div class="services-one__single-img">
                  <div class="services-one__single-img-inner">
                    <?php if ($img): ?>
                    <img src="<?= html_escape($img) ?>" alt="<?= html_escape($p['name']) ?>" />
                    <?php else: ?>
                    <div style="width:100%;height:280px;display:flex;align-items:center;justify-content:center;background:#f0f5f0">
                      <i class="fas <?= $icon ?>" style="font-size:64px;color:#9ab87a;opacity:.35"></i>
                    </div>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="services-one__single-content text-center" style="flex:1;">
                  <!-- <div class="services-one__single-img-icon">
                    <i class="fas <?= $icon ?>" style="font-size: 38px"></i>
                  </div> -->
                  <h3>
                    <button
                      type="button"
                      class="home-product-toggle"
                      aria-expanded="false"
                      aria-controls="<?= $desc_id ?>"
                    ><?= html_escape($p['name']) ?></button>
                  </h3>
                  <p id="<?= $desc_id ?>" class="home-product-description" hidden><?= html_escape($p['description']) ?></p>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="text-center" style="margin-top: 40px">
            <a href="products.php" class="thm-btn">View All Products</a>
          </div>
        </div>
      </section>

      <!-- ==================== QUALITY SECTION ==================== -->
      <!-- <section class="providing-quality-one clearfix">
        <div class="providing-quality-one__bg">
          <img
            src="assets/images/backgrounds/providing-quality-one-bg.png"
            alt=""
          />
        </div>
        <div class="providing-quality-one__shape"></div>
        <div class="container-fullwidth">
          <div class="row">
            <div class="col-xl-6 col-lg-6 providing-quality-one__image-block clearfix">
              <div class="providing-quality-one__image__line float-bob-y"></div>
              <img
                src="assets/images/resources/providing-quality-v1-img.jpg"
                alt=""
              />
             
            </div>

            <div class="col-xl-6 col-lg-6">
              <div class="providing-quality-one__content-box">
                <div class="sec-title">
                 
                  <span class="sec-title__tagline">Our Commitment</span>
                  <h2 class="sec-title__title">
                    Quality Begins <br />at the Source
                  </h2>
                </div>
                <p style="margin-bottom: 25px; color: #dbe6d2">
                  We believe quality begins at the source. Every product is
                  carefully selected and supplied through trusted channels to
                  ensure consistency, reliability, and compliance with customer
                  expectations and international standards.
                </p>
                <ul class="providing-quality-one__content-box-list">
                  <li class="providing-quality-one__content-box-list-item">
                    <div class="icon">
                      <i
                        class="fas fa-seedling"
                        style="font-size: 32px; color: #f1cf69"
                      ></i>
                    </div>
                    <div class="text">
                      <h3>Trusted Channel Sourcing</h3>
                      <p>
                        Every product carefully selected through verified and
                        trusted procurement channels.
                      </p>
                    </div>
                  </li>
                  <li class="providing-quality-one__content-box-list-item">
                    <div
                      class="icon"
                      style="
                        display: flex;
                        align-items: center;
                        justify-content: center;
                      "
                    >
                      <i
                        class="fas fa-tractor"
                        style="font-size: 32px; color: #f1cf69"
                      ></i>
                    </div>
                    <div class="text">
                      <h3>International Standards Compliance</h3>
                      <p>
                        Ensuring consistency and compliance with customer
                        expectations and global standards.
                      </p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section> -->

      <!-- ==================== BLOG SECTION ==================== -->
      <!-- <section class="blog-one" id="blog">
        <div
          class="blog-one__bg wow slideInDown"
          data-wow-delay="100ms"
          data-wow-duration="2500ms"
        ></div>
        <div class="blog-one__shape"></div>
        <div class="container">
          <div class="sec-title text-center">
            <div class="icon">
              <img src="assets/images/resources/sec-title-icon1.png" alt="" />
            </div>
            <span class="sec-title__tagline">From the Blog</span>
            <h2 class="sec-title__title">News &amp; Articles</h2>
          </div>
          <div class="row">
            <?php if (empty($home_blogs)): ?>
            <div class="col-lg-12 text-center" style="padding:40px 0">
              <p style="color:#999;font-size:15px">No blog posts published yet.</p>
            </div>
            <?php else: foreach ($home_blogs as $i => $b):
              $n     = $i % 3 + 1;
              $delay = $i % 3 * 300;
              $img   = $b['image_url'] ?: "assets/images/blog/blog-v1-img{$n}.jpg";
              $link  = !empty($b['slug'])
                ? 'blog-post.php?slug=' . urlencode($b['slug'])
                : 'blog-post.php?id=' . $b['id'];
            ?>
            <div
              class="col-xl-4 col-lg-4 wow fadeInLeft"
              data-wow-delay="<?= $delay ?>ms"
              data-wow-duration="1500ms"
            >
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
                  </ul>
                  <h2><a href="<?= $link ?>"><?= html_escape($b['title']) ?></a></h2>
                  <p><?= html_escape($b['excerpt']) ?></p>
                </div>
              </div>
            </div>
            <?php endforeach; endif; ?>
          </div>
        </div>
      </section> -->

      <!-- ==================== INSTAGRAM REELS ==================== -->
      <?php if (!empty($home_reels)): ?>
      <section class="insta-reels-section" style="padding: 80px 0; background: #f8faf3;">
        <div class="container">

          <div class="sec-title text-center" style="margin-bottom: 48px;">
            <!-- <div class="icon"><img src="assets/images/resources/sec-title-icon1.png" alt="" /></div> -->
            <!-- <span class="sec-title__tagline">Follow Us</span> -->
            <h2 class="sec-title__title">Follow Us</h2>
            <p style="color:#777; font-size:15px; margin-top:10px; max-width:520px; margin-left:auto; margin-right:auto;">
              Stay connected with Ficus International on Instagram for updates, behind-the-scenes, and agro commodity insights.
            </p>
          </div>

          <div class="row justify-content-center">
            <?php foreach ($home_reels as $i => $reel):
              $reel_id = '';
              if (preg_match('#instagram\.com/(?:reel|p)/([A-Za-z0-9_-]+)#', $reel['reel_url'], $rm)) {
                $reel_id = $rm[1];
              }
              if (!$reel_id) continue;
              $delay = ($i % 4) * 150;
            ?>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay="<?= $delay ?>ms" data-wow-duration="1000ms">
              <div class="insta-reel-card">
                <div class="insta-reel-card__iframe-wrap">
                  <iframe
                    src="https://www.instagram.com/reel/<?= html_escape($reel_id) ?>/embed/"
                    allowtransparency="true"
                    allowfullscreen="true"
                    frameborder="0"
                    scrolling="no"
                    loading="lazy"
                  ></iframe>
                </div>
                <div class="insta-reel-card__footer">
                  <span class="insta-reel-card__handle"><i class="fab fa-instagram"></i> @ficusinternational</span>
                  <a href="<?= html_escape($reel['reel_url']) ?>" target="_blank" rel="noopener" class="insta-reel-card__watch">
                    <?= html_escape($reel['label']) ?> <i class="fas fa-external-link-alt"></i>
                  </a>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="text-center" style="margin-top: 40px;">
            <a href="https://www.instagram.com/ficusinternational/" target="_blank" rel="noopener"
               style="display:inline-flex;align-items:center;gap:10px;background:linear-gradient(135deg,#833ab4,#fd1d1d,#fcb045);color:#fff;padding:13px 32px;border-radius:30px;font-weight:700;font-size:15px;text-decoration:none;">
              <i class="fab fa-instagram" style="font-size:20px;"></i>
              Follow @ficusinternational
            </a>
          </div>

        </div>
      </section>

      <style>
        .insta-reel-card { border-radius:16px; overflow:hidden; background:#fff; box-shadow:0 4px 24px rgba(0,0,0,0.10); margin-bottom:24px; transition:transform 0.25s, box-shadow 0.25s; }
        .insta-reel-card:hover { transform:translateY(-6px); box-shadow:0 12px 36px rgba(0,0,0,0.16); }
        .insta-reel-card__iframe-wrap { position:relative; width:100%; aspect-ratio:9/16; overflow:hidden; background:#000; }
        .insta-reel-card__iframe-wrap iframe { position:absolute; top:0; left:0; width:100%; height:100%; border:none; display:block; }
        .insta-reel-card__footer { display:flex; align-items:center; justify-content:space-between; padding:12px 16px; background:#fff; border-top:1px solid #f0f0f0; }
        .insta-reel-card__handle { font-size:13px; color:#555; display:flex; align-items:center; gap:6px; }
        .insta-reel-card__handle .fab { background:linear-gradient(135deg,#833ab4,#fd1d1d,#fcb045); -webkit-background-clip:text; -webkit-text-fill-color:transparent; font-size:16px; }
        .insta-reel-card__watch { font-size:12px; font-weight:700; color:#88b04b; text-decoration:none; display:flex; align-items:center; gap:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:120px; }
        .insta-reel-card__watch:hover { color:#5a8a2a; text-decoration:none; }
      </style>
      <?php endif; ?>

      <!-- ==================== CONTACT SECTION ==================== -->
      <section class="contact-one" id="contact">
        <div class="container">
          <div class="sec-title text-center">
            <!-- <div class="icon">
              <img src="assets/images/resources/sec-title-icon1.png" alt="" />
            </div> -->
            <span class="sec-title__tagline">Contact With Us</span>
            <h2 class="sec-title__title">
              Get in Touch with <br />Ficus International
            </h2>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="contact-one__content">
                <p class="contact-one__text">
                  We are here to answer your enquiries and help you find the
                  right agro commodity solutions for your business. Reach out to
                  us through any of the channels below.
                </p>
                <ul class="list-unstyled ml-0 contact-one__lists">
                  
                     <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <strong>India:</strong> Karnal, Haryana
                  </li>
                  <li>
                    <i class="fas fa-phone"></i>
                    <a href="tel:+919653530361">+91 96535 30361</a>
                  </li>
               
                  <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <strong>Africa:</strong> 2 Plateaux Vallon, Abidjan, Ivory Coast
                  </li>
                   <li>
                    <i class="fas fa-phone"></i>
                    <a href="tel:+225 0150099753">+225 0150099753</a>
                  </li>
                  <li>
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:contact@ficusinternational.com"
                      >contact@ficusinternational.com</a
                    >
                  </li>
                </ul>

                <!-- <div style="margin-top: 30px">
                  <h4 style="margin-bottom: 15px">Follow Us</h4>
                  <div class="footer-one__bottom-social-links">
                    <ul
                      style="
                        list-style: none;
                        padding: 0;
                        display: flex;
                        gap: 12px;
                      "
                    >
                 <li>
                        <a
                          href="https://www.facebook.com/share/1JcoEmGGfT/"
                          target="_blank"
                          style="font-size: 22px; color: #3b5998"
                          ><i class="fab fa-facebook-square"></i
                        ></a>
                      </li> 
                     <li>
                        <a
                          href="https://www.instagram.com/ficusinternational?igsh=MXA5dHowOXFjYnBlcg=="
                          target="_blank"
                          style="font-size: 22px; color: #e1306c"
                          ><i class="fab fa-instagram"></i
                        ></a>
                      </li>
                      <li>
                        <a
                          href="https://www.linkedin.com/company/ficus-international/"
                          target="_blank"
                          style="font-size: 22px; color: #0077b5"
                          ><i class="fab fa-linkedin"></i
                        ></a>
                      </li>
                      <li>
                        <a
                          href="https://x.com/FicusIntl"
                          target="_blank"
                          style="font-size: 22px; color: #1da1f2"
                          ><i class="fab fa-twitter"></i
                        ></a>
                      </li> 
                    </ul>
                  </div>
                </div> -->

                <!-- <div class="contact-one__images" style="margin-top: 30px">
               
                  <img
                    src="assets/images/resources/contact-1-1.png"
                    alt=""
                    class="contact-one__images-1"
                  />
                  <img
                    src="assets/images/resources/contact-1-2.png"
                    alt=""
                    class="contact-one__images-2"
                  />
                </div> -->
              </div>
            </div>
            <div class="col-lg-6">
              <?php if ($contact_status === 'success'): ?>
              <div style="background:#e8f5e9;border:1px solid #a5d6a7;border-radius:8px;padding:16px 20px;margin-bottom:20px;display:flex;align-items:center;gap:12px;">
                  <i class="fas fa-check-circle" style="color:#388e3c;font-size:22px;flex-shrink:0;"></i>
                  <div>
                      <strong style="color:#1b5e20;display:block;margin-bottom:2px;">Message Sent!</strong>
                      <span style="color:#2e7d32;font-size:14px;">Thank you for reaching out. We will get back to you soon.</span>
                  </div>
              </div>
              <?php elseif ($contact_status === 'error'): ?>
              <div style="background:#fdecea;border:1px solid #f5c6c6;border-radius:8px;padding:16px 20px;margin-bottom:20px;display:flex;align-items:center;gap:12px;">
                  <i class="fas fa-exclamation-circle" style="color:#c62828;font-size:22px;flex-shrink:0;"></i>
                  <div>
                      <strong style="color:#b71c1c;display:block;margin-bottom:2px;">Something went wrong</strong>
                      <span style="color:#c62828;font-size:14px;">Please fill in all required fields with a valid email and try again.</span>
                  </div>
              </div>
              <?php endif; ?>

              <form action="contact-submit.php" method="post" class="contact-one__form comment-one__form">
                <input type="hidden" name="return_to" value="index">
                <div class="row">
                  <div class="col-xl-6">
                    <div class="comment-form__input-box">
                      <input type="text" placeholder="Your name *" name="name" required />
                    </div>
                  </div>
                  <div class="col-xl-6">
                    <div class="comment-form__input-box">
                      <input type="email" placeholder="Email address *" name="email" required />
                    </div>
                  </div>
                  <div class="col-xl-6">
                    <div class="comment-form__input-box">
                      <input type="text" placeholder="Phone number" name="phone" />
                    </div>
                  </div>
                  <div class="col-xl-6">
                    <div class="comment-form__input-box">
                      <input type="text" placeholder="Subject" name="subject" />
                    </div>
                  </div>
                  <div class="col-xl-12">
                    <div class="comment-form__input-box">
                      <textarea name="message" placeholder="Write your message *" required></textarea>
                    </div>
                  </div>
                </div>
                <button type="submit" class="thm-btn comment-form__btn">Send a Message</button>
              </form>
            </div>
          </div>
        </div>
      </section>

      <!-- ==================== FOOTER ==================== -->
  <?php include 'footer.php'; ?>
    </div>
    <!-- /.page-wrapper -->

    <div class="mobile-nav__wrapper">
      <div class="mobile-nav__overlay mobile-nav__toggler"></div>
      <div class="mobile-nav__content">
        <span class="mobile-nav__close mobile-nav__toggler"
          ><i class="fa fa-times"></i
        ></span>
        <div class="logo-box">
          <a href="index.php" aria-label="logo image"
            ><img
              src="assets/images/logo.jpeg"
              width="155"
              alt=""
          /></a>
        </div>
        <div class="mobile-nav__container"></div>
        <ul class="mobile-nav__contact list-unstyled">
          <li>
            <i class="fas fa-phone" style="font-size: 15px"></i>
            <a href="tel:+919653530361">+91 96535 30361</a>
          </li>
          <li>
            <i class="fas fa-envelope" style="font-size: 15px"></i>
            <a href="mailto:contact@ficusinternational.com"
              >contact@ficusinternational.com</a
            >
          </li>
        </ul>
        <div class="mobile-nav__top">
          <div class="mobile-nav__social">
            <a
              href="https://x.com/FicusIntl"
              target="_blank"
              class="fab fa-twitter"
            ></a>
            <a
              href="https://www.facebook.com/share/1JcoEmGGfT/"
              target="_blank"
              class="fab fa-facebook-square"
            ></a>
            <a
              href="https://www.linkedin.com/company/ficus-international/"
              target="_blank"
              class="fab fa-linkedin"
            ></a>
            <a
              href="https://www.instagram.com/ficusinternational?igsh=MXA5dHowOXFjYnBlcg=="
              target="_blank"
              class="fab fa-instagram"
            ></a>
          </div>
        </div>
      </div>
    </div>

    <div class="search-popup">
      <div class="search-popup__overlay search-toggler"></div>
      <div class="search-popup__content">
        <form action="#">
          <label for="search" class="sr-only">search here</label>
          <input type="text" id="search" placeholder="Search Here..." />
          <button type="submit" aria-label="search submit" class="thm-btn2">
            <i class="fa fa-search" aria-hidden="true"></i>
          </button>
        </form>
      </div>
    </div>

    <!-- <a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fas fa-arrow-up"></i ></a> -->

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

    <!-- template js -->
    <script src="assets/js/agriox.js"></script>
    <script>
      document.querySelectorAll('.home-product-toggle').forEach(function (toggle) {
        toggle.addEventListener('click', function () {
          var description = document.getElementById(toggle.getAttribute('aria-controls'));
          var isOpen = toggle.getAttribute('aria-expanded') === 'true';

          if (!description) {
            return;
          }

          toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
          description.hidden = isOpen;
        });
      });
    </script>

    <!-- toolbar js -->
    <script src="assets/vendors/toolbar/js/js.cookie.min.js"></script>
    <script src="assets/vendors/toolbar/js/jQuery.style.switcher.min.js"></script>
    <script src="assets/vendors/toolbar/js/toolbar.js"></script>
  </body>
</html>
