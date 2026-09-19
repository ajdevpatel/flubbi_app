@extends('frontend.layoutIndex')


@section('bodyIndex')
    <div class="breadcumb-area d-flex ">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="breadcumb-content">
                        <div class="breadcumb-title style_two">
                            <h4>Privacy Policy</h4>
                            <hr>
                        </div>
                        <ul>
                            <li><a href="{{ route('_homeIndex') }}"><i class="bi bi-house-door-fill"></i> Home </a></li>
                            <li class="rotates"><i class="bi bi-slash-lg"></i>Privacy Policy</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="portfolio_details">
        <div class="container">
            <div class="port_main">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="port_details_content">
                            <h2>Privacy Policy</h2>
                            <hr>

                            <p>
                                This Privacy Policy describes how {{ config('web.store_data.company_name') }}
                                (“Company”, “we”, “us”, “our”) collects, uses, stores, and protects your information
                                when you use the {{ config('web.store_data.app_name') }} mobile application (“App”) and website.
                            </p>
                            <p>
                                By accessing or using our services, you agree to the practices described in this Privacy Policy.
                            </p>

                            <hr>

                            <h3>1. Information We Collect</h3>
                            <p>We collect the following types of information:</p>

                            <h4>1.1 Personal Information</h4>
                            <ul>
                                <li>Name</li>
                                <li>Mobile number</li>
                                <li>Email address</li>
                                <li>Date of birth</li>
                                <li>Residential address</li>
                                <li>Gender</li>
                                <li>Employment details</li>
                                <li>Income details</li>
                                <li>Bank account details (non-sensitive, read-only where applicable)</li>
                                <li>Existing loans and financial obligations</li>
                                <li>PAN and Aadhaar details (for KYC purposes)</li>
                                <li>Uploaded documents (ID proof, address proof, salary slips, bank statements, etc.)</li>
                            </ul>

                            <h4>1.2 KYC & Verification Data</h4>
                            <ul>
                                <li>Aadhaar/PAN verification details</li>
                                <li>Photographs and liveness verification</li>
                                <li>Geolocation data (only with your consent)</li>
                            </ul>

                            <h4>1.3 Device & Usage Data</h4>
                            <ul>
                                <li>Device type, operating system, IP address</li>
                                <li>App usage logs and interaction data</li>
                                <li>Crash reports and diagnostics</li>
                                <li>Cookies (for website users)</li>
                            </ul>

                            <hr>

                            <h3>2. How We Use Your Information</h3>
                            <ul>
                                <li>Provide loan consultancy and eligibility assessment</li>
                                <li>Match you with suitable lending partners</li>
                                <li>Assist in loan application processing</li>
                                <li>Perform KYC and identity verification</li>
                                <li>Detect fraud and enhance security</li>
                                <li>Send service-related communications and updates</li>
                                <li>Improve our services and user experience</li>
                                <li>Provide customer support</li>
                                <li>Comply with legal and regulatory obligations</li>
                            </ul>

                            <p><strong>We do NOT sell your personal data to any third party.</strong></p>

                            <hr>

                            <h3>3. Sharing of Information</h3>

                            <h4>3.1 Lending Partners</h4>
                            <ul>
                                <li>Banks and NBFCs for eligibility, credit checks, and loan processing</li>
                            </ul>

                            <h4>3.2 Verification & Service Providers</h4>
                            <ul>
                                <li>KYC and identity verification agencies</li>
                                <li>Cloud storage providers</li>
                                <li>Analytics and communication service providers</li>
                            </ul>

                            <p>
                                All third parties are contractually obligated to protect your data in accordance with applicable laws.
                            </p>

                            <hr>

                            <h3>4. Data Security</h3>
                            <p>We implement industry-standard safeguards including:</p>
                            <ul>
                                <li>Encryption (SSL/TLS)</li>
                                <li>Secure servers and firewalls</li>
                                <li>Restricted access controls</li>
                                <li>Periodic security audits</li>
                            </ul>

                            <p>
                                Despite our efforts, no system is completely secure. Use of the App is at your own risk.
                            </p>

                            <hr>

                            <h3>5. Data Retention</h3>
                            <ul>
                                <li>Data is retained as long as your account is active</li>
                                <li>Retention may continue as required by law or regulatory obligations</li>
                                <li>Documents related to loan applications may be deleted within a reasonable period after processing</li>
                            </ul>

                            <hr>

                            <h3>6. Your Rights</h3>
                            <ul>
                                <li>Access your personal data</li>
                                <li>Update or correct your information</li>
                                <li>Request deletion of your data</li>
                                <li>Withdraw consent</li>
                                <li>Opt-out of marketing communications</li>
                            </ul>

                            <p>
                                To exercise your rights, contact us at:
                                <strong>{{ config('web.webapp.support_email') }}</strong>
                            </p>

                            <hr>

                            <h3>7. Cookies & Tracking</h3>
                            <ul>
                                <li>Improve user experience</li>
                                <li>Analytics and performance tracking</li>
                                <li>Session management</li>
                            </ul>
                            <p>You can control cookies through your browser settings.</p>

                            <hr>

                            <h3>8. Children’s Privacy</h3>
                            <p>
                                Our services are not intended for individuals under 18 years of age.
                                We do not knowingly collect data from minors.
                            </p>

                            <hr>

                            <h3>9. Third-Party Links</h3>
                            <p>
                                We are not responsible for the privacy practices of third-party websites or services.
                            </p>

                            <hr>

                            <h3>10. Changes to This Policy</h3>
                            <p>
                                We may update this Privacy Policy from time to time. Continued use of the App constitutes acceptance of updates.
                            </p>

                            <hr>

                            <h3>11. Contact Us</h3>
                            @include('frontend.block.contactus')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
