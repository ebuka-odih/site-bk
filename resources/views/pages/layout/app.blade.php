
<!DOCTYPE html>
<html lang="en-US">
<head>

    <title>{{ env("APP_NAME") }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="HandheldFriendly" content="true">
    <meta name="author" content="bslthemes" />

    <!-- switzer font css -->
    <link rel="stylesheet" href="fonts/css/switzer.css" type="text/css" media="all">
    <!-- font awesome css -->
    <link rel="stylesheet" href="fonts/css/font-awesome.min.css" type="text/css" media="all">
    <!-- bootstrap grid css -->
    <link rel="stylesheet" href="css/plugins/bootstrap-grid.css" type="text/css" media="all">
    <!-- swiper css -->
    <link rel="stylesheet" href="css/plugins/swiper.min.css" type="text/css" media="all">
    <!-- magnific popup -->
    <link rel="stylesheet" href="css/plugins/magnific-popup.css" type="text/css" media="all">
    <!-- plax css -->
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all">
    <style>
        .mil-top-panel .mil-top-menu > ul > li > a {
            color: #1c1c1c;
        }
        .mil-top-panel .mil-top-menu > ul > li.mil-active > a,
        .mil-top-panel .mil-top-menu > ul > li:hover > a {
            color: #03a6a6;
        }
        .mil-top-panel .mil-top-menu > ul > li.mil-has-children::after {
            border-color: #1c1c1c;
        }
        .mil-top-panel .mil-top-menu > ul > li.mil-has-children:hover::after,
        .mil-top-panel .mil-top-menu > ul > li.mil-has-children.mil-active::after {
            border-color: #03a6a6;
        }
    </style>

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

</head>

<body>


    <!-- wrapper -->
    <div id="smooth-wrapper" class="mil-wrapper" style="background-color: #1B1717">

        <!-- preloader -->
        <div class="mil-preloader">
            <div class="mil-load"></div>
            <p class="h2 mil-mb-30"><span class="mil-light mil-counter" data-number="100">100</span><span class="mil-light">%</span></p>
        </div>
        <!-- preloader end -->

        <!-- scroll progress -->
        <div class="mil-progress-track">
            <div class="mil-progress"></div>
        </div>
        <!-- scroll progress end -->

        <!-- back to top -->
        <div class="progress-wrap active-progress"></div>

        <!-- top panel end -->
        <div class="mil-top-panel mil-dark-2">
            <div class="container">
                <a href="{{ url('/') }}" class="mil-logo">
                    <img src="img/logo-color-soft.png" alt="Plax" width="83" height="32">
                </a>
                <nav class="mil-top-menu">
                    <ul>
                        <li class="mil-active">
                            <a href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="mil-has-children">
                            <a href="#.">Personal Banking</a>
                            <ul>
                                <li><a href="services.html">Banking Services</a></li>
                                <li><a href="register.html">Open an Account</a></li>
                                <li><a href="contact.html">Customer Support</a></li>
                            </ul>
                        </li>
                        <li class="mil-has-children">
                            <a href="#.">Corporate Banking</a>
                            <ul>
                                <li><a href="career.html">Business Solutions</a></li>
                                <li><a href="career-details.html">Commercial Lending</a></li>
                                <li><a href="price.html">Treasury Services</a></li>
                            </ul>
                        </li>
                        <li class="mil-has-children">
                            <a href="#.">Help &amp; Info</a>
                            <ul>
                                <li><a href="{{ url('/about') }}">About</a></li>
                                <li><a href="support.html">Support</a></li>
                                <li><a href="faq.html">FAQ</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <div class="mil-menu-buttons">
                    <a href="register.html" class="mil-btn mil-sm">Client Login</a>
                    <div class="mil-menu-btn">
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- top panel end -->

        <!-- content -->
       @yield('content')
        <!-- content end -->
    </div>
    <!-- wrapper end -->

    <!-- jquery js -->
    <script src="js/plugins/jquery.min.js"></script>
    <!-- swiper css -->
    <script src="js/plugins/swiper.min.js"></script>
    <!-- gsap js -->
    <script src="js/plugins/gsap.min.js"></script>
    <!-- scroll smoother -->
    <script src="js/plugins/ScrollSmoother.min.js"></script>
    <!-- scroll trigger js -->
    <script src="js/plugins/ScrollTrigger.min.js"></script>
    <!-- scroll to js -->
    <script src="js/plugins/ScrollTo.min.js"></script>
    <!-- magnific -->
    <script src="js/plugins/magnific-popup.js"></script>
    <!-- plax js -->
    <script src="js/main.js"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-125314689-11"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-125314689-11');
	</script>

	<!-- bslthemes.com buttons html begin -->
    <div class="bsl-popup" data-theme="plax" data-category="html">
        <div class="bsl-popup__buttons"></div>
        <div class="bsl-popup__content bsl-popup__content-related">
        <div class="bsl-popup__menu"></div>
        <div class="bsl-popup__tabs">
            <div class="bsl-popup__tab bsl-popup__tab-demo"></div>
            <div class="bsl-popup__tab bsl-popup__tab-all"></div>
            <div class="bsl-popup__tab bsl-popup__tab-related"></div>
            <div class="bsl-popup__tab bsl-popup__tab-version"></div>
        </div>
        </div>
        <div class="bsl-popup__content bsl-popup__content-help"></div>
    </div>
    <!-- bslthemes.com buttons html end -->

	<!-- bslthemes.com buttons assets begin -->
	<link rel="stylesheet" href="https://bslthemes.com/bslthms-advanced-btns/assets/style.css">
    <script src="https://bslthemes.com/bslthms-advanced-btns/assets/script.js"></script>
    <!-- bslthemes.com buttons assets end -->

</body>


<!-- Mirrored from html.bslthemes.com/plax-demo/home-5.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 09 Nov 2025 17:23:51 GMT -->
</html>
