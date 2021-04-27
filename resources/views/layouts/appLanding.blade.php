<!doctype html>
<html class="no-js" lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} | @yield('title')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('landing/img/favicon.ico') }}">

    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('landing/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/slicknav.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/style.css') }}">

    <!-- CSS inside -->
    @yield('styles')
</head>

<body>
<!-- Preloader Start -->
<div id="preloader-active">
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
            <div class="preloader-circle"></div>
            <div class="preloader-img pere-text">
                <img src="{{ asset('landing/img/logo/loder-logo.png') }}" alt="">
            </div>
        </div>
    </div>
</div>
<!-- Preloader Start -->
<header>
    <!-- Header Start -->
    <div class="header-area header-transparent">
        <div class="main-header ">
            <div class="header-top d-none d-lg-block">
                <div class="container-fluid">
                    <div class="col-xl-12">
                        <div class="row d-flex justify-content-between align-items-center">
                            <div class="header-info-left">
                                <ul>
                                    <li>+(123) 1234-567-8901</li>
                                    <li>info@domain.com</li>
                                    <li>Mon - Sat 8:00 - 17:30, Sunday - CLOSED</li>
                                </ul>
                            </div>
                            <div class="header-info-right">
                                <ul class="header-social">
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                                    <li> <a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom  header-sticky">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <!-- Logo -->
                        <div class="col-xl-2 col-lg-2 col-md-2">
                            <div class="logo">
                                <!-- logo-1 -->
                                <a href="{{ url('/') }}" class="big-logo"><img src="{{ asset('landing/img/logo/logo.png') }}" alt=""></a>
                                <!-- logo-2 -->
                                <a href="{{ url('/') }}" class="small-logo"><img src="{{ asset('landing/img/logo/loder-logo.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="col-xl-10 col-lg-10 col-md-10">
                            <!-- Main-menu -->
                            <div class="main-menu f-right d-none d-lg-block">
                                <nav>
                                    <ul id="navigation">
                                        <li><a href="{{ url('/') }}">Inicio</a></li>
                                        <li><a href="#">Fabricación</a></li>
                                        <li><a href="#">Servicios</a></li>
                                        <li><a href="#">Productos</a></li>
                                        <li><a href="#">Nosotros</a></li>
                                        @auth()
                                           {{-- <li><a href="#">Pages</a>
                                                <ul class="submenu">
                                                    <li><a href="#">Element</a></li>
                                                    <li><a href="#">Projects Details</a></li>
                                                    <li><a href="#">Services Details</a></li>
                                                </ul>
                                            </li>--}}
                                        @endauth
                                        <li><a href="#">Contacto</a></li>
                                        @guest
                                            <li><a href="{{ route('register') }}" > Registro</a></li>
                                            <li><a href="{{ route('login') }}" > Iniciar sesión</a></li>
                                        @else
                                            @can('access_dashboard')
                                                <li>
                                                    <a href="{{ route('dashboard.principal') }}">
                                                        Dashboard
                                                    </a>
                                                </li>
                                            @endcan
                                            <li><a href="#">{{ Auth::user()->name }}</a>
                                                <ul class="submenu">
                                                    <li>
                                                        <a href="{{ route('logout') }}"
                                                           onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                                            <i class="fa fa-sign-out"></i>
                                                            {{ __('Cerrar Sesión') }}
                                                        </a>
                                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                            @csrf
                                                        </form>
                                                    </li>
                                                </ul>
                                            </li>
                                        @endguest
                                    </ul>
                                </nav>
                            </div>
                        </div>

                        <!-- Mobile Menu -->
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
</header>
<main>
    <!-- Header Page Start-->
    <div class="slider-area ">
        <div class="single-slider hero-overly slider-height2 d-flex align-items-center" data-background="{{ asset('landing/img/hero/about.jpg') }}">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        @yield('header-page')
                        {{--<div class="hero-cap pt-100">
                            <h2>About us</h2>
                            <nav aria-label="breadcrumb ">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item"><a href="#">Product</a></li>
                                </ol>
                            </nav>
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Page End-->

    <!-- Content Page Start-->
    <div class="services-area1 section-padding">
        <div class="container">
            <!-- section tittle -->
            <div class="row">
                <div class="col-lg-12">
                    @yield('title-page')
                    {{--<div class="section-tittle mb-55">
                        <div class="front-text">
                            <h2 class="">Our Services</h2>
                        </div>
                        <span class="back-text">Services</span>
                    </div>--}}
                </div>
            </div>
            <div class="row">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- Content Page End-->
</main>
<footer>
    <!-- Footer Start-->
    <div class="footer-main">
        <div class="footer-area footer-padding">
            <div class="container">
                <div class="row  justify-content-between">
                    <div class="col-lg-4 col-md-4 col-sm-8">
                        <div class="single-footer-caption mb-30">
                            <!-- logo -->
                            <div class="footer-logo">
                                <a href="#"><img src="{{ asset('landing/img/logo/logo2_footer.png') }}" alt=""></a>
                            </div>
                            <div class="footer-tittle">
                                <div class="footer-pera">
                                    <p class="info1">Lorem ipsum dolor sit amet, consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Quick Links</h4>
                                <ul>
                                    <li><a href="#">About</a></li>
                                    <li><a href="#">Services</a></li>
                                    <li><a href="#">Projects</a></li>
                                    <li><a href="#">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-7">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Contact</h4>
                                <div class="footer-pera">
                                    <p class="info1">198 West 21th Street, Suite 721 New York,NY 10010</p>
                                </div>
                                <ul>
                                    <li><a href="#">Phone: +95 (0) 123 456 789</a></li>
                                    <li><a href="#">Cell: +95 (0) 123 456 789</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-8">
                        <div class="single-footer-caption mb-50">
                            <!-- Form -->
                            <div class="footer-form">
                                <div id="mc_embed_signup">
                                    <form target="_blank" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01" method="get" class="subscribe_form relative mail_part" novalidate="true">
                                        <input type="email" name="EMAIL" id="newsletter-form-email" placeholder=" Email Address " class="placeholder hide-on-focus" onfocus="this.placeholder = ''" onblur="this.placeholder = ' Email Address '">
                                        <div class="form-icon">
                                            <button type="submit" name="submit" id="newsletter-submit" class="email_icon newsletter-submit button-contactForm">
                                                REGÍSTRATE
                                            </button>
                                        </div>
                                        <div class="mt-10 info"></div>
                                    </form>
                                </div>
                            </div>
                            <!-- Map -->
                            <div class="map-footer">
                                <img src="{{ asset('landing/img/gallery/map-footer.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Copy-Right -->
                <div class="row align-items-center">
                    <div class="col-xl-12 ">
                        <div class="footer-copy-right">
                            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                Copyright &copy;<script>document.write(new Date().getFullYear());</script> Todos los derechos reservados por <a href="https://www.edesce.com/" target="_blank">EDESCE</a>
                                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End-->
</footer>

<!-- JS here -->

<!-- All JS Custom Plugins Link Here here -->
<script src="{{ asset('landing/js/vendor/modernizr-3.5.0.min.js') }}"></script>
<!-- Jquery, Popper, Bootstrap -->
<script src="{{ asset('landing/js/vendor/jquery-1.12.4.min.js') }}"></script>
<script src="{{ asset('landing/js/popper.min.js') }}"></script>
<script src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
<!-- Jquery Mobile Menu -->
<script src="{{ asset('landing/js/jquery.slicknav.min.js') }}"></script>

<!-- Jquery Slick , Owl-Carousel Plugins -->
<script src="{{ asset('landing/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('landing/js/slick.min.js') }}"></script>
<!-- Date Picker -->
<script src="{{ asset('landing/js/gijgo.min.js') }}"></script>
<!-- One Page, Animated-HeadLin -->
<script src="{{ asset('landing/js/wow.min.js') }}"></script>
<script src="{{ asset('landing/js/animated.headline.js') }}"></script>
<script src="{{ asset('landing/js/jquery.magnific-popup.js') }}"></script>

<!-- Scrollup, nice-select, sticky -->
<script src="{{ asset('landing/js/jquery.scrollUp.min.js') }}"></script>
<script src="{{ asset('landing/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('landing/js/jquery.sticky.js') }}"></script>

<!-- counter , waypoint -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
<script src="{{ asset('landing/js/jquery.counterup.min.js') }}"></script>

<!-- contact js -->
<script src="{{ asset('landing/js/contact.js') }}"></script>
<script src="{{ asset('landing/js/jquery.form.js') }}"></script>
<script src="{{ asset('landing/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('landing/js/mail-script.js') }}"></script>
<script src="{{ asset('landing/js/jquery.ajaxchimp.min.js') }}"></script>

<!-- Jquery Plugins, main Jquery -->
<script src="{{ asset('landing/js/plugins.js') }}"></script>
<script src="{{ asset('landing/js/main.js') }}"></script>

<!-- Jquery inside -->
@yield('scripts')

</body>
</html>