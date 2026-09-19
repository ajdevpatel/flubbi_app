@extends('frontend.layoutIndex')

@section('bodyIndex')

<!-- HERO SECTION -->
<section class="hero_area style_two style_three d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero_content">
                    <h4>RBI COMPLIANT FINTECH PLATFORM</h4>
                    <h1>Smart & Secure</h1>
                    <h1>Loan Consultancy Solutions</h1>
                    <p>
                        We help individuals find the right personal loan options through
                        transparent guidance, secure technology, and RBI-regulated lending partners.
                    </p>
                    <div class="slider_button">
                        <div class="hero_btn style_two">
                            <a href="{{ route('_contactus') }}">
                                <i class="far fa-thumbs-up"></i>Get Started<span></span>
                            </a>
                        </div>
                        <div class="hero_video_btn">
                            <a class="video-vemo-icon venobox vbox-item" data-vbtype="youtube" data-autoplay="true"
                               href="https://youtu.be/BS4TUd7FJSg">
                                <i class="bi bi-play-btn"></i>
                            </a>
                            <span>How It Works</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="hero-thumb">
                    <img src="assets/images/home_3/hero_thumb3.png" alt="">
                    <div class="powerful_box bounce-animate4">
                        <div class="hero_power_check">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="hero_powerful_content">
                            <h4>Trusted Platform</h4>
                            <p>RBI-Regulated Partners</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ABOUT SECTION -->
<section class="about_area style_three"
         style="background:url(assets/images/home_3/about_bg.png) no-repeat center/cover;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about_thumb">
                    <img src="assets/images/home_3/about_thumb.png" alt="">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section_title style_three">
                    <h4>ABOUT US</h4>
                    <h1>India’s Trusted Digital</h1>
                    <h1>Loan Consultancy Platform</h1>
                    <p>
                        {{ config('web.store_data.app_name') }} operates as a Loan Service Provider (LSP),
                        helping users assess eligibility, compare loan offers, and apply securely —
                        without hidden fees or misleading claims.
                    </p>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="about-icon_box style_two">
                            <h3>Loan Eligibility Assessment</h3>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-icon_box style_two">
                            <h3>Guided Loan Applications</h3>
                        </div>
                    </div>
                </div>

                <div class="about_button style_upper">
                    <a href="{{ route('_aboutus') }}" class="about_btn style_two style_three">
                        Learn More<span></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES -->
<section class="service_area style_two style_three">
    <div class="container">
        <div class="section_title text-center style_three">
            <h4>OUR SERVICES</h4>
            <h1>End-to-End Loan Assistance</h1>
        </div>

        <div class="service_list2 owl-carousel">
            <div class="service_single_item">
                <h3>Loan Eligibility Check</h3>
                <p>Instant assessment based on your profile.</p>
            </div>

            <div class="service_single_item">
                <h3>Loan Comparison</h3>
                <p>Compare offers from RBI-registered lenders.</p>
            </div>

            <div class="service_single_item">
                <h3>Application Support</h3>
                <p>End-to-end guidance till disbursement.</p>
            </div>

            <div class="service_single_item">
                <h3>Secure Data Handling</h3>
                <p>No access to SMS, contacts, or call logs.</p>
            </div>
        </div>
    </div>
</section>

<!-- WHY CHOOSE US -->
<section class="why_choose_us">
    <div class="container">
        <div class="section_title style_three">
            <h4>WHY CHOOSE US</h4>
            <h1>Transparent & Responsible FinTech</h1>
            <p>
                We follow RBI Digital Lending Guidelines and operate strictly
                as a service provider — not a lender.
            </p>
        </div>

        <ul>
            <li>✔ RBI-Compliant Operations</li>
            <li>✔ Zero Hidden Charges</li>
            <li>✔ Secure User Data</li>
            <li>✔ Customer-First Guidance</li>
        </ul>
    </div>
</section>

<!-- COUNTER -->
<section class="counter_area style_two style_three">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <h1>100%</h1>
                <p>Compliance Driven</p>
            </div>
            <div class="col-lg-4">
                <h1>0</h1>
                <p>Unauthorized Data Access</p>
            </div>
            <div class="col-lg-4">
                <h1>24/7</h1>
                <p>Customer Support</p>
            </div>
        </div>
    </div>
</section>

<!-- CONTACT -->
<section class="contact_area">
    <div class="container">
        <div class="section_title text-center">
            <h4>CONTACT US</h4>
            <h1>Talk to Our Loan Experts</h1>
        </div>

        <form id="_inquiryModule" method="POST" action="{{ route('_contactusPost') }}">
            @csrf
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="email" name="mail" placeholder="Email Address" required>
            <textarea name="message" placeholder="Your Requirement" required></textarea>
            <button type="submit">Request Call Back</button>
        </form>
    </div>
</section>

@endsection

@section('jsIndex')
<script src="{{ asset('assets/app/contactIndex.js') }}"></script>
@endsection
