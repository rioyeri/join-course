
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>GPT Tulungagung</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <!-- Favicons -->
        <link href="{{ asset('assets/images/logo_mempelai.ico') }}" rel="icon">
        <link href="{{ asset('assets2/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="{{ asset('assets2/vendor/animate.css/animate.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets2/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets2/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
        <link href="{{ asset('assets2/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets2/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets2/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets2/vendor/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets2/vendor/owlcarousel/assets/owl.theme.default.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets2/vendor/feature/feature.css') }}" rel="stylesheet">

        <!-- Template Main CSS File -->
        <link href="{{ asset('assets2/css/style.css') }}" rel="stylesheet">

        <!-- =======================================================
        * Template Name: Shuffle - v4.3.0
        * Template URL: https://bootstrapmade.com/bootstrap-3-one-page-template-free-shuffle/
        * Author: BootstrapMade.com
        * License: https://bootstrapmade.com/license/
        ======================================================== -->
    </head>

    <body>
        @include('landingpage.header')

        <main id="main">
        <!-- ======= Breadcrumbs Section ======= -->
            <section class="breadcrumbs">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                <h2>Portfolio Details</h2>
                <ol>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="portfolio.html">Portfolio</a></li>
                    <li>Portfolio Details</li>
                </ol>
                </div>

            </div>
            </section><!-- Breadcrumbs Section -->

            <!-- ======= Portfolio Details Section ======= -->
            <section id="portfolio-details" class="portfolio-details">
            <div class="container">

                <div class="row gy-4">

                <div class="col-lg-8">
                    <div class="portfolio-details-slider swiper-container">
                    <div class="swiper-wrapper align-items-center">

                        <div class="swiper-slide">
                        <img src="assets/img/portfolio/portfolio-1.jpg" alt="">
                        </div>

                        <div class="swiper-slide">
                        <img src="assets/img/portfolio/portfolio-2.jpg" alt="">
                        </div>

                        <div class="swiper-slide">
                        <img src="assets/img/portfolio/portfolio-3.jpg" alt="">
                        </div>

                    </div>
                    <div class="swiper-pagination"></div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="portfolio-info">
                    <h3>Project information</h3>
                    <ul>
                        <li><strong>Category</strong>: Web design</li>
                        <li><strong>Client</strong>: ASU Company</li>
                        <li><strong>Project date</strong>: 01 March, 2020</li>
                        <li><strong>Project URL</strong>: <a href="#">www.example.com</a></li>
                    </ul>
                    </div>
                    <div class="portfolio-description">
                    <h2>This is an example of portfolio detail</h2>
                    <p>
                        Autem ipsum nam porro corporis rerum. Quis eos dolorem eos itaque inventore commodi labore quia quia. Exercitationem repudiandae officiis neque suscipit non officia eaque itaque enim. Voluptatem officia accusantium nesciunt est omnis tempora consectetur dignissimos. Sequi nulla at esse enim cum deserunt eius.
                    </p>
                    </div>
                </div>

                </div>

            </div>
            </section><!-- End Portfolio Details Section -->

        </main><!-- End #main -->

        @include('landingpage.footer')

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

        <!-- Vendor JS Files -->
        <script src="{{ asset('assets2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets2/vendor/glightbox/js/glightbox.min.js') }}"></script>
        <script src="{{ asset('assets2/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
        <script src="{{ asset('assets2/vendor/php-email-form/validate.js') }}"></script>
        <script src="{{ asset('assets2/vendor/purecounter/purecounter.js') }}"></script>
        <script src="{{ asset('assets2/vendor/swiper/swiper-bundle.min.js') }}"></script>
        <script src="{{ asset('assets2/vendor/waypoints/noframework.waypoints.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets2/vendor/owlcarousel/owl.carousel.min.js') }}"></script>

        <!-- Template Main JS File -->
        <script src="{{ asset('assets2/js/main.js') }}"></script>

        <script>
            $(document).ready(function(){
                $(".owl-carousel").owlCarousel({
                    "loop": true,
                    "freeDrag": true,
                    "dotsEach":true,
                    "autoplay":true,
                    "autoplayTimeout":3000,
                    "responsiveClass":true,
                    "responsive":{
                        0:{
                            items:1,
                            nav:true
                        },
                        1000:{
                            items:4,
                            nav:true,
                            loop:false
                        }
                    }
                });
            });
        </script>
    </body>
</html>
