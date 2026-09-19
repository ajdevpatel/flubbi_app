@extends('frontend.layoutIndex')


@section('bodyIndex')
    <div class="breadcumb-area d-flex ">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="breadcumb-content">
                        <div class="breadcumb-title style_two">
                            <h4>Disclaimer</h4>
                        </div>
                        <ul>
                            <li><a href="{{ route('_homeIndex') }}"><i class="bi bi-house-door-fill"></i> Home </a></li>
                            <li class="rotates"><i class="bi bi-slash-lg"></i>Disclaimer</li>
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
                            <h2>Disclaimer – Legal Liability Protection</h2>
                            <hr>

                            <p>
                                This Disclaimer (“Disclaimer”) applies to the use of the {{ config('web.store_data.app_name') }} mobile application
                                (“App”)
                                operated by {{ config('web.store_data.company_name') }} (“we”, “us”, “our”).
                            </p>

                            <p>
                                By accessing or using the App, you agree to this Disclaimer.
                            </p>

                            <hr>

                            <h3>1. No Lender Relationship</h3>
                            <p>
                                {{ config('web.store_data.company_name') }} is not a bank, NBFC, or financial institution, and we do not:
                            </p>
                            <ul>
                                <li>Approve or reject loans</li>
                                <li>Disburse loan amounts</li>
                                <li>Decide interest rates or tenure</li>
                                <li>Influence lender decisions</li>
                            </ul>
                            <p>
                                All loan decisions are made solely by the respective banks/NBFCs.
                            </p>

                            <hr>

                            <h3>2. Consultancy-Only Role</h3>
                            <p>
                                We operate only as a loan consultancy and facilitation platform.
                                Our services are limited to:
                            </p>
                            <ul>
                                <li>Providing general financial information</li>
                                <li>Assessing eligibility</li>
                                <li>Offering lender suggestions</li>
                                <li>Assisting with documentation</li>
                                <li>Facilitating the loan application process</li>
                            </ul>
                            <p>
                                We do not provide financial, legal, tax, investment, or credit advice.
                            </p>

                            <hr>

                            <h3>3. No Guarantee of Loan Approval</h3>
                            <p>We do not guarantee:</p>
                            <ul>
                                <li>Loan approval</li>
                                <li>Loan amount or terms</li>
                                <li>Processing speed or timeline</li>
                                <li>Accuracy of lender decisions</li>
                            </ul>
                            <p>
                                Loan approval depends solely on lender policies, internal risk models,
                                credit bureau checks, and other independent factors.
                            </p>

                            <hr>

                            <h3>4. Information Accuracy</h3>
                            <p>
                                While we strive to keep the information accurate and updated, we do not guarantee:
                            </p>
                            <ul>
                                <li>Completeness</li>
                                <li>Timeliness</li>
                                <li>Accuracy</li>
                                <li>Reliability</li>
                            </ul>
                            <p>
                                Interest rates, loan details, lender criteria, and eligibility norms are subject to change
                                without notice.
                                We are not responsible for errors, omissions, or outdated information.
                            </p>

                            <hr>

                            <h3>5. Third-Party Links &amp; Services</h3>
                            <p>The App may contain links to:</p>
                            <ul>
                                <li>Banks/NBFC websites</li>
                                <li>Third-party APIs</li>
                                <li>Verification services</li>
                                <li>External financial tools</li>
                            </ul>
                            <p>
                                We do not endorse or control these external services.
                                We are not liable for:
                            </p>
                            <ul>
                                <li>Their content</li>
                                <li>Their practices</li>
                                <li>Any loss or damage caused by their services</li>
                            </ul>

                            <hr>

                            <h3>6. User Responsibility</h3>
                            <p>You acknowledge that:</p>
                            <ul>
                                <li>You submit information voluntarily.</li>
                                <li>You are responsible for the correctness of all documents and details.</li>
                                <li>You assume all risks associated with financial decisions.</li>
                            </ul>
                            <p>
                                We strongly advise consulting professional financial advisors before making financial
                                commitments.
                            </p>

                            <hr>

                            <h3>7. No Professional Advice</h3>
                            <p>
                                Information provided in the App is for general educational and informational purposes only.
                                It should not be treated as:
                            </p>
                            <ul>
                                <li>Financial advice</li>
                                <li>Legal advice</li>
                                <li>Investment advice</li>
                                <li>Tax planning advice</li>
                            </ul>
                            <p>
                                Any decisions made based on App content are at your own risk.
                            </p>

                            <hr>

                            <h3>8. Limitation of Liability</h3>
                            <p>
                                To the maximum extent permitted by law, we are not liable for:
                            </p>
                            <ul>
                                <li>Loan rejection or modification</li>
                                <li>Financial loss or damages</li>
                                <li>Delay in processing by lenders</li>
                                <li>Incorrect or incomplete data from third parties</li>
                                <li>Technical errors, downtime, or service interruptions</li>
                                <li>Loss of profits, data, or business opportunities</li>
                            </ul>
                            <p>
                                Your use of the App is at your sole risk.
                            </p>

                            <hr>

                            <h3>9. Indemnification</h3>
                            <p>
                                You agree to indemnify and hold {{ config('web.store_data.company_name') }} harmless from any claims, damages,
                                losses, and liabilities arising out of:
                            </p>
                            <ul>
                                <li>Misuse of the App</li>
                                <li>Submission of incorrect information</li>
                                <li>Violation of Terms &amp; Conditions</li>
                                <li>Fraudulent activities</li>
                            </ul>

                            <hr>

                            <h3>10. Changes to Disclaimer</h3>
                            <p>
                                We may update or amend this Disclaimer at any time.
                                Updates will be posted in the App with a revised “Last Updated” date.
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
