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

            <!-- navbar Section -->
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a href="{{ '/' }}">Home</a></li>
                    <li><a class="active" href="{{ '/fitur' }}">Fitur</a></li>
                    <li><a href="{{ '/about' }}">About</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
            <!-- End navbar -->
        </div>
    </header>
    <!-- End Header -->

    <main id="main">
        <!-- ======= Features Section ======= -->

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
                            <div class="col-md-7 text-center hero-text">
                                <h1 data-aos="fade-up" data-aos-delay="">Fitur Aplikasi  Kami</h1>
                                <p class="mb-5" data-aos="fade-up" data-aos-delay="100">
                                    Aplikasi Kami menawarkan berbagai fitur unggulan yang memudahkan pengguna dalam
                                    melakukan berbagai transaksi keuangan sehari-hari. Berikut adalah deskripsi dari
                                    fitur-fitur utama yang tersedia dalam aplikasi kami:
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section pb-0">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-4 me-auto">
                        <h2 class="mb-4">Laporan Keuangan</h2>
                        <p class="mb-4">
                            Dapatkan laporan keuangan lengkap dan detail setiap bulan.Laporan keuangan menyediakan
                            gambaran menyeluruh tentang arus keuangan Anda. Dengan laporan ini, Anda dapat melacak
                            pengeluaran, pendapatan, dan perkembangan keuangan
                            Anda dari waktu ke waktu.
                        </p>
                        <p><a href="#">Baca Lebih Lanjut</a></p>
                    </div>
                    <div class="col-md-6" data-aos="fade-left">
                        <img src="assetsLanding/img/undraw_projections_re_ulc6.svg" alt="Image" class="img-fluid" />
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-4 ms-auto order-2">
                        <h2 class="mb-4">Pencarian Transaksi</h2>
                        <p class="mb-4">Temukan transaksi tertentu dengan cepat melaui fitur pencarian yang mudah
                            digunakan.</p>
                        <p><a href="#">Baca Lebih Lanjut</a></p>
                    </div>
                    <div class="col-md-6" data-aos="fade-right">
                        <img src="assetsLanding/img/undraw_svg_3.svg" alt="Image" class="img-fluid" />
                    </div>
                </div>
            </div>
        </section>

        <section class="section pb-0">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-4 me-auto">
                        <h2 class="mb-4">Anggaran Bulanan</h2>
                        <p class="mb-4">Buat dan kelola anggaran bulanan Anda dengan mudah.Anggaran bulanan
                            memungkinkan Anda untuk mengatur batas pengeluaran dan memantau keuangan Anda agar tetap
                            sesuai dengan tujuan finansial Anda.</p>
                        <p><a href="#">Baca Lebih Lanjut</a></p>
                    </div>
                    <div class="col-md-6" data-aos="fade-left">
                        <img src="assetsLanding/img/undraw_online_banking_re_kwqh.svg" alt="Image" class="img-fluid" />
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-4 ms-auto order-2">
                        <h2 class="mb-4">Pengingat Pembayaran Tagihan</h2>
                        <p class="mb-4">
                            Membantu dalam mengatur dan mengingatkan pembayaran tagihan rutin anda. Fitur ini
                            memungkinkan anda untuk menjadwalkan pengingat untuk berbagai jenis tagihan seperti listrik,
                            air, internet, telepon, asuransi, dan lain-lain.
                        </p>
                        <p><a href="#">Baca Lebih Lanjut</a></p>
                    </div>
                    <div class="col-md-6" data-aos="fade-right">
                        <img src="assetsLanding/img/undraw_transfer_money_re_6o1h.svg" alt="Image" class="img-fluid" />
                    </div>
                </div>
            </div>
        </section>

        <!-- ======= Testimonials Section ======= -->
        <section class="section border-top border-bottom">
            <div class="container">
                <div class="row justify-content-center text-center mb-5">
                    <div class="col-md-4">
                        <h2 class="section-heading">Review Pengguna Aplikasi Kami</h2>
                    </div>
                </div>
                <div class="row justify-content-center text-center">
                    <div class="col-md-7">
                        <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="review text-center">
                                        <p class="stars">
                                            <span class="bi bi-star-fill"></span>
                                            <span class="bi bi-star-fill"></span>
                                            <span class="bi bi-star-fill"></span>
                                            <span class="bi bi-star-fill"></span>
                                            <span class="bi bi-star-fill"></span>
                                        </p>
                                        <h3>Pencatatan Otomatis yang Akurat</h3>
                                        <blockquote>
                                            <p>
                                                Saya suka bagaimana aplikasinya bisa mengintegrasikan akun bank saya dan
                                                secara otomatis mencatat semua transaksi saya. Hal ini sangat membantu
                                                karena saya tidak perlu lagi mencatat setiap pengeluaran secara manual.
                                                Semua transaksi saya dari berbagai akun terdata dengan akurat di satu
                                                tempat.
                                            </p>
                                        </blockquote>

                                        <p class="review-user">
                                            <img src="assetsLanding/img/person_1.jpg" alt="Image"
                                                class="img-fluid rounded-circle mb-3" />
                                            <span class="d-block"> <span class="text-black">Rani Nuraeni</span>,
                                                &mdash; Pengguna Aplikasi </span>
                                        </p>
                                    </div>
                                </div>
                                <!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="review text-center">
                                        <p class="stars">
                                            <span class="bi bi-star-fill"></span>
                                            <span class="bi bi-star-fill"></span>
                                            <span class="bi bi-star-fill"></span>
                                            <span class="bi bi-star-fill"></span>
                                            <span class="bi bi-star-fill muted"></span>
                                        </p>
                                        <h3>Laporan Keuangan yang Rinci</h3>
                                        <blockquote>
                                            <p>
                                                Fitur laporan keuangan bulanan adalah favorit saya. Setiap akhir bulan,
                                                saya mendapatkan laporan yang sangat rinci tentang pengeluaran dan
                                                pendapatan saya. Ini membantu saya memahami di mana uang saya digunakan
                                                dan
                                                membuat penyesuaian yang diperlukan untuk bulan berikutnya. Grafis dan
                                                statistik yang disediakan juga sangat informatif dan mudah dipahami.
                                            </p>
                                        </blockquote>

                                        <p class="review-user">
                                            <img src="assetsLanding/img/person_2.jpg" alt="Image"
                                                class="img-fluid rounded-circle mb-3" />
                                            <span class="d-block"> <span class="text-black">Agus Darmawan</span>,
                                                &mdash; Pengguna Aplikasi </span>
                                        </p>
                                    </div>
                                </div>
                                <!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="review text-center">
                                        <p class="stars">
                                            <span class="bi bi-star-fill"></span>
                                            <span class="bi bi-star-fill"></span>
                                            <span class="bi bi-star-fill"></span>
                                            <span class="bi bi-star-fill"></span>
                                            <span class="bi bi-star-fill muted"></span>
                                        </p>
                                        <h3>Antarmuka Pengguna yang Intuitif</h3>
                                        <blockquote>
                                            <p>
                                                Antarmuka pengguna Aplikasi sangat bersih dan mudah dinavigasi. Sebagai
                                                seseorang yang tidak terlalu teknis, saya menghargai bagaimana setiap
                                                fitur dan opsi mudah diakses dan digunakan. Tidak ada kurva pembelajaran
                                                yang curam, dan saya bisa mulai menggunakan aplikasi ini dengan segera.
                                            </p>
                                        </blockquote>

                                        <p class="review-user">
                                            <img src="assetsLanding/img/person_3.jpg" alt="Image"
                                                class="img-fluid rounded-circle mb-3" />
                                            <span class="d-block"> <span class="text-black">Siti Rahmawati</span>,
                                                &mdash; Pengguna Aplikasi </span>
                                        </p>
                                    </div>
                                </div>
                                <!-- End testimonial item -->
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Testimonials Section -->

        <!-- ======= CTA Section ======= -->
        <section class="section cta-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 me-auto text-center text-md-start mb-5 mb-md-0">
                        <h2>Starts Publishing Your Apps</h2>
                    </div>
                    <div class="col-md-5 text-center text-md-end">
                        <p>
                            <a href="#" class="btn d-inline-flex align-items-center"><i
                                    class="bx bxl-apple"></i><span>App store</span></a>
                            <a href="#" class="btn d-inline-flex align-items-center"><i
                                    class="bx bxl-play-store"></i><span>Google play</span></a>
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- End CTA Section -->
    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer class="footer" role="contentinfo">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h3>Tentang Aplikasi Kami</h3>
                    <p>
                        Uangku adalah aplikasi pencatatan keuangan pribadi yang dirancang untuk membantu pengguna
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
                            </ul>
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0">
                            <h3>Support</h3>
                            <ul class="list-unstyled">
                                <li><a href="#">Hubungi Kami</a></li>
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
