@extends('frontend.layoutIndex')


@section('bodyIndex')
    <div class="page-content">

        <div class="section-full p-t120 p-b120 twm-for-employee-area site-bg-white">
            <div class="container">

                <div class="section-content">


                    <div class="pricing-block-outer">
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-6 m-b30">
                                <div class="pricing-table-1">

                                    <div class="p-table-title">
                                        <h4 class="wt-title">Customer Details</h4>
                                        <ul>
                                            <li><i class="bi bi-person-check"></i> Full Name :-
                                                <strong>{{ session('in_user')['name'] }}</strong>
                                            </li>
                                            <li><i class="bi bi-phone"></i> Mobile :-
                                                <strong>{{ session('in_user')['phone'] }}</strong>
                                            </li>
                                            <li><i class="bi bi-currency-rupee"></i>
                                                {{ Str::ucfirst(config('web.webapp.loan_word.view_file')) }} Amount :-
                                                <strong>₹{{ session('in_user')['format_amount'] }}</strong>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="p-table-title">
                                        <h4 class="wt-title">
                                            {{ Str::ucfirst(config('web.webapp.loan_word.view_file')) }}
                                            Applying Steps</h4>
                                        <ul>
                                            <li><i class="fas fa-arrow-right"></i> Registration Process</li>
                                            <li><i class="fas fa-arrow-right"></i> Check Eligibility</li>
                                            <li><i class="fas fa-arrow-right"></i> Pre-Approval Offer</li>
                                            <li class="text-warning"><strong><i class="fas fa-arrow-right text-warning"></i>
                                                    Buy
                                                    Subscription</strong></li>
                                        </ul>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 p-table-highlight m-b30">
                                <div class="pricing-table-1 circle-yellow">
                                    <div class="p-table-recommended">50% OFF</div>
                                    <div class="p-table-title">
                                        <h4 class="wt-title">
                                            Subscription Plan
                                        </h4>
                                    </div>
                                    <div class="p-table-inner">

                                        <div class="p-table-price">
                                            <del class="text-danger">₹ {{ $subscription_info['price'] }}/-</del>
                                            <span class="fs-3 text-success"><strong>₹
                                                    {{ $subscription_info['s_price'] }}/-</strong></span>
                                        </div>
                                        <div class="p-table-list">
                                            <ul>
                                                <li>
                                                    <i class="feather-check"></i>
                                                    <span class="weight-700 text-success">
                                                        50% OFF
                                                    </span>
                                                </li>
                                                <!--- <li>
                                                        <i class="feather-check"></i>
                                                        <strong>Subtotal: </strong>&nbsp; ₹{{ $subscription_info['s_price'] }}/-
                                                    </li>
                                                    <li>
                                                        <i class="feather-check"></i>
                                                        <strong>GST (18%): </strong>&nbsp; 179.82
                                                    </li> --->
                                                <li>
                                                    <i class="feather-check"></i>
                                                    <strong>Grand Total: </strong>&nbsp;
                                                    ₹{{ $subscription_info['s_price'] }}/-
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="p-table-btn">
                                            <a href="{{ route('_checkoutLoansIndex', ['uuid' => $key_uuid]) }}">
                                                <button type="button" class="site-button" name="calc_submit"> BUY
                                                    NOW <i class="bi bi-arrow-up-right"></i><span></span></button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 m-b30">
                                <div class="pricing-table-1 circle-pink">
                                    <div class="p-table-title">
                                        <h4 class="wt-title">
                                            Plan Benefits
                                        </h4>
                                    </div>
                                    <div class="p-table-inner">
                                        <div class="p-table-list">
                                            <ul>
                                                <li><i class="feather-check"></i> 100% Online Process</li>
                                                <li><i class="feather-check"></i> Get Personalized Tracking Portal</li>
                                                <li><i class="feather-check"></i> On-Call Expert Consultation</li>
                                                <li><i class="feather-check"></i> Dedicated
                                                    {{ Str::ucfirst(config('web.webapp.loan_word.view_file')) }} Expert
                                                    Assigned</li>
                                                <li><i class="feather-check"></i> CIBIL Remains Unaffected</li>
                                                <li><i class="feather-check"></i> Plan Validity: 3 Months </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    @include('frontend.block.testimonials')

@endsection
