@extends('frontend.layoutIndex')


@section('bodyIndex')
    <div class="breadcumb-area d-flex ">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="breadcumb-content">
                        <div class="breadcumb-title style_two">
                            <h4>Terms And Conditions</h4>
                        </div>
                        <ul>
                            <li><a href="{{ route('_homeIndex') }}"><i class="bi bi-house-door-fill"></i> Home </a></li>
                            <li class="rotates"><i class="bi bi-slash-lg"></i>Terms And Conditions</li>
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
                            <h2>Terms & Conditions</h2>
                            <hr>

                            <p>
                                These Terms & Conditions (“Terms”) govern your use of the
                                {{ config('web.store_data.app_name') }} mobile application (“App”)
                                and website operated by {{ config('web.store_data.company_name') }}
                                (“Company”, “we”, “us”, “our”).
                                By accessing or using our services, you agree to these Terms.
                                If you do not agree, please do not use the App.
                            </p>

                            <hr>

                            <h3>1. Acceptance of Terms</h3>
                            <p>
                                By registering, accessing, or using the App, you agree to be legally bound by these Terms,
                                our Privacy Policy, and any additional policies referenced herein.
                            </p>

                            <hr>

                            <h3>2. Eligibility</h3>
                            <p>To use the App, you must:</p>
                            <ul>
                                <li>Be at least 18 years old</li>
                                <li>Be legally capable of entering into binding contracts under Indian law</li>
                                <li>Provide accurate and complete information</li>
                            </ul>

                            <hr>

                            <h3>3. Nature of Services</h3>
                            <p>
                                {{ config('web.store_data.company_name') }} is a technology platform that facilitates
                                connections between users and third-party financial institutions, including banks and NBFCs.
                            </p>

                            <ul>
                                <li>Loan consultancy services</li>
                                <li>Loan eligibility assessment</li>
                                <li>Matching with lending partners</li>
                                <li>Assistance with documentation and application</li>
                                <li>Customer support and guidance</li>
                            </ul>

                            <p><strong>Important:</strong></p>
                            <p>
                                We are NOT a lender, NBFC, or financial institution.
                                We do not provide loans directly.
                            </p>

                            <hr>

                            <h3>4. User Responsibilities</h3>
                            <ul>
                                <li>Provide accurate, truthful, and updated information</li>
                                <li>Maintain confidentiality of login details</li>
                                <li>Use the App only for lawful purposes</li>
                                <li>Not misuse, reverse engineer, or tamper with the App</li>
                            </ul>

                            <hr>

                            <h3>5. KYC, Verification & Information Accuracy</h3>
                            <p>You authorize us to:</p>
                            <ul>
                                <li>Collect and verify your information</li>
                                <li>Share necessary data with banks/NBFCs/verifiers</li>
                                <li>Conduct eligibility checks</li>
                            </ul>
                            <p>
                                You are solely responsible for the accuracy of all submitted information and documents.
                            </p>

                            <hr>

                            <h3>6. No Guarantee of Loan Approval</h3>
                            <p>
                                Loan approval depends entirely on the policies of individual banks and NBFCs.
                                We do not guarantee:
                            </p>
                            <ul>
                                <li>Loan approval</li>
                                <li>Sanctioned amount</li>
                                <li>Processing time</li>
                                <li>Interest rates or lender terms</li>
                            </ul>

                            <hr>

                            <h3>7. Third-Party Services</h3>
                            <p>The App may include integrations with:</p>
                            <ul>
                                <li>Banks and NBFCs</li>
                                <li>KYC/verification providers</li>
                                <li>Payment gateways</li>
                                <li>Data verification partners</li>
                            </ul>
                            <p>
                                We are not responsible for third-party services, content, or decisions.
                            </p>

                            <hr>

                            <h3>8. Fees & Charges</h3>
                            <ul>
                                <li>We may charge service or consultancy fees</li>
                                <li>All charges will be disclosed before payment</li>
                                <li>Lender-side fees are governed by respective lenders</li>
                            </ul>

                            <hr>

                            <h3>9. Prohibited Activities</h3>
                            <p>You agree NOT to:</p>
                            <ul>
                                <li>Upload false or forged documents</li>
                                <li>Impersonate another person</li>
                                <li>Engage in fraud or illegal activities</li>
                                <li>Disrupt or interfere with App functionality</li>
                                <li>Introduce malware or harmful code</li>
                            </ul>
                            <p>Violation may result in suspension or legal action.</p>

                            <hr>

                            <h3>10. Intellectual Property</h3>
                            <p>
                                All content, including design, logos, code, UI elements, graphics, and text,
                                are the exclusive property of {{ config('web.store_data.company_name') }}
                                and protected under applicable laws.
                            </p>

                            <hr>

                            <h3>11. Limitation of Liability</h3>
                            <ul>
                                <li>Loan decisions made by lenders</li>
                                <li>Loan rejection or delays</li>
                                <li>Financial losses</li>
                                <li>Third-party errors</li>
                                <li>Indirect or consequential damages</li>
                            </ul>
                            <p>Your use of the App is at your own risk.</p>

                            <hr>

                            <h3>12. Indemnification</h3>
                            <p>
                                You agree to indemnify and hold harmless {{ config('web.store_data.company_name') }}
                                from any claims arising due to misuse, false information, or violation of these Terms.
                            </p>

                            <hr>

                            <h3>13. Account Suspension & Termination</h3>
                            <ul>
                                <li>Fraud or suspicious activity</li>
                                <li>Violation of Terms</li>
                                <li>Legal requirements</li>
                                <li>Service discontinuation</li>
                            </ul>

                            <hr>

                            <h3>14. Changes to Terms</h3>
                            <p>
                                We may update these Terms at any time. Continued use of the App implies acceptance of updates.
                            </p>

                            <hr>

                            <h3>15. Governing Law & Jurisdiction</h3>
                            <p>
                                These Terms are governed by the laws of India.
                                Disputes shall fall under the jurisdiction of courts in Surat, Gujarat, India.
                            </p>

                            <hr>

                            <h3>16. Contact Information</h3>
                            @include('frontend.block.contactus')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
