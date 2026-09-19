@extends('frontend.layoutIndex')

@section('bodyIndex')
    <!-- Breadcrumb Area -->
    <div class="breadcumb-area d-flex">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="breadcumb-content">
                        <div class="breadcumb-title">
                            <h4>Contact Us</h4>
                        </div>
                        <ul>
                            <li><a href="{{ route('_homeIndex') }}"><i class="bi bi-house-door-fill"></i> Home </a></li>
                            <li class="rotates"><i class="bi bi-slash-lg"></i>Contact</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form Section -->
    <section class="contact_area inner_section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="section_title">
                        <h4>GET IN TOUCH</h4>
                        <h1>Why Contact Us?</h1>
                        <p>We’re here to answer your questions, guide you through our services, and help you make confident
                            financial decisions.</p>
                    </div>
                    <div class="contact_main_info">
                        <div class="call-do-action-info">
                            <div class="call-do-social_icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="call_info">
                                <p>Instant Response</p>
                                <h3>Our support team responds to all queries within 2 hours, 24/7.</h3>
                            </div>
                        </div>
                        <div class="call-do-action-info">
                            <div class="call-do-social_icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="call_info">
                                <p>Expert Help</p>
                                <h3>Our team has years of experience in financial services and technology.</h3>
                            </div>
                        </div>
                        <div class="call-do-action-info">
                            <div class="call-do-social_icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div class="call_info">
                                <p>Personalized Support</p>
                                <h3>Every customer gets personalized attention and solutions.</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="contact-form-box style_two">
                        <div class="section_title style_three style_four text-center">
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
                                        <input type="text" name="phone" id="phone" placeholder="Phone No" required>
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
                                    <button type="submit" id="contact-submit"><i class="far fa-thumbs-up"></i> Request Call
                                        Back</button>
                                </div>
                            </div>
                        </form>

                        <div id="status" style="margin-top:15px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('jsIndex')
    <script src="{{ asset('assets/app/contactIndex.js') }}"></script>
@endsection
