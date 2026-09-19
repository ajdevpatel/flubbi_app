<!DOCTYPE HTML>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="56x56" href="{{ asset('store/flubbi.png') }}">
    <link rel="shortcut icon" href="{{ asset('store/flubbi-favicon.webp') }}" type="image/x-icon">
    <title>Get Personal & Business {{ config('web.webapp.loan_word.meta_tag') }} Online - Flubbi</title>
    <meta name="keywords"
        content="Instant {{ config('web.webapp.loan_word.meta_tag') }} approvals, Online {{ config('web.webapp.loan_word.meta_tag') }} applications, Low-interest rates, Flexible repayment options, Financial solutions for startups, Personal {{ config('web.webapp.loan_word.meta_tag') }} for emergencies, Small business funding, {{ config('web.webapp.loan_word.meta_tag') }} comparison platforms, Digital finance solutions, Secured vs. unsecured {{ config('web.webapp.loan_word.meta_tag') }} s">
    <meta name="description"
        content="Flubbi offers quick personal and business {{ config('web.webapp.loan_word.meta_tag') }} with low-interest rates, easy documentation, and fast approval. Apply online today for instant funds!">
    <meta name="author" content="dev sutariya">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="canonical" href="{{ url()->full() }}" />

    @if (!empty(app('request')->input('google_domain_verification')) && app()->environment('production'))
        <meta name="google-site-verification" content="{{ app('request')->input('google_domain_verification') }}" />
    @endif
    @if (!empty(app('request')->input('facebook_domain_verification')) && app()->environment('production'))
        <meta name="facebook-domain-verification"
            content="{{ app('request')->input('facebook_domain_verification') }}" />
    @endif
    @if (!empty(app('request')->input('pinterest_domain_verification')) && app()->environment('production'))
        <meta name="p:domain_verify" content="{{ app('request')->input('pinterest_domain_verification') }}" />
    @endif

    @if ([] != config('web.assets.frontend.css'))
        @foreach (config('web.assets.frontend.css') as $k => $v)
            <link rel="stylesheet" href="{{ asset($v) }}">
        @endforeach
    @endif

    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

    @hasSection('styleIndex')
        @yield('styleIndex')
    @endif

    @hasSection('inlineStyleIndex')
        @yield('inlineStyleIndex')
    @endif

</head>

<body oncontextmenu="return false">

    <!--========= Prealoader ==============-->
    {{--  <div class="loading-screen" id="loading-screen">
        <span class="bar top-bar"></span>
        <span class="bar down-bar"></span>
        <div class="animation-preloader">
            <div class="spinner"></div>
            <div class="txt-loading">
                <span data-text-preloader="F" class="letters-loading">F</span>
                <span data-text-preloader="L" class="letters-loading">L</span>
                <span data-text-preloader="U" class="letters-loading">U</span>
                <span data-text-preloader="B" class="letters-loading">B</span>
                <span data-text-preloader="B" class="letters-loading">B</span>
                <span data-text-preloader="I" class="letters-loading">I</span>
            </div>
        </div>
    </div> --}}
    <!--========= End Prealoader ==============-->


    <!-- =======Start Topber Area Css -->

    <div class="topber_area style_two">
        <div class="container">
            <div class="row topber_upper align-items-center d-flex">
                <div class="col-lg-8">
                    <div class="header-address-info">
                        <p><span><i class="bi bi-envelope"></i></span> {{ config('web.store_data.support_mail') }}
                            <span class="right_info"><i class="fas fa-phone-alt"></i></span>
                            {{ config('web.store_data.phone') }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!--header top address-->
                    <div class="topber_right_social style_two">
                        <h2>FOLLOW US :</h2>
                        <ul>
                            <li><a href="{{ config('web.store_data.social_links.facebook') }}"><i
                                        class="fab fa-facebook-f"></i></a></li>
                            <li><a class="top-social-icon-left"
                                    href="{{ config('web.store_data.social_links.instagram') }}"><i
                                        class="fab fa-instagram"></i></a></li>
                            <li><a href="{{ config('web.store_data.social_links.threads') }}"><svg
                                        xmlns="http://www.w3.org/2000/svg" height="18" width="16"
                                        viewBox="0 0 448 512">
                                        <path fill="#ffffff"
                                            d="M331.5 235.7c2.2 .9 4.2 1.9 6.3 2.8c29.2 14.1 50.6 35.2 61.8 61.4c15.7 36.5 17.2 95.8-30.3 143.2c-36.2 36.2-80.3 52.5-142.6 53h-.3c-70.2-.5-124.1-24.1-160.4-70.2c-32.3-41-48.9-98.1-49.5-169.6V256v-.2C17 184.3 33.6 127.2 65.9 86.2C102.2 40.1 156.2 16.5 226.4 16h.3c70.3 .5 124.9 24 162.3 69.9c18.4 22.7 32 50 40.6 81.7l-40.4 10.8c-7.1-25.8-17.8-47.8-32.2-65.4c-29.2-35.8-73-54.2-130.5-54.6c-57 .5-100.1 18.8-128.2 54.4C72.1 146.1 58.5 194.3 58 256c.5 61.7 14.1 109.9 40.3 143.3c28 35.6 71.2 53.9 128.2 54.4c51.4-.4 85.4-12.6 113.7-40.9c32.3-32.2 31.7-71.8 21.4-95.9c-6.1-14.2-17.1-26-31.9-34.9c-3.7 26.9-11.8 48.3-24.7 64.8c-17.1 21.8-41.4 33.6-72.7 35.3c-23.6 1.3-46.3-4.4-63.9-16c-20.8-13.8-33-34.8-34.3-59.3c-2.5-48.3 35.7-83 95.2-86.4c21.1-1.2 40.9-.3 59.2 2.8c-2.4-14.8-7.3-26.6-14.6-35.2c-10-11.7-25.6-17.7-46.2-17.8H227c-16.6 0-39 4.6-53.3 26.3l-34.4-23.6c19.2-29.1 50.3-45.1 87.8-45.1h.8c62.6 .4 99.9 39.5 103.7 107.7l-.2 .2zm-156 68.8c1.3 25.1 28.4 36.8 54.6 35.3c25.6-1.4 54.6-11.4 59.5-73.2c-13.2-2.9-27.8-4.4-43.4-4.4c-4.8 0-9.6 .1-14.4 .4c-42.9 2.4-57.2 23.2-56.2 41.8l-.1 .1z">
                                        </path>
                                    </svg>
                                </a>
                            </li>
                            <li><a href="{{ config('web.store_data.social_links.linkedin') }}"><i
                                        class="fab fa-linkedin-in"></i></a></li>
                            <li><a href="{{ config('web.store_data.social_links.youtube') }}"><i
                                        class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- =======End Topber Area Css -->


    {{-- @if (!empty(app('request')->input('google_tag_manager')) && app()->environment('production'))
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ app("request")->input("google_tag_manager") }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif --}}

    <!--==================================================-->
    <!-- Start Header Area Style Three-->
    <!--==================================================-->
    <div class="consalt-header-area style_two style_three" id="sticky-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-2">
                    <div class="header-logo">
                        <a class="active_header" href="{{ route('_homeIndex') }}"><img src="{{ asset('assets/images/logo.png') }}"
                                width="150" alt="logo"></a>
                        <a class="active_sticky" href="{{ route('_homeIndex') }}"><img src="{{ asset('assets/images/logo.png') }}"
                                width="150" alt="logo"></a>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="header-menu">
                        <ul class="nav_scroll">
                            <li><a href="{{ route('_homeIndex') }}">Home</a></li>
                            <li><a href="{{ route('_aboutusPost') }}">About</a></li>
                            <li><a href="#">Services <span><i class="fas fa-angle-down"></i></span></a>
                                <ul class="sub_menu">
                                    <li><a href="{{ route('_personalServiceIndex') }}">Personal Loan</a></li>
                                    <li><a href="{{ route('_businessServiceIndex') }}">Business Loan</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('_contactusPost') }}">Contact</a></li>
                        </ul>

                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="consalt_header-right">
                        <div class="sidebar-btn">
                            <div class="nav-btn navSidebar-button"><span><i class="bi bi-filter-left"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--==================================================-->
    <!-- End Header Area -->
    <!--==================================================-->

    <!--========= Start Mobile Memu========== -->

    <div class="mobile-menu-area sticky d-sm-block d-md-block d-lg-none">
        <div class="mobile-menu">
            <nav class="header-menu">
                <ul class="nav_scroll">
                    <li><a href="{{ route('_homeIndex') }}">Home</a></li>
                    <li><a href="{{ route('_aboutusPost') }}">About</a></li>
                    <li><a href="#">Services</a>
                        <ul class="sub_menu">
                            <li><a href="{{ route('_personalServiceIndex') }}">Personal Loan</a></li>
                            <li><a href="{{ route('_businessServiceIndex') }}">Business Loan</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('_contactusPost') }}">Contact</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <!--========= End Mobile Memu========== -->

    <!-- Sidebar Cart Item -->
    <div class="xs-sidebar-group info-group">
        <div class="xs-overlay xs-bg-black"></div>
        <div class="xs-sidebar-widget">
            <div class="sidebar-widget-container">
                <div class="widget-heading">
                    <a href="#" class="close-side-widget">
                        <i class="far fa-times-circle"></i>
                    </a>
                </div>
                <div class="sidebar-textwidget">
                    <!-- Sidebar Info Content -->
                    <div class="sidebar-info-contents">
                        <div class="content-inner">
                            <div class="nav-logo">
                                <a href="{{ route('_homeIndex') }}"><img src="{{ asset('assets/images/logo.png') }}" width="150"
                                        alt="flubbi"></a>
                                <p class="pt-4 text-white">{{ config('web.store_data.company_name') }}</p>
                                <p class="pt-0 text-white">{{ config('web.store_data.footer_about_us_text') }}</p>
                                <p class="pt-0 text-white"><strong><i class="bi bi-file-earmark"></i> LLPIN: </strong> {{ config('web.store_data.LLPIN') }}</p>
                                <p class="pt-0 text-white"><strong><i class="bi bi-file-earmark"></i> GSTIN: </strong> {{ config('web.store_data.GSTIN') }}</p>
                            </div>

                            <div class="contact-info">
                                <h2>Contact Info</h2>
                                <ul class="list-style-one">
                                    <li>
                                        <a href="tel:{{ config('web.store_data.phone') }}" title="Mobile number">
                                            <i class="bi bi-phone"></i>
                                            {{ config('web.store_data.phone') }}
                                        </a>
                                    </li>
                                    <li><a href="mailto:{{ config('web.store_data.support_mail') }}" title="Email">
                                            <i class="bi bi-envelope-open"></i>
                                            {{ config('web.store_data.support_mail') }}
                                        </a>
                                    </li>
                                    <li><a href="{{ config('web.store_data.location_gmap_url') }}" target="_blank"
                                            title="address">
                                            <i class="bi bi-map"></i>
                                            {{ config('web.store_data.location') }}
                                        </a>
                                    </li>

                                    <li><i class="bi bi-clock"></i>Week Days: 09.00 AM to 06.00 PM <br /> &nbsp; &nbsp;
                                        &nbsp; Sunday: Closed</li>
                                </ul>

                            </div>
                            <ul class="social-box">

                                <li><a href="{{ config('web.store_data.social_links.facebook') }}"><i
                                            class="fab fa-facebook-f"></i></a></li>
                                <li><a class="top-social-icon-left"
                                        href="{{ config('web.store_data.social_links.instagram') }}"><i
                                            class="fab fa-instagram"></i></a></li>
                                <li><a href="{{ config('web.store_data.social_links.linkedin') }}"><i
                                            class="fab fa-linkedin-in"></i></a></li>
                                <li><a href="{{ config('web.store_data.social_links.youtube') }}"><i
                                            class="fab fa-youtube"></i></a></li>
                                <li><a href="{{ config('web.store_data.social_links.threads') }}"><svg
                                            xmlns="http://www.w3.org/2000/svg" height="20" width="18"
                                            viewBox="0 0 448 512">
                                            <path fill="#928357"
                                                d="M331.5 235.7c2.2 .9 4.2 1.9 6.3 2.8c29.2 14.1 50.6 35.2 61.8 61.4c15.7 36.5 17.2 95.8-30.3 143.2c-36.2 36.2-80.3 52.5-142.6 53h-.3c-70.2-.5-124.1-24.1-160.4-70.2c-32.3-41-48.9-98.1-49.5-169.6V256v-.2C17 184.3 33.6 127.2 65.9 86.2C102.2 40.1 156.2 16.5 226.4 16h.3c70.3 .5 124.9 24 162.3 69.9c18.4 22.7 32 50 40.6 81.7l-40.4 10.8c-7.1-25.8-17.8-47.8-32.2-65.4c-29.2-35.8-73-54.2-130.5-54.6c-57 .5-100.1 18.8-128.2 54.4C72.1 146.1 58.5 194.3 58 256c.5 61.7 14.1 109.9 40.3 143.3c28 35.6 71.2 53.9 128.2 54.4c51.4-.4 85.4-12.6 113.7-40.9c32.3-32.2 31.7-71.8 21.4-95.9c-6.1-14.2-17.1-26-31.9-34.9c-3.7 26.9-11.8 48.3-24.7 64.8c-17.1 21.8-41.4 33.6-72.7 35.3c-23.6 1.3-46.3-4.4-63.9-16c-20.8-13.8-33-34.8-34.3-59.3c-2.5-48.3 35.7-83 95.2-86.4c21.1-1.2 40.9-.3 59.2 2.8c-2.4-14.8-7.3-26.6-14.6-35.2c-10-11.7-25.6-17.7-46.2-17.8H227c-16.6 0-39 4.6-53.3 26.3l-34.4-23.6c19.2-29.1 50.3-45.1 87.8-45.1h.8c62.6 .4 99.9 39.5 103.7 107.7l-.2 .2zm-156 68.8c1.3 25.1 28.4 36.8 54.6 35.3c25.6-1.4 54.6-11.4 59.5-73.2c-13.2-2.9-27.8-4.4-43.4-4.4c-4.8 0-9.6 .1-14.4 .4c-42.9 2.4-57.2 23.2-56.2 41.8l-.1 .1z" />
                                        </svg> </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    @hasSection('bodyIndex')
        @yield('bodyIndex')
    @endif


    <section class="call_area style_three">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-6">
                    <div class="call-do-action-info">
                        <div class="call-do-social_icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="call_info">
                            <p>Say Hello</p>
                            <h3><a
                                    href="mailto:{{ config('web.store_data.support_mail') }}">{{ config('web.store_data.support_mail') }}</a>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="footer_logo">
                        <a href="{{ route('_homeIndex') }}"><img src="{{ asset('assets/images/logo.png') }}" width="150"
                                alt="flubbi"></a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="call_social_icon">
                        {{-- <ul class="social-icons">

                            @if ([] != config('web.menu.social_media'))
                                @foreach (config('web.menu.social_media') as $k => $v)
                                    <li>
                                        <a href="{{ $v['url'] }}" title="{{ $v['label'] }}" class="fab"
                                            target="_blank">
                                            <?php
                                            echo $v['icon'];
                                            ?>
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul> --}}
                        <ul>
                            <li><a href="{{ config('web.store_data.social_links.facebook') }}"><i
                                        class="fab fa-facebook-f"></i></a></li>
                            <li><a class="top-social-icon-left"
                                    href="{{ config('web.store_data.social_links.instagram') }}"><i
                                        class="fab fa-instagram"></i></a></li>
                            <li><a href="{{ config('web.store_data.social_links.linkedin') }}"><i
                                        class="fab fa-linkedin-in"></i></a></li>
                            <li><a href="{{ config('web.store_data.social_links.youtube') }}"><i
                                        class="fab fa-youtube"></i></a></li>
                            <li><a href="{{ config('web.store_data.social_links.threads') }}"> <svg
                                        xmlns="http://www.w3.org/2000/svg" height="20" width="18"
                                        viewBox="0 0 448 512">
                                        <path fill="#ffffff"
                                            d="M331.5 235.7c2.2 .9 4.2 1.9 6.3 2.8c29.2 14.1 50.6 35.2 61.8 61.4c15.7 36.5 17.2 95.8-30.3 143.2c-36.2 36.2-80.3 52.5-142.6 53h-.3c-70.2-.5-124.1-24.1-160.4-70.2c-32.3-41-48.9-98.1-49.5-169.6V256v-.2C17 184.3 33.6 127.2 65.9 86.2C102.2 40.1 156.2 16.5 226.4 16h.3c70.3 .5 124.9 24 162.3 69.9c18.4 22.7 32 50 40.6 81.7l-40.4 10.8c-7.1-25.8-17.8-47.8-32.2-65.4c-29.2-35.8-73-54.2-130.5-54.6c-57 .5-100.1 18.8-128.2 54.4C72.1 146.1 58.5 194.3 58 256c.5 61.7 14.1 109.9 40.3 143.3c28 35.6 71.2 53.9 128.2 54.4c51.4-.4 85.4-12.6 113.7-40.9c32.3-32.2 31.7-71.8 21.4-95.9c-6.1-14.2-17.1-26-31.9-34.9c-3.7 26.9-11.8 48.3-24.7 64.8c-17.1 21.8-41.4 33.6-72.7 35.3c-23.6 1.3-46.3-4.4-63.9-16c-20.8-13.8-33-34.8-34.3-59.3c-2.5-48.3 35.7-83 95.2-86.4c21.1-1.2 40.9-.3 59.2 2.8c-2.4-14.8-7.3-26.6-14.6-35.2c-10-11.7-25.6-17.7-46.2-17.8H227c-16.6 0-39 4.6-53.3 26.3l-34.4-23.6c19.2-29.1 50.3-45.1 87.8-45.1h.8c62.6 .4 99.9 39.5 103.7 107.7l-.2 .2zm-156 68.8c1.3 25.1 28.4 36.8 54.6 35.3c25.6-1.4 54.6-11.4 59.5-73.2c-13.2-2.9-27.8-4.4-43.4-4.4c-4.8 0-9.6 .1-14.4 .4c-42.9 2.4-57.2 23.2-56.2 41.8l-.1 .1z" />
                                    </svg></a></li>

                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!--==================================================-->
    <!-- Start Consalt Footer Area -->
    <!--==================================================-->
    <section class="footer_area style_two style_three">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget-content style_two">
                        <div class="footer-widget-title">
                            <a href="{{ route('_homeIndex') }}"><img src="{{ asset('assets/images/logo.png') }}" width="150"
                                    alt=""></a>
                        </div>
                        <p class="footer_desc">{{ config('web.store_data.footer_about_us_text') }}</p>
                    </div>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget-content style_two">
                        <div class="footer-widget-title">
                            <h4>Company</h4>
                        </div>
                        <div class="footer-widget-menu">
                            <ul>
                                <li><a href="{{ route('_aboutusPost') }}"><i class="bi bi-chevron-double-right"></i>
                                        About Us</a></li>
                                <li><a href="#"><i class="bi bi-chevron-double-right"></i> Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget-content style_two">
                        <div class="footer-widget-title">
                            <h4>Help & Support</h4>
                        </div>
                        <div class="footer-widget-menu">
                            <ul>
                                <li><a href="{{ route('_privacyPolicyPost') }}"><i
                                            class="bi bi-chevron-double-right"></i>
                                        Privacy Policy</a></li>
                                <li><a href="{{ route('_refundPolicyPost') }}"><i
                                            class="bi bi-chevron-double-right"></i>
                                        Refund Policy </a></li>
                                <li><a href="{{ route('_termsAndConditionsPost') }}"><i
                                            class="bi bi-chevron-double-right"></i> Terms &
                                        Conditions</a></li>
                                <li><a href="{{ route('_disclaimerPost') }}"><i
                                            class="bi bi-chevron-double-right"></i>
                                        Disclaimer</a></li>
                                <li><a href="{{ route('_faqPost') }}"><i class="bi bi-chevron-double-right"></i>
                                        FAQ’s</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget-content style_two">
                        <div class="footer-widget-title">
                            <h4>Get In Touch</h4>
                        </div>
                        <div class="footer-widget-menu">

                            <ul>
                                <li>
                                    <a href="#" target="#" title="Company name">
                                        <i class="bi bi-building"></i>
                                        {{ config('web.store_data.company_name') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="tel:{{ config('web.store_data.phone') }}" title="Mobile number">
                                        <i class="bi bi-phone"></i>
                                        {{ config('web.store_data.phone') }}
                                    </a>
                                </li>
                                <li><a href="mailto:{{ config('web.store_data.support_mail') }}" title="Email">
                                        <i class="bi bi-envelope-open"></i>
                                        {{ config('web.store_data.support_mail') }}
                                    </a>
                                </li>
                                <li><a href="{{ config('web.store_data.location_gmap_url') }}" target="_blank"
                                        title="address">
                                        <i class="bi bi-map"></i>
                                        {{ config('web.store_data.location') }}
                                    </a>
                                </li>
                                <li><a href="#" target="#" title="GSTIN"><i class="bi bi-file-earmark"></i> GSTIN: {{ config('web.store_data.GSTIN') }}</a></li>
                                <li><a href="#" target="#" title="LLPIN"><i class="bi bi-file-earmark"></i> LLPIN: {{ config('web.store_data.LLPIN') }}</a></li>
                            </ul>

                        </div>
                    </div>
                </div>

            </div>
            <div class="row add-border style_two align-items-center">
                <div class="col-md-12 text-center">
                    <div class="footer-bottom-content">
                        <div class="footer-bottom-content-copy style_two">
                            <p>Copyright © 2025 by {{ config('web.store_data.company_name') }}. All Rights Reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer_all_shape">
            <div class="footer_shape_one dance">
                <img src="{{ asset('assets/images/home_3/choose_rotete.png') }}" alt="">
            </div>
            <div class="footer_shape_two bounce-animate">
                <img src="{{ asset('assets/images/home_3/footer_shape.png') }}" alt="">
            </div>
        </div>
    </section>
    <!--==================================================-->
    <!-- End Consalt Footer Area-->
    <!--==================================================-->

    <div class="prgoress_indicator active-progress">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
                style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 212.78;">
            </path>
        </svg>
    </div>

    @stack('customJs')

    @if ([] != config('web.assets.frontend.js'))
        @foreach (config('web.assets.frontend.js') as $k => $v)
            <script src="{{ asset($v) }}"></script>
        @endforeach
    @endif

    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/app/layout.js') }}"></script>

    @hasSection('jsIndex')
        @yield('jsIndex')
    @endif
</body>

</html>

@hasSection('inlineJsIndex')
    @yield('inlineJsIndex')
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script type="text/javascript">
            Notify("{{ $error }}");
        </script>
    @endforeach
@endif

@if (session('success'))
    <script type="text/javascript">
        Notify("{{ session('success') }}", "success");
    </script>
@endif
