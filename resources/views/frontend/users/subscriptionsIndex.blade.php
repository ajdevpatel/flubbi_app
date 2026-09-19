@extends("frontend.layoutIndex")
 

@section("bodyIndex")

    <!-- Banner Start -->
    <section class="banner">
        <div class="container ">
            <div class="row gy-4 gy-sm-0 align-items-center">
                <div class="col-12 col-sm-6">
                    <div class="banner__content">
                        <h1 class="banner__title display-4 wow fadeInLeft" data-wow-duration="0.8s"> Subscriptions List</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb wow fadeInRight" data-wow-duration="0.8s">
                                <li class="breadcrumb-item"><a href="{{ route('_homeIndex') }}">Home</a></li>
                                <li class="breadcrumb-item">User</li>
                                <li class="breadcrumb-item active" aria-current="page"> Subscriptions List</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="banner__thumb text-end">
                        <img src="{{ asset('assets/images/about_banner.png') }}" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner End -->

    <section class="reviews-details section">
        <div class="container ">
            <div class="row"> 
                <div class="col-12 col-xl-3 btn_sticky advantage-boxes">
                    @include("frontend.users.menuIndex")
                </div>
                <div class="col-12 col-xl-9 order-1 order-xl-0">
                    <div class="reviews-details__area">
                        <div class="reviews-details__part">
                            <h4 class="average-reviews__title">Subscription List</h4>

                            @if ([] != $subscription_list)
                                @foreach($subscription_list as $k => $v)
                                    <div class="loan-reviews loan-reviews--quaternary">
                                        <div class="loan-reviews_card card p-0">
                                            <div class="loan-reviews__part-two">
                                                <div class="reviews-inner">
                                                    <div class="row g-xl-4 g-3">
                                                        <div class="col-lg-6 col-md-6">
                                                            <div class="terms-box">
                                                                <ul>
                                                                    <li>
                                                                        <span class="loan-name">• Type </span>
                                                                        <span class="loan-value">{{ Str::ucfirst($v->loan_type_heading) }} {{ Str::ucfirst(config("web.webapp.loan_word.view_file")) }}</span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="loan-name">• Start Date </span>
                                                                        <span class="loan-value">{{ $v->start_date }}</span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="loan-name">• Expiry Date </span>
                                                                        <span class="loan-value">{{ $v->expiry_date }}</span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="loan-name">• Amount </span>
                                                                        <span class="loan-value">{{ $v->amount }}/-</span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6">
                                                            <div class="terms-box">
                                                                <ul>
                                                                    <li>
                                                                        <span class="loan-name">• Status</span>
                                                                        <span class="loan-value">
                                                                            @if ("0" == $v->status) 
                                                                                <span class="border border-0 text-warning"><i class="ti ti-progress-alert"></i> Pending </span>
                                                                            @elseif ("1" == $v->status)
                                                                                <span class="border border-0 text-success"><i class="ti ti-progress-check"></i> Success </span>
                                                                            @elseif ("2" == $v->status)
                                                                                <span class="border border-0 text-danger"><i class="ti ti-square-rounded-check"></i> Failed </span>
                                                                            @elseif ("3" == $v->status)
                                                                                <span class="border border-0 text-primary"><i class="ti ti-square-progress-check"></i> Refund </span>
                                                                            @elseif ("4" == $v->status)
                                                                                <span class="border border-0 text-danger"><i class="ti ti-square-progress-check"></i> Suspect </span>
                                                                            @elseif ("5" == $v->status)
                                                                                <span class="border border-0 text-info"><i class="ti ti-square-rounded-check"></i> De-Active </span>
                                                                            @endif
                                                                        </span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="loan-name">• Subscription Date</span>
                                                                        <span class="loan-value">{{ $v->rec_date }}</span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="loan-name">• Subscription Number </span>
                                                                        <span class="loan-value">{{ $v->card_number }}</span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="loan-name">• Transaction Id </span>
                                                                        <span class="loan-value">{{ $v->transaction_id }}</span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                                        
                                            </div>
                                        </div>
                                    </div>                                
                                @endforeach
                            @endif

                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </section> 

@endsection

@section("jsIndex")
@endsection
