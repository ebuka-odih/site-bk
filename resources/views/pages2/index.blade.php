@extends('pages.layout.app_layout')

@section('content')
<div>
    <!--Main Slider Start-->
        <section class="main-slider main-slider-style3">
            <div class="swiper-container thm-swiper__slider" data-swiper-options='{"slidesPerView": 1, "loop": true,
                "effect": "fade",
                "pagination": {
                "el": "#main-slider-pagination",
                "type": "bullets",
                "clickable": true
                },
                "navigation": {
                "nextEl": "#main-slider__swiper-button-next",
                "prevEl": "#main-slider__swiper-button-prev"
                },
                "autoplay": {
                "delay": 5000
                }}'>

                <div class="swiper-wrapper">

                    <!--Start Single Swiper Slide-->
                    <div class="swiper-slide">
                        <div class="image-layer" style="background-image: url(assets/images/slides/slide-v3-1.jpg);">
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="main-slider-content">
                                        <div class="main-slider-content__inner">
                                            <div class="big-title">
                                                <h2>Empowering Your Financial Future</h2>
                                            </div>
                                            <div class="text">
                                                <p>
                                                    Secure your finances with innovative banking solutions tailored to your needs. Discover more with us.
                                                </p>
                                            </div>
                                            <div class="btns-box">
                                                <a class="btn-one" href="register/new-account.html">
                                                    <span class="txt">
                                                        Open An Account
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Single Swiper Slide-->

                    <!--Start Single Swiper Slide-->
                    <div class="swiper-slide">
                        <div class="image-layer" style="background-image: url(assets/images/slides/slide-v3-2.jpg);">
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="main-slider-content">
                                        <div class="main-slider-content__inner">
                                            <div class="big-title">
                                                <h2>Your Trust, Our Commitment</h2>
                                            </div>
                                            <div class="text">
                                                <p>
                                                    Reliable banking services focused on growth, security, and seamless transactions for your peace of mind.
                                                </p>
                                            </div>
                                            <div class="btns-box">
                                                <a class="btn-one" href="register/new-account.html">
                                                    <span class="txt">
                                                        Open An Account
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Single Swiper Slide-->

                    <!--Start Single Swiper Slide-->
                    <div class="swiper-slide">
                        <div class="image-layer" style="background-image: url(assets/images/slides/slide-v3-3.jpg);">
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="main-slider-content">
                                        <div class="main-slider-content__inner">
                                            <div class="big-title">
                                                <h2>Banking Made Simple and Secure</h2>
                                            </div>
                                            <div class="text">
                                                <p>
                                                    Manage your money effortlessly with our digital solutions, from anywhere, anytime.
                                                </p>
                                            </div>
                                            <div class="btns-box">
                                                <a class="btn-one" href="register/new-account.html">
                                                    <span class="txt">
                                                        Open An Account
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Single Swiper Slide-->

                </div>

                <!-- If we need navigation buttons -->
                <div class="main-slider__nav main-slider__nav--style3">
                    <div class="swiper-button-prev" id="main-slider__swiper-button-next">
                        <i class="icon-chevron left"></i>
                    </div>
                    <div class="swiper-button-next" id="main-slider__swiper-button-prev">
                        <i class="icon-chevron right"></i>
                    </div>
                </div>

            </div>
        </section>
        <!--Main Slider End-->

        <!--Start Accounts Style1 Area-->
        <section class="account-style1-area">
            <div class="container">
                <div class="sec-title text-center">
                    <h2>Let’s Think of saving Money</h2>
                    <div class="sub-title">
                        <p>Convenient banking options for you.</p>
                    </div>
                </div>
                <div class="row">
                    <!--Start Single Account Box Style1-->
                    <div class="col-xl-4 col-lg-4">
                        <div class="single-account-box-style1">
                            <div class="img-holder">
                                <img src="assets/images/resources/account-1.jpg" alt="">
                            </div>
                            <div class="text-holder">
                                <h3><a href="#">Savings Account</a></h3>
                                    <p>Open an account today and earn up to 8% annually, with no maintenance fees and easy access to your savings.</p>

                            </div>
                        </div>
                    </div>
                    <!--End Single Account Box Style1-->
                    <!--Start Single Account Box Style1-->
                    <div class="col-xl-4 col-lg-4">
                        <div class="single-account-box-style1">
                            <div class="img-holder">
                                <img src="assets/images/resources/account-2.jpg" alt="">
                            </div>
                            <div class="text-holder">
                                <h3><a href="#">Current Account</a></h3>
                                   <p>Enjoy flexible, unlimited transactions and easy online access with our hassle-free Current Account.</p>

                            </div>
                        </div>
                    </div>
                    <!--End Single Account Box Style1-->
                    <!--Start Single Account Box Style1-->
                    <div class="col-xl-4 col-lg-4">
                        <div class="single-account-box-style1">
                            <div class="img-holder">
                                <img src="assets/images/resources/account-3.jpg" alt="">
                            </div>
                            <div class="text-holder">
                                <h3><a href="#">Fixed Deposit Account</a></h3>
                                <p>Grow your funds securely with high returns and flexible terms. Start a Fixed Deposit today!</p>
                            </div>
                        </div>
                    </div>
                    <!--End Single Account Box Style1-->
                </div>



            </div>
        </section>
        <!--End Accounts Style1 Area-->

        <!--Start Banking Tab Area-->
        <section class="banking-tab-area">
            <div class="auto-container">
                <div class="banking-tab">

                    <!--Start Tabs Content Box-->
                    <div class="tabs-content-box">

                        <!--Tab-->
                        <div class="tab-content-box-item tab-content-box-item-active" id="trading">
                            <div class="banking-tab-content-item">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="banking-tab-img-box">
                                            <div class="banking-tab-img-box__bg"
                                                style="background-image: url(assets/images/resources/banking-tab-1.jpg);">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="banking-tab-text-box">
                                            <div class="inner-title">
                                                <h3>BEST SERVICES FOR BEST CLIENTS</h3>
                                                <h2>THE SECURE WAY TO : EASY BANKING</h2>
                                            </div>
                                            <div class="banking-tab-text-box__inner">
                                                <div class="text">
                                                    <p>Manage your money effortlessly with NESTOPRO INTERNATIONAL BANK range of personal banking solutions. From savings and checking accounts to personal loans, our services are designed to support your financial needs and help you reach your goals.</p>
                                                </div>
                                                <ul>
                                                    <li>Savings and Checking Accounts with competitive rates</li>
                                                    <li>On the other hand</li>
                                                    <li>Easy online and mobile banking access</li>
                                                    <li>Personalized support from our financial experts</li>
                                                    <li>Secure transactions and account management</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--End Tabs Content Box-->



                </div>
            </div>
        </section>
        <!--End Banking Tab Area-->


        <!--Start Benefits Area-->
        <section class="benefits-area">
            <div class="container">
                <div class="sec-title text-center">
                    <h2>Benefits for Account Holders</h2>
                    <div class="sub-title">
                        <p>We help businesses and customers achieve more.</p>
                    </div>
                </div>
                <ul class="row benefits-content text-center">

                    <!--Start Single Benefits Box Colum-->
                    <li class="col-xl-4 single-benefits-box-colum">
                        <div class="single-benefits-box">
                            <div class="icon">
                                <span class="icon-high"></span>
                            </div>
                            <div class="text">
                                <h3>Earn Interest up to 7%</h3>
                                <p>Grow your savings with competitive interest rates.</p>
                            </div>
                        </div>
                    </li>
                    <!--End Single Benefits Box Colum-->
                    <!--Start Single Benefits Box Colum-->
                    <li class="col-xl-4 single-benefits-box-colum">
                        <div class="single-benefits-box">
                            <div class="icon">
                                <span class="icon-notification"></span>
                            </div>
                            <div class="text">
                                <h3>Email Alerts</h3>
                                <p>Stay updated with real-time alerts on account activity.</p>
                            </div>
                        </div>
                    </li>
                    <!--End Single Benefits Box Colum-->
                    <!--Start Single Benefits Box Colum-->
                    <li class="col-xl-4 single-benefits-box-colum">
                        <div class="single-benefits-box">
                            <div class="icon">
                                <span class="icon-safebox"></span>
                            </div>
                            <div class="text">
                                <h3>Discounts on Locker</h3>
                                <p>Enjoy exclusive discounts on safe deposit locker rentals.</p>
                            </div>
                        </div>
                    </li>
                    <!--End Single Benefits Box Colum-->


                    <!--Start Single Benefits Box Colum-->
                    <li class="col-xl-4 single-benefits-box-colum">
                        <div class="single-benefits-box">
                            <div class="icon">
                                <span class="icon-credit-card-2"></span>
                            </div>
                            <div class="text">
                                <h3>International Debit Cards</h3>
                                <p>Access your money worldwide with secure debit cards.</p>
                            </div>
                        </div>
                    </li>
                    <!--End Single Benefits Box Colum-->
                    <!--Start Single Benefits Box Colum-->
                    <li class="col-xl-4 single-benefits-box-colum">
                        <div class="single-benefits-box">
                            <div class="icon">
                                <span class="icon-shield-1"></span>
                            </div>
                            <div class="text">
                                <h3>Provides Safety</h3>
                                <p>Experience advanced security features to protect your account.
                                </p>
                            </div>
                        </div>
                    </li>
                    <!--End Single Benefits Box Colum-->
                    <!--Start Single Benefits Box Colum-->
                    <li class="col-xl-4 single-benefits-box-colum">
                        <div class="single-benefits-box">
                            <div class="icon">
                                <span class="icon-paperless"></span>
                            </div>
                            <div class="text">
                                <h3>Mobile Banking</h3>
                                <p>Access your accounts anytime, anywhere with secure mobile banking features.</p>
                            </div>
                        </div>
                    </li>
                    <!--End Single Benefits Box Colum-->

                </ul>
            </div>
        </section>
        <!--End Benefits Area-->

        <!--Start Service Request Style2 Area-->
        <section class="service-request-style2-area">
            <div class="container">
                <div class="row">

                    <div class="col-xl-6">
                        <div class="service-request-style2-img-box">
                            <div class="service-request-style2-img-box__inner">
                                <img src="assets/images/resources/service-request-style2.jpg" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="service-request-style2-content-box">
                            <div class="pattern-bottom"
                                style="background-image: url(assets/images/shapes/service-request-style2-content-box-pattern.png);">
                            </div>
                            <div class="sec-title">
                                <h2>Online Emergency<br> Service Requests All In<br> One Place</h2>
                                <div class="sub-title">

                                </div>
                            </div>
                            <div class="service-request-style2-content-box__inner">
                                <ul>
                                    <li>
                                        <div class="single-service-request-style2-box">
                                            <div class="icon">
                                                <span class="icon-credit-card"></span>
                                            </div>
                                            <div class="title">
                                                <h3>
                                                    Credit & Debit Card<br> Related
                                                    <a href="#"><span class="icon-right-arrow"></span></a>
                                                </h3>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="single-service-request-style2-box">
                                            <div class="icon">
                                                <span class="icon-computer"></span>
                                            </div>
                                            <div class="title">
                                                <h3>
                                                    Mobile & Internet<br> Banking
                                                    <a href="#"><span class="icon-right-arrow"></span></a>
                                                </h3>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="single-service-request-style2-box">
                                            <div class="icon">
                                                <span class="icon-book"></span>
                                            </div>
                                            <div class="title">
                                                <h3>
                                                    Account & Personal<br> Details Change
                                                    <a href="#"><span class="icon-right-arrow"></span></a>
                                                </h3>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="single-service-request-style2-box">
                                            <div class="icon">
                                                <span class="icon-check-book"></span>
                                            </div>
                                            <div class="title">
                                                <h3>
                                                    Cheque Book / DD<br> Related
                                                    <a href="#"><span class="icon-right-arrow"></span></a>
                                                </h3>
                                            </div>
                                        </div>
                                    </li>
                                </ul>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!--End Service Request Style2 Area-->

        <!--Start Account Steps Area-->
        <section class="account-steps-area">
            <div class="container">
                <div class="sec-title text-center">
                    <h2>Your Account in Easy Steps</h2>
                    <div class="sub-title">
                        <p>Open your account with NESTOPRO INTERNATIONAL BANK </p>
                    </div>
                </div>
                <ul class="row account-steps__content">
                    <!--Start Single Account Steps Colum-->
                    <li class="col-xl-4 single-account-steps-colum text-center">
                        <div class="single-account-steps">
                            <div class="icon">
                                <div class="icon-inner">
                                    <span class="icon-consultation"></span>
                                </div>
                                <div class="counting">01</div>
                            </div>
                            <div class="text">
                                <h3>Fill Your Personal Details</h3>
                                <p>Provide your basic information quickly and securely to start the account opening process. Our streamlined process ensures accuracy and convenience.</p>
                            </div>
                        </div>
                    </li>
                    <!--End Single Account Steps Colum-->
                    <!--Start Single Account Steps Colum-->
                    <li class="col-xl-4 single-account-steps-colum text-center">
                        <div class="single-account-steps">
                            <div class="icon">
                                <div class="icon-inner">
                                    <span class="icon-file-1"></span>
                                </div>
                                <div class="counting">02</div>
                            </div>
                            <div class="text">
                                <h3>KYC Verification</h3>
                                <p>Complete a simple KYC verification to secure your account and meet compliance. This step safeguards both your identity and transactions.</p>
                            </div>
                        </div>
                    </li>
                    <!--End Single Account Steps Colum-->
                    <!--Start Single Account Steps Colum-->
                    <li class="col-xl-4 single-account-steps-colum text-center">
                        <div class="single-account-steps">
                            <div class="icon">
                                <div class="icon-inner">
                                    <span class="icon-investment"></span>
                                </div>
                                <div class="counting">03</div>
                            </div>
                            <div class="text">
                                <h3>Start Your Savings</h3>
                                <p>Begin saving with NESTOPRO INTERNATIONAL BANK flexible options. Our accounts support your financial goals with competitive interest rates and tailored savings solutions.</p>
                            </div>
                        </div>
                    </li>
                    <!--End Single Account Steps Colum-->
                </ul>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="account-steps-area__bottom-text">
                            <p>Start your account process today <a href="register/new-account.html" target="_blank">Open Account</a></p>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!--End Account Steps Area-->



        <!--Start Testimonials Style1 Area-->
        <section class="testimonials-style1-area">
            <div class="container">
                <div class="sec-title text-center">
                    <h2>Check Out Customer Feedback</h2>
                    <div class="sub-title">
                        <p>Pleasure to share some of our customers feedback.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="owl-carousel owl-theme thm-owl__carousel testimonials-style1-carousel owl-nav-style-one"
                            data-owl-options='{
                            "loop": true,
                            "autoplay": true,
                            "margin": 30,
                            "nav": true,
                            "dots": false,
                            "smartSpeed": 500,
                            "autoplayTimeout": 10000,
                            "navText": ["<span class=\"left icon-right-arrow\"></span>","<span class=\"right icon-right-arrow\"></span>"],
                            "responsive": {
                                    "0": {
                                        "items": 1
                                    },
                                    "768": {
                                        "items": 1
                                    },
                                    "992": {
                                        "items": 2
                                    },
                                    "1200": {
                                        "items": 2
                                    }
                                }
                            }'>

                            <!--Start Single Testimonials style1-->
                            <div class="single-testimonials-style1">
                                <div class="quote-box">
                                    <span class="icon-quote"></span>
                                </div>
                                <div class="customer-info">
                                    <div class="img-box">
                                        <img src="assets/images/testimonial/testimonial-v1-1.jpg" alt="">
                                    </div>
                                    <div class="title-box">
                                        <h3>Nathan Felix</h3>
                                        <span>Small Business Owner</span>
                                    </div>
                                </div>
                                <div class="inner">
                                    <div class="text-box">
                                        <h5 class="mb-2">NESTOPRO INTERNATIONAL BANK made saving so easy!</h5>
                                        <p>I’ve never felt more confident with my finances. Redwood Bank offers excellent interest rates, and their customer service is always prompt and helpful. Highly recommend!</p>
                                    </div>
                                    <div class="review-box">
                                        <ul>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--End Single Testimonials style1-->

                            <!--Start Single Testimonials style1-->
                            <div class="single-testimonials-style1">
                                <div class="quote-box">
                                    <span class="icon-quote"></span>
                                </div>
                                <div class="customer-info">
                                    <div class="img-box">
                                        <img src="assets/images/testimonial/testimonial-v1-2.jpg" alt="">
                                    </div>
                                    <div class="title-box">
                                        <h3>Nora Gorge</h3>
                                        <span>Freelancer</span>
                                    </div>
                                </div>
                                <div class="inner">
                                    <div class="text-box">
                                        <h5 class="mb-2">A bank that truly cares about customers.</h5>
                                        <p>NESTOPRO INTERNATIONAL BANK has been a game-changer for me. Their mobile app makes managing my accounts effortless, and I love the instant SMS alerts. It’s like having a bank in my pocket!</p>
                                    </div>
                                    <div class="review-box">
                                        <ul>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--End Single Testimonials style1-->

                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!--End Testimonials Style1 Area-->


    <section class="slogan-area slogan-area--style2">
            <div class="container">
                <div class="slogan-content-box slogan-content-box--style2">
                    <div class="slogan-content-box-bg"
                        style="background-image: url(assets/images/backgrounds/slogan-content-box-bg.jpg);"></div>
                    <div class="inner-title">
                        <h2>Empowering Financial Growth, One Account at a Time</h2>

                    </div>

                </div>
            </div>
        </section>

</div>

@endsection

