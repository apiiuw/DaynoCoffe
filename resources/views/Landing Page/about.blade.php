<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Kelompok 5 | Pencatatan Keuangan</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- Favicons -->
    <link href="assetsLanding/img/favicon.png" rel="icon" />
    <link href="assetsLanding/img/apple-touch-icon.png" rel="apple-touch-icon" />
    <link rel="icon" href="{{ asset('assetsLanding/img/1.png') }}" type="image/png">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="assetsLanding/vendor/aos/aos.css" rel="stylesheet" />
    <link href="assetsLanding/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assetsLanding/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
    <link href="assetsLanding/vendor/boxicons/css/boxicons.min.css" rel="stylesheet" />
    <link href="assetsLanding/vendor/swiper/swiper-bundle.min.css" rel="stylesheet" />

    <!-- Template Main CSS File -->
    <link href="assetsLanding/css/style.css" rel="stylesheet" />

    <!-- =======================================================
  * Template Name: SoftLand
  * Template URL: https://bootstrapmade.com/softland-bootstrap-app-landing-page-template/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top d-flex align-items-center">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo d-flex align-items-center">
                <h1><a href="index.html" class="align-items-center">Kelompok 5</a></h1>
            </div>

            <!-- navbar section -->
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a href="{{ '/' }}">Home</a></li>
                    <li><a href="{{ '/fitur' }}">Fitur</a></li>
                    <li><a class="active" href="{{ '/about' }}">About</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
            <!-- .navbar -->
        </div>
    </header>
    <!-- End Header -->

    <main id="main">
        <!-- ======= Single Blog Section ======= -->
        <section class="hero-section inner-page">
            <div class="wave">
                <svg width="1920px" height="265px" viewBox="0 0 1920 265" version="1.1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Apple-TV" transform="translate(0.000000, -402.000000)" fill="#FFFFFF">
                            <path
                                d="M0,439.134243 C175.04074,464.89273 327.944386,477.771974 458.710937,477.771974 C654.860765,477.771974 870.645295,442.632362 1205.9828,410.192501 C1429.54114,388.565926 1667.54687,411.092417 1920,477.771974 L1920,667 L1017.15166,667 L0,667 L0,439.134243 Z"
                                id="Path"></path>
                        </g>
                    </g>
                </svg>
            </div>

            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="row justify-content-center">
                            <div class="col-md-10 text-center hero-text">
                                <h1 data-aos="fade-up" data-aos-delay="">Tentang Aplikasi Kami</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======= Testimonials Section ======= -->
        <section class="section border-top border-bottom">
            <div class="container">
                <div class="row justify-content-center text-center mb-6">
                    <div class="col-md-6">
                        <h2 class="section-heading mb-6">Mengapa Memilih Aplikasi Kami?</h2>
                        <br /><br />
                    </div>
                </div>
                <div class="row justify-content-center text-center">
                    <div class="col-md-7">
                        <div class="review text-center">
                            <h3>Mudah Digunakan</h3>
                            <blockquote>
                                <p>
                                    Desain yang user-friendly memastikan Anda dapat mulai mencatat keuangan Anda tanpa
                                    hambatan. Baik Anda seorang pemula dalam hal manajemen keuangan atau sudah
                                    berpengalaman, Aplikasi ini akan menjadi teman setia dalam mengelola
                                    setiap transaksi.
                                </p>
                            </blockquote>
                            <br /><br />
                            <h3>Fitur Lengkap</h3>
                            <blockquote>
                                <p>Aplikasi ini dilengkapi dengan berbagai fitur yang mendukung kebutuhan keuangan Anda,
                                    seperti pencatatan transaksi harian, pelacakan anggaran, laporan keuangan yang mudah
                                    dipahami, dan pengingat pembayaran tagihan.</p>
                            </blockquote>
                            <br /><br />
                            <h3>Laporan Keuangan yang Komprehensif</h3>
                            <blockquote>
                                <p>Dapatkan laporan keuangan yang mendetail dan mudah dipahami untuk membantu Anda
                                    menganalisis kebiasaan pengeluaran dan membuat keputusan keuangan yang lebih baik.
                                </p>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Testimonials Section -->

        <!-- ======= Visi & Misi Section ======= -->
        <section class="section border-top border-bottom">
            <div class="container">
                <div class="row justify-content-center text-center mb-6">
                    <div class="col-md-6">
                        <h2 class="section-heading mb-6">Visi & Misi Kami</h2>
                        <br /><br />
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="visi">
                                <h2>Visi</h2>
                                <p>Menjadi aplikasi keuangan terpercaya yang membantu setiap individu mencapai
                                    stabilitas dan kesejahteraan finansial melalui pengelolaan keuangan yang bijak dan
                                    efisien.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="misi">
                                <h2>Misi</h2>
                                <p>
                                    Menyediakan alat yang tepat dan informasi yang akurat untuk membantu pengguna dalam
                                    mencatat dan mengelola keuangan mereka dengan mudah, aman, dan efektif. Kami
                                    berkomitmen untuk menghadirkan pengalaman pengguna terbaik
                                    dan terus berinovasi untuk memenuhi kebutuhan finansial masyarakat.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Visi & Misi Section -->

        <!-- ======= CTA Section ======= -->
        <section class="section cta-section" style="background-color: #ffffff">
            <div class="container">
                <div class="row justify-content-center text-center mb-6">
                    <div class="col-md-6">
                        <div class="col-md-12 me-auto text-center text-md-center mb-5 mb-md-0">
                            <h2>Bergabunglah Bersama Kami</h2>
                        </div>
                        <br /><br />
                        <p style="color: white">Mulai perjalanan keuangan Anda bersama kami dan rasakan kemudahan dalam
                            mengatur keuangan Anda. Unduh aplikasi Kami hari ini dan capai tujuan finansial Anda
                            dengan lebih mudah dan menyenangkan!</p>
                        <a href="#" class="btn btn-primary mt-3"
                            style="background-color: white; color: black; border: none"><b>Unduh Sekarang</b></a>
                    </div>
                </div>
            </div>
        </section>
        <!-- End CTA Section -->

        <!-- End Bergabunglah dengan Uangku Section -->
        <!-- ======= Footer ======= -->
        <footer class="footer" role="contentinfo">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h3>Tentang Aplikasi Kami</h3>
                        <p>
                            Aplikasi kami adalah aplikasi pencatatan keuangan pribadi yang dirancang untuk membantu pengguna
                            mengelola pengeluaran dan pendapatan mereka dengan lebih efektif dan efisien. Aplikasi ini
                            menawarkan berbagai fitur yang memungkinkan
                            pengguna untuk memantau arus kas, membuat anggaran, serta mendapatkan laporan keuangan yang
                            rinci.
                        </p>
                    </div>
                    <div class="col-md-7 ms-auto">
                        <div class="row site-section pt-0">
                            <div class="col-md-4 mb-4 mb-md-0">
                                <h3>Navigasi</h3>
                                <ul class="list-unstyled">
                                    <li><a href="{{ '/' }}">Home</a></li>
                                    <li><a href="{{ '/fitur' }}">Features</a></li>
                                    <li><a href="{{ '/about' }}">About</a></li>
                                    <li><a href="{{ '/contact' }}">Contact Us</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-4 mb-md-0">
                                <h3>Support</h3>
                                <ul class="list-unstyled">
                                    <li><a href="#">Hubungi Kami</a></li>
                                    <li><a href="#">FAQ</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-4 mb-md-0">
                                <h3>Temukan dan Ikuti Kami</h3>
                                <ul class="social">
                                    <a href="#"><span class="bi bi-twitter"></span></a>
                                    <a href="#"><span class="bi bi-facebook"></span></a>
                                    <a href="#"><span class="bi bi-instagram"></span></a>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assetsLanding/vendor/aos/aos.js"></script>
    <script src="assetsLanding/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assetsLanding/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assetsLanding/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assetsLanding/js/main.js"></script>
</body>

</html>
