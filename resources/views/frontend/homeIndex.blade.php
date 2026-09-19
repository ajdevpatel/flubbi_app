@extends('frontend.layoutIndex')


@section('bodyIndex')
    <!--==================================================-->
    <!-- Start Hero Area Style Two -->
    <!--==================================================-->
    <section class="hero_area style_two style_three d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- hero content -->
                    <div class="hero_content">
                        <h4>HASSLE FREE SOLUTION</h4>
                        <h1>Online Personal Loan <br>for all your Financial Needs!</h1>
                        <p>Get instant funds with just your bank statement. No paperwork, no waiting, no worries!</p>
                        <!-- slider button -->
                        <div class="slider_button d-flex gap-3">
                            <div class="hero_btn style_two">
                                <a href="{{ route('_accountDeleteIndex') }}" style="background-color: #dc3545;">Delete
                                    Account <i class="bi bi-trash"></i><span></span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-thumb">
                        <img src="assets/images/home_3/hero_thumb3.png" alt="">
                        <div class="hero_thumb_shape">
                            <img src="assets/images/home_3/hero3_dot_shape.png" alt="">
                        </div>
                        <!-- Hero Powerful Box -->
                        <div class="powerful_box bounce-animate4">
                            <div class="hero_power_check">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="hero_powerful_content">
                                <h4>Powerful Team</h4>
                                <p>36+ Members</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero_shape">
            <img src="assets/images/home_3/box.png" alt="">
        </div>
    </section>
    <!--==================================================-->
    <!-- End Hero Area Style Two -->
    <!--==================================================-->


    <!--==================================================-->
    <!-- Start About Area Style Two-->
    <!--==================================================-->
    <section class="about_area style_three"
        style="background: url(assets/images/home_3/about_bg.png); background-repeat: no-repeat; background-size: cover;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12">
                    <div class="about_thumb">
                        <img src="assets/images/home_3/about_thumb.png" alt="">
                        <div class="about_play style_two style_three">
                            <a data-aos="flip-left" class="banner-play-btn">
                                <div class="text-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="250.5" height="250.5"
                                        viewBox="0 0 250.5 250.5">
                                        <path d="M.25,125.25a125,125,0,1,1,125,125,125,125,0,0,1-125-125"
                                            id="e-path-35ee1b2"></path>
                                        <text>
                                            <textPath id="e-text-path-35ee1b2" href="#e-path-35ee1b2" startOffset="0%">
                                                * Digital Loan Consultancy * RBI compliant
                                            </textPath>
                                        </text>
                                    </svg>
                                </div>
                                <div class="like">
                                    <img src="assets/images/home_3/like_2.png" alt="">
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="section_title style_three">
                        <h4>ABOUT {{ strtoupper(config('web.store_data.app_name')) }}</h4>
                        <h1>Trusted Digital Loan</h1>
                        <h1>Consultancy Platform</h1>
                        <p>
                            {{ config('web.store_data.app_name') }} is a digital loan-consultancy platform dedicated to
                            simplifying
                            access to personal loans through a transparent, secure, and user-friendly
                            process. We connect borrowers exclusively with RBI-registered banks and NBFCs,
                            ensuring safe, compliant, and responsible financial assistance.
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="about-icon_box style_two">
                                <div class="about_icon">
                                    <img src="assets/images/home_two/about_icon1.png" alt="">
                                </div>
                                <div class="about_content style_two">
                                    <h3>Loan Eligibility & Advisory</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="about-icon_box style_two">
                                <div class="about_icon">
                                    <img src="assets/images/home_two/about_icon2.png" alt="">
                                </div>
                                <div class="about_content style_two">
                                    <h3>Secure & Guided Applications</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="about_shape two style_five bounce-animate ">
            <img src="assets/images/home_3/about_shape2.png" alt="">
        </div>
        <div class="about_shpe-three bounce-animate ">
            <img src="assets/images/home_3/about_shape.png" alt="">
        </div>
        <div class="about_shpe-four bounce-animate2">
            <img src="assets/images/home_3/about_shape_3.png" alt="">
        </div>
    </section>
    <!--==================================================-->
    <!-- End About Area Style Two-->
    <!--==================================================-->


    <!--==================================================-->
    <!-- Start Marquee Area Style Two-->
    <!--==================================================-->
    <div class="marquee_area style_two">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="slide-har st1">
                        <div class="box">
                            <div class="item">
                                <h4 class="d-flex align-items-center"><a href="#"><img class="marqee_img_left"
                                            src="assets/images/home_3/marqee_star.png" alt=""><span>Instant Loan
                                            Approval</span><span class="icon-img-50 ml-40"><img
                                                src="assets/images/home_3/marqee_star.png" alt=""></span></a></h4>
                            </div>
                            <div class="item">
                                <h4 class="d-flex align-items-center"><a href="#"><span>Low Interest Rates</span><span
                                            class="icon-img-50"><img src="assets/images/home_3/marqee_star.png"
                                                alt=""></span></a></h4>
                            </div>
                            <div class="item">
                                <h4 class="d-flex align-items-center"><a href="#"><span>Minimal
                                            Documentation</span><span class="icon-img-50"><img
                                                src="assets/images/home_3/marqee_star.png" alt=""></span></a></h4>
                            </div>
                            <div class="item">
                                <h4 class="d-flex align-items-center"><a href="#"><span>Secure Digital
                                            Process</span><span class="icon-img-50"><img
                                                src="assets/images/home_3/marqee_star.png" alt=""></span></a></h4>
                            </div>
                            <div class="item">
                                <h4 class="d-flex align-items-center"><a href="#"><span>Flexible Repayment
                                            Options</span><span class="icon-img-50"><img
                                                src="assets/images/home_3/marqee_star.png" alt=""></span></a></h4>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--==================================================-->
    <!-- End  Marquee Area-->
    <!--==================================================-->


    <!--==================================================-->
    <!-- Start Service Area Style three -->
    <!--==================================================-->
    <section class="service_area style_two style_three">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- section title -->
                    <div class="section_title style_three style_four text-center ">
                        <h4>SERVICES WE PROVIDE</h4>
                        <h1>Explore Our Products</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="service_list2 owl-carousel">
                    <div class="col-lg-12">
                        <div class="service_single_item style_two style_three">
                            <div class="service_thumb">
                                <img src="assets/images/home_3/service_1.png" alt="">
                                <div class="service_icon">
                                    <a href="#"><img src="assets/images/home_3/service_icon.png"
                                            alt=""></a>
                                </div>
                            </div>
                            <div class="service_content">
                                <h4>LOAN</h4>
                                <h3>Personal Loan</h3>
                                <p>Seamlessly expedite extensible the business
                                    methodologies benchmark done</p>
                                <div class="service_btn">
                                    <a href="{{ route('_personalServiceIndex') }}">View Details <i
                                            class="flaticon flaticon-right-arrow"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="service_single_item style_two style_three">
                            <div class="service_thumb">
                                <img src="assets/images/home_3/service_2.png" alt="">
                                <div class="service_icon">
                                    <a href="#"><img src="assets/images/home_3/service_icon.png"
                                            alt=""></a>
                                </div>
                            </div>
                            <div class="service_content">
                                <h4>LOAN</h4>
                                <h3>Business Loan</h3>
                                <p>Seamlessly expedite extensible the business
                                    methodologies benchmark done</p>
                                <div class="service_btn">
                                    <a href="{{ route('_businessServiceIndex') }}">View Details <i
                                            class="flaticon flaticon-right-arrow"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="service_single_item style_two style_three">
                            <div class="service_thumb">
                                <img src="assets/images/home_3/service_3.png" alt="">
                                <div class="service_icon">
                                    <a href="#"><img src="assets/images/home_3/service_icon.png"
                                            alt=""></a>
                                </div>
                            </div>
                            <div class="service_content">

                                <h4>CARD</h4>
                                <h3>Credit Card</h3>
                                <p>Seamlessly expedite extensible the business
                                    methodologies benchmark done</p>
                                <div class="service_btn">
                                    <a href="service-details.html">View Details <i
                                            class="flaticon flaticon-right-arrow"></i></a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="service_shape_three style_four rotate">
            <img src="assets/images/home_3/service_shpe2.png" alt="">
        </div>
        <div class="service_shape_five bounce-animate2">
            <img src="assets/images/home_3/service_shape.png" alt="">
        </div>
        <div class="service_shape_three style_six bounce-animate4">
            <img src="assets/images/home_3/tir.png" alt="">
        </div>
        <div class="service_shape_seven bounce-animate">
            <img src="assets/images/home_3/boxs.png" alt="">
        </div>

    </section>
    <!--==================================================-->
    <!-- End Service Area Style Two-->
    <!--==================================================-->


    <!--==================================================-->
    <!-- Start Why Choose Us Area -->
    <!--==================================================-->
    <section class="why_choose_us">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="choose_thumb">
                        <img src="assets/images/home_3/choose_thumb.png" alt="">
                        <div class="choose_thumb_shpae bounce-animate">
                            <img src="assets/images/home_3/choose_dot.png" alt="">
                        </div>
                        <div class="choose_thumb_shpae2 bounce-animate2">
                            <img src="assets/images/home_3/choose_dot2.png" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="choose_right">
                        <div class="section_title style_three pb-13">
                            <h4>WHY CHOOSE US</h4>
                            <h1>Responsible & Ethical</h1>
                            <h1>FinTech Solutions</h1>
                            <p>
                                We operate strictly as a technology and service provider — not a lender.
                                Our platform ensures complete transparency, data privacy, and ethical
                                fintech practices while guiding users toward the right financial partners.
                            </p>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="choose_list">
                                    <ul>
                                        <li><i class="bi bi-check-circle"></i> RBI-Regulated Lending Partners</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="choose_list">
                                    <ul>
                                        <li><i class="bi bi-check-circle"></i> Zero Unauthorized Data Access</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="choose_list">
                                    <ul>
                                        <li><i class="bi bi-check-circle"></i> Transparent & Fair Processes</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="choose_list">
                                    <ul>
                                        <li><i class="bi bi-check-circle"></i> Customer-First Loan Guidance</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- choose spane -->
                        <div class="choose_all_shape">
                            <div class="choose_one bounce-animate">
                                <img src="assets/images/home_3/box.png" alt="">
                            </div>
                            <div class="choose_two rotate">
                                <img src="assets/images/home_3/choose_rotete.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!--==================================================-->
    <!-- End Why Choose Us Area-->
    <!--==================================================-->


    <section class="feature_area inner_page">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- section title -->
                    <div class="section_title text-center">
                        <h4>FEATURED & BENEFITS</h4>
                        <h1>Everything You Need, One Platform</h1>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-3 col-md-6">
                    <!-- feature item -->
                    <div class="feature_item style_two">
                        <div class="feature_icon">
                            <i class="bi bi-lightning fs-1"></i>
                        </div>
                        <div class="feature_content">
                            <h3>Quick Process</h3>
                            <p>Fast and simple application with minimal steps.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <!-- feature item -->
                    <div class="feature_item style_two">
                        <div class="feature_icon">
                            <i class="bi bi-shield-lock fs-1"></i>
                        </div>
                        <div class="feature_content">
                            <h3>100% Secure Platform</h3>
                            <p>Your data and transactions are protected with advanced security.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <!-- feature item -->
                    <div class="feature_item style_two">
                        <div class="feature_icon">
                            <i class="bi bi-graph-down-arrow fs-1"></i>
                        </div>
                        <div class="feature_content">
                            <h3>Low Interest</h3>
                            <p>Affordable interest rates to help you save more.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <!-- feature item -->
                    <div class="feature_item style_two">
                        <div class="feature_icon">
                            <i class="bi bi-patch-check fs-1"></i>
                        </div>
                        <div class="feature_content">
                            <h3>Fast Approval</h3>
                            <p>Get funds approved and disbursed without long waiting periods.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="feature_shape bounce-animate-3">
            <img src="assets/images/home_one/arrow.png" alt="">
        </div>
    </section>


    <section class="brand_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 ">
                    <h2 class="brand_title pb-50">Our Reliable Partners</h2>
                </div>
            </div>
            <div class="row">
                <div class="brand_list owl-carousel">
                    <div class="col-lg-12">
                        <div class="single-brand-item">
                            <div class="brand-thumb">
                                <img src="assets/images/bank/01.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="single-brand-item">
                            <div class="brand-thumb">
                                <img src="assets/images/bank/02.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="single-brand-item">
                            <div class="brand-thumb">
                                <img src="assets/images/bank/03.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="single-brand-item">
                            <div class="brand-thumb">
                                <img src="assets/images/bank/04.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="single-brand-item">
                            <div class="brand-thumb">
                                <img src="assets/images/bank/05.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="single-brand-item">
                            <div class="brand-thumb">
                                <img src="assets/images/bank/06.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!--==================================================-->
    <!-- Start Testimonial Area Style Three-->
    <!--==================================================-->
    <section class="testimonial_area style_two style_three">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="section_title style_three">
                        <h4>CUSTOMERS TESTIMONIALS</h4>
                        <h1>What our customers say about us</h1>
                    </div>
                    <div class="counter-single-item style_two style_three style_four">
                        <div class="counter-content none">
                            <div class="counter-_number">
                                <h1 class="counter">4.98</h1>
                            </div>
                            <div class="counter_title">
                                <div class="counter-star">
                                    <i class="fa fa-star active"></i>
                                    <i class="fa fa-star active"></i>
                                    <i class="fa fa-star active"></i>
                                    <i class="fa fa-star active"></i>
                                    <i class="fa fa-star active"></i>
                                </div>
                                <h5 class="title_two">Avg. Clients Ratings</h5>
                            </div>
                        </div>
                        <div class="counter_shape">
                            <img src="assets/images/home_3/about_shape_3.png" alt="">
                        </div>
                    </div>
                    <div class="testi-list">
                        <ul>
                            <li><i class="bi bi-check"></i>100% Clients Satisfaction Gaurantee</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="testi_list2 owl-carousel">

                        @foreach ($testimonials as $testimonial)
                            <div class="col-lg-12">
                                <div class="testimonial_item style_two style_three">
                                    <div class="tesit-auothor">
                                        <div class="auothor">
                                            <div class="testi_quote">
                                                <img src="{{ asset('assets/images/home_3/quote.png') }}" alt="">
                                            </div>
                                        </div>
                                        <div class="bio">
                                            <h4 class="name">{{ $testimonial['name'] }}</h4>
                                            <h5 class="designation">{{ $testimonial['profession'] }}</h5>
                                        </div>
                                    </div>

                                    <div class="testimonal-content">
                                        <p>“{{ $testimonial['review'] }}”</p>

                                        <div class="testi-star">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fa fa-star {{ $i <= $testimonial['rating'] ? 'active' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>

                                    <div class="testi_item_shape">
                                        <img src="{{ asset('assets/images/home_3/testi_shape.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
        <div class="testi_shape_all">
            <div class="testi_shape_two dance3">
                <img src="assets/images/home_3/service_shpe2.png" alt="">
            </div>
            <div class="testi_shape_three dance">
                <img src="assets/images/home_3/service_shpe2.png" alt="">
            </div>
            <div class="testi_shape_four bounce-animate">
                <img src="assets/images/home_3/tir.png" alt="">
            </div>
        </div>
    </section>
    <!--==================================================-->
    <!-- End Testimonial Area Style Three -->
    <!--==================================================-->

    <!--==================================================-->
    <!-- Start Counter Area Style Three-->
    <!--==================================================-->
    <section class="counter_area style_two style_three">
        <div class="container">
            <div class="row style_bg_two">
                <div class="col-lg-4 col-md-6">
                    <div class="counter-single-item style_five">
                        <div class="counter-content none">
                            <div class="counter-_number">
                                <h1 class="counter">100</h1>
                                <span>%</span>
                            </div>
                            <div class="counter_title">
                                <h5>RBI Guideline <br>Compliance</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="counter-single-item style_five">
                        <div class="counter-content none">
                            <div class="counter-_number">
                                <h1 class="counter">15K+</h1>
                                <span></span>
                            </div>
                            <div class="counter_title">
                                <h5>Customers <br>served</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="counter-single-item style_five">
                        <div class="counter-content none">
                            <div class="counter-_number">
                                <h1 class="counter">24</h1>
                                <span>/7</span>
                            </div>
                            <div class="counter_title">
                                <h5>Customer Support <br>Assistance</h5>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!--==================================================-->
    <!-- Start Counter Area Style Three -->
    <!--==================================================-->

    <!--==================================================-->
    <!-- Start Contact Area -->
    <!--==================================================-->
    <section class="contact_area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="contact_thumb">
                        <img src="assets/images/home_3/contact.png" alt="">
                        <div class="call-do-action-info style_two">
                            <div class="call-do-social_icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="call_info">
                                <h3>{{ config('web.store_data.phone') }}</h3>
                            </div>
                        </div>
                        <div class="contact_thumb_shape bounce-animate">
                            <img src="assets/images/home_3/contact_shapes.png" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <!-- contact form box -->
                    <div class="contact-form-box style_two">
                        <!-- section title -->
                        <div class="section_title style_three style_four text-center ">
                            <h4>CONTACT US</h4>
                            <h1>Get In Touch with {{ config('web.store_data.app_name') }}</h1>
                        </div>
                        <form id="_inquiryModule" method="POST" action="{{ route('_contactusPost') }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-box">
                                        <input type="text" name="name" placeholder="Your Name" required>
                                        <span class="error-text text-danger" id="error-name"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-box">
                                        <input type="text" name="phone" id="phone" placeholder="Phone No"
                                            required>
                                        <span class="error-text text-danger" id="error-phone"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-box">
                                        <input type="email" name="mail" placeholder="E-Mail Address" required>
                                        <span class="error-text text-danger" id="error-mail"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-box message">
                                        <textarea name="message" id="message" cols="30" rows="5" placeholder="Write Message" required></textarea>
                                        <span class="error-text text-danger" id="error-message"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center contact-form">
                                    <button type="submit" id="contact-submit"><i class="far fa-thumbs-up"></i> Request
                                        Call
                                        Back</button>
                                </div>
                            </div>
                        </form>
                        <div class="contact_shape bounce-animate">
                            <img src="assets/images/home_3/contact_shape.png" alt="">
                        </div>
                        <div id="status"></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="contact_shape1 dance">
            <img src="assets/images/home_3/animate.png" alt="">
        </div>
        <div class="contact_shape2 dance2">
            <img src="assets/images/home_3/service_shpe2.png" alt="">
        </div>
    </section>
    <!--==================================================-->
    <!-- End Contact Area-->
    <!--==================================================-->


    <!--==================================================-->
    <!-- Start Blog Area style Two-->
    <!--==================================================-->
    <section class="blog_area style_two">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title style_three style_four text-center">
                        <h4>LATEST BLOG</h4>
                        <h1>Read Our Latest Insights from the</h1>
                        <h1>Latest Blog Articles</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="single-blog-box style_three">
                        <div class="single-blog-thumb">
                            <img src="assets/images/home_3/blog_1.png" alt="">
                            <div class="blog_category">
                                <a href="#">CREATIVE</a>
                            </div>
                        </div>
                        <div class="blog-content">
                            <div class="blog-title">
                                <h3><a href="blog-details.html">Globally disintermediate exten services resource</a></h3>
                            </div>
                            <p class="blog_text">Continually plagiarizes virtual web services
                                action items. Globally build</p>
                            <div class="meta-blog style_two">
                                <p>22 Jan, 2024<span class="solution"><i class="bi bi-heart"></i> 2 Comments</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-blog-box style_three">
                        <div class="single-blog-thumb">
                            <img src="assets/images/home_3/blog_2.png" alt="">
                            <div class="blog_category">
                                <a href="#">CREATIVE</a>
                            </div>
                        </div>
                        <div class="blog-content">
                            <div class="blog-title">
                                <h3><a href="blog-details.html">Consulting Industry changing Business Landscape</a></h3>
                            </div>
                            <p class="blog_text">Continually plagiarizes virtual web services
                                action items. Globally build</p>
                            <div class="meta-blog style_two">
                                <p>22 Jan, 2024<span class="solution"><i class="bi bi-heart"></i> 2 Comments</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-blog-box style_three">
                        <div class="single-blog-thumb">
                            <img src="assets/images/home_3/blog_3.png" alt="">
                            <div class="blog_category">
                                <a href="#">CREATIVE</a>
                            </div>
                        </div>
                        <div class="blog-content">
                            <div class="blog-title">
                                <h3><a href="blog-details.html">Sustainability Consulting for Business Planning</a></h3>
                            </div>
                            <p class="blog_text">Continually plagiarizes virtual web services
                                action items. Globally build</p>
                            <div class="meta-blog style_two">
                                <p>22 Jan, 2024<span class="solution"><i class="bi bi-heart"></i> 2 Comments</span></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!--==================================================-->
    <!-- End Blog Area style Two-->
    <!--==================================================-->
@endsection

@section('jsIndex')
    <script src="{{ asset('assets/app/contactIndex.js') }}"></script>
@endsection
