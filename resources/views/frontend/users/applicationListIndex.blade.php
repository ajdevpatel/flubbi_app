@extends('frontend.layoutIndex')


@section('bodyIndex')

    <!-- Banner Start -->
    <section class="banner">
        <div class="container ">
            <div class="row gy-4 gy-sm-0 align-items-center">
                <div class="col-12 col-sm-6">
                    <div class="banner__content">
                        <h1 class="banner__title display-4 wow fadeInLeft" data-wow-duration="0.8s">Application List</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb wow fadeInRight" data-wow-duration="0.8s">
                                <li class="breadcrumb-item"><a href="{{ route('_homeIndex') }}">Home</a></li>
                                <li class="breadcrumb-item">User</li>
                                <li class="breadcrumb-item active" aria-current="page">Application List</li>
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
                    @include('frontend.users.menuIndex')
                </div>
                <div class="col-12 col-xl-9 order-1 order-xl-0">
                    <div class="reviews-details__area">
                        <div class="reviews-details__part">
                            <h4 class="average-reviews__title">Application List</h4>

                            @if ([] != $application_list)
                                @foreach ($application_list as $k => $v)
                                    <div class="loan-reviews loan-reviews--quaternary">
                                        <div class="loan-reviews_card card">
                                            <div class="loan-reviews__part-two">
                                                <div class="space_between">
                                                    <h4>{{ Str::ucfirst($v->loan_types) }}
                                                        {{ config('web.webapp.loan_word.user_panel_view_file') }}</h4>
                                                    <div class="gap-2 comments-title mb-2">
                                                        <a href="#" class="btn_theme btn_theme_active"> View More <i
                                                                class="bi bi-eye"></i><span></span></a>
                                                    </div>
                                                </div>
                                                <div class="reviews-inner">
                                                    <div class="row g-xl-4 g-3">
                                                        <div class="col-lg-6 col-md-6">
                                                            <div class="terms-box">
                                                                <span
                                                                    class="text-uppercase fw-semibold title">{{ Str::ucfirst(config('web.webapp.loan_word.user_panel_view_file')) }}
                                                                    Info</span>
                                                                <ul>
                                                                    <li>
                                                                        <span class="loan-name">• Status</span>
                                                                        <span
                                                                            class="loan-value text-{{ $v->status_class }}">{{ $v->status_label }}</span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="loan-name">• Date</span>
                                                                        <span class="loan-value">{{ $v->rec_date }}</span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="loan-name">•
                                                                            {{ config('web.webapp.loan_word.user_panel_view_file') }}
                                                                            Purpose </span>
                                                                        <span
                                                                            class="loan-value">{{ $v->loan_purposes }}</span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="loan-name">•
                                                                            {{ config('web.webapp.loan_word.user_panel_view_file') }}
                                                                            Amount </span>
                                                                        <span
                                                                            class="loan-value">{{ $v->loan_amount }}</span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="loan-name">•
                                                                            {{ config('web.webapp.loan_word.user_panel_view_file') }}
                                                                            Tenure </span>
                                                                        <span class="loan-value">{{ $v->loantenure }}
                                                                            Months</span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6">
                                                            <div class="terms-box">
                                                                <span class="text-uppercase fw-semibold title">Other
                                                                    INFO</span>
                                                                <ul>
                                                                    <li>
                                                                        <span class="loan-name">• Cibil Score </span>
                                                                        <span
                                                                            class="loan-value">{{ $v->cibil_scores }}</span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="loan-name">• Monthly Income </span>
                                                                        <span class="loan-value">{{ $v->income }}</span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="loan-name">• Current EMI </span>
                                                                        <span
                                                                            class="loan-value">₹{{ $v->emi_paying }}/-</span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="loan-name">• EMI Bounce </span>
                                                                        <span
                                                                            class="loan-value">{{ $v->emi_bounce }}</span>
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

@section('jsIndex')
@endsection
