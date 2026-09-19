{{-- Landing page for /pl-service and /bl-service (same view, $service differs). --}}
@extends('frontend.layoutIndex')

@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('assets/app/loanService.css') }}">
@endsection

@section('bodyIndex')
    <div class="breadcumb-area d-flex">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="breadcumb-content">
                        <div class="breadcumb-title">
                            <h4>{{ $service['label'] }}</h4>
                        </div>
                        <ul>
                            <li><a href="{{ route('_homeIndex') }}"><i class="bi bi-house-door-fill"></i> Home</a></li>
                            <li class="rotates"><i class="bi bi-slash-lg"></i>{{ $service['label'] }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================ hero ============================ --}}
    <section class="fl-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <span class="fl-card__eyebrow">{{ $service['short'] }} &middot; 100% online</span>
                    <h1>Get a pre-approved {{ strtolower($service['label']) }} offer in minutes</h1>
                    <p>{{ $service['tagline'] }} Check your eligibility with a few details &mdash; no paperwork, no
                        branch visit, and it never affects your credit score.</p>

                    <div class="fl-stats">
                        <div class="fl-stat">
                            <strong>&#8377; 8.75 L</strong>
                            <span>Offers up to</span>
                        </div>
                        <div class="fl-stat">
                            <strong>{{ $interest_rate }}%*</strong>
                            <span>Rate from</span>
                        </div>
                        <div class="fl-stat">
                            <strong>30+</strong>
                            <span>Lending partners</span>
                        </div>
                    </div>

                    <ul class="fl-ticks">
                        <li><i class="bi bi-check-circle-fill"></i> Eligibility check in under 2 minutes</li>
                        <li><i class="bi bi-check-circle-fill"></i> Apply yourself, or let a Flubbi agent handle it</li>
                        <li><i class="bi bi-check-circle-fill"></i> Your progress is saved at every step</li>
                    </ul>

                    <div class="fl-actions">
                        <a class="fl-btn" href="{{ route('_' . $service['type'] . 'ServiceStart') }}">
                            Check my eligibility <i class="bi bi-arrow-right"></i>
                        </a>
                        <a class="fl-btn fl-btn--ghost" href="{{ route('_contactusPost') }}">
                            <i class="bi bi-telephone"></i> Talk to us
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 d-none d-lg-block">
                    <img src="{{ asset('assets/images/home_3/about_thumb.png') }}" alt="{{ $service['label'] }}"
                        style="width:100%;height:auto;">
                </div>
            </div>
        </div>
    </section>

    {{-- ========================= how it works ========================= --}}
    <section class="fl-how">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title style_three style_four text-center">
                        <h4>HOW IT WORKS</h4>
                        <h1>Seven simple steps</h1>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                @php
                    $how = [
                        ['Verify your mobile', 'Name and mobile number, confirmed with an OTP.'],
                        ['Basic details', 'Email, PIN code, city and state.'],
                        ['Employment', 'Tell us if you are salaried or self employed.'],
                        ['Income details', 'Monthly income, CIBIL band, running EMI and the amount you need.'],
                        ['Pre-approved offer', 'See the amount you qualify for and pick a tenure.'],
                        ['Choose your route', 'Apply yourself, or hire a Flubbi agent to do it for you.'],
                        ['Pay and proceed', 'Pay the one time fee and get your lender links right away.'],
                    ];
                @endphp

                @foreach ($how as $k => $row)
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="fl-how__item">
                            <div class="fl-how__no">{{ $k + 1 }}</div>
                            <h5>{{ $row[0] }}</h5>
                            <p>{{ $row[1] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================ plans ============================ --}}
    <section class="fl-service">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title style_three style_four text-center">
                        <h4>TWO WAYS TO APPLY</h4>
                        <h1>Pick what suits you</h1>
                    </div>
                </div>
            </div>

            <div class="row g-3 justify-content-center">
                @foreach ([$fee_self, $fee_consultant] as $plan)
                    <div class="col-lg-5 col-md-6 col-12">
                        <div class="fl-plan">
                            <h4>{{ $plan['label'] }}</h4>
                            <div class="fl-plan__price">
                                &#8377; {{ number_format($plan['total_amount'], 2) }}
                                <small>
                                    &#8377; {{ number_format($plan['base_amount'], 2) }} +
                                    {{ rtrim(rtrim(number_format($plan['gst_rate'], 2), '0'), '.') }}% GST
                                    (&#8377; {{ number_format($plan['gst_amount'], 2) }})
                                </small>
                            </div>
                            <p>{{ $plan['note'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-lg-12 text-center" style="margin-top:34px;">
                    <a class="fl-btn" href="{{ route('_' . $service['type'] . 'ServiceStart') }}">
                        Start my application <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- =========================== partners =========================== --}}
    @if (count($partners))
        <section class="fl-how">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section_title style_three style_four text-center">
                            <h4>OUR LENDING PARTNERS</h4>
                            <h1>Backed by RBI regulated lenders</h1>
                        </div>
                    </div>
                </div>
                <div class="fl-partners">
                    @foreach ($partners as $partner)
                        <img src="{{ asset('banks/' . $partner->logo) }}" alt="{{ $partner->label }}"
                            title="{{ $partner->label }}" loading="lazy">
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-lg-12 text-center" style="margin-top:30px;">
                        <p class="fl-consent" style="margin:0;">
                            *Interest rates, eligibility and final approval are decided solely by the lending partner.
                            Flubbi is a technology and service provider, not a lender.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
