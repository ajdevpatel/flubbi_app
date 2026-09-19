@extends('frontend.layoutIndex')


@section('bodyIndex')

    <!-- Banner Start -->
    <section class="banner">
        <div class="container ">
            <div class="row gy-4 gy-sm-0 align-items-center">
                <div class="col-12 col-sm-6">
                    <div class="banner__content">
                        <h1 class="banner__title display-4 wow fadeInLeft" data-wow-duration="0.8s">Profile</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb wow fadeInRight" data-wow-duration="0.8s">
                                <li class="breadcrumb-item"><a href="{{ route('_homeIndex') }}">Home</a></li>
                                <li class="breadcrumb-item">User</li>
                                <li class="breadcrumb-item active" aria-current="page">Profile</li>
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


                            <div class="row">
                                <div class="col-12 col-xl-8">
                                    <form action="{{ route('_userRaiseRequestIndex') }}" id="_raiseRequestModule"
                                        method="POST" enctype="multipart/form-data"
                                        class="reviews-details__part write-commnets" autocomplete="off">
                                        @csrf
                                        <h4 class="average-reviews__title">Raise a Request</h4>
                                        <div class="d-grid gap-xxl-4 gap-3">
                                            <div class="input-single">
                                                <label class="label" for="email">Query Reason</label>
                                                <select name="req_reason" class="form-control" data-allow-clear="true">
                                                    <option value="">Select Reason</option>
                                                    @if ([] != $support_reasons)
                                                        @foreach ($support_reasons as $k => $v)
                                                            <option value="{{ $v->id }}">
                                                                {{ Str::ucfirst($v->label) }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="input-single">
                                                <label class="label" for="name">Request Message</label>
                                                <textarea class="form-control" name="req_message" rows="4" placeholder="Enter Your Message..."></textarea>
                                            </div>
                                        </div>
                                        <div class="section__cta text-start mt-xl-3 mt-2">
                                            <button type="submit" class="btn_theme btn_theme_active"> Submit Query <i
                                                    class="bi bi-arrow-up-right"></i><span></span></button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-12 col-xl-4">
                                    <div class="more-help">
                                        <h3 class="contact__title">Need more help?</h3>
                                        <div class="more-help__content">
                                            <div class="card card--small">
                                                <div class="card--small-icon">
                                                    <i class="bi bi-telephone"></i>
                                                </div>
                                                <div class="card--small-content">
                                                    <h5 class="card--small-title">Call Now</h5>
                                                    <div class="gap-1 flex-column">
                                                        <a href="tel:{{ config('web.store_data.phone') }}"
                                                            class="card--small-call">{{ config('web.store_data.phone') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card--small">
                                                <div class="card--small-icon">
                                                    <i class="bi bi-envelope-open"></i>
                                                </div>
                                                <div class="card--small-content">
                                                    <h5 class="card--small-title">Email Address</h5>
                                                    <div class="gap-1 flex-column">
                                                        <a href="mailto:{{ config('web.store_data.mail') }}"
                                                            class="card--small-call">{{ config('web.store_data.mail') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card--small">
                                                <div class="card--small-icon">
                                                    <i class="bi bi-geo-alt"></i>
                                                </div>
                                                <div class="card--small-content">
                                                    <h5 class="card--small-title">Location</h5>
                                                    <div class="gap-1 flex-column">
                                                        <p>{{ config('web.store_data.location') }}</p>
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
            </div>
        </div>
    </section>

@endsection

@section('jsIndex')
    <script src="{{ asset('assets/app/userPanelIndex.js') }}"></script>
@endsection
