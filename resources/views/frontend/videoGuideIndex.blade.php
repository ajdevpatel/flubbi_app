@extends('frontend.layoutIndex')

@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('assets/app/loanService.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/app/videoGuide.css') }}">
@endsection

@section('bodyIndex')
    @php
        $apply_personal = route('_personalServiceStart', ['login_type' => $guide['login_type']]);
        $apply_business = route('_businessServiceStart', ['login_type' => $guide['login_type']]);
    @endphp

    <div class="breadcumb-area fl-breadcumb d-flex">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="breadcumb-content">
                        <div class="breadcumb-title">
                            <h4>{{ $guide['label'] }} Video Guide</h4>
                        </div>
                        <ul>
                            <li><a href="{{ route('_homeIndex') }}"><i class="bi bi-house-door-fill"></i> Home</a></li>
                            <li class="rotates"><i class="bi bi-slash-lg"></i>{{ $guide['label'] }} Video Guide</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="fl-service fl-guide">
        <div class="container">
            <div class="fl-guide__tabs">
                @foreach ($guides as $slug => $row)
                    <a class="fl-guide__tab {{ $slug === $guide_slug ? 'is-active' : '' }}"
                        href="{{ route('_videoGuideIndex', $slug) }}">
                        <i class="bi {{ $row['icon'] }}"></i> {{ $row['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="fl-guide__hero {{ $video_vertical ? 'fl-guide__hero--vertical' : '' }}">
                <div class="fl-guide__head">
                    <span class="fl-card__eyebrow">Video guide</span>
                    <h1>{{ $guide['title'] }}</h1>
                    <p>{{ $guide['intro'] }}</p>
                </div>

                <div class="fl-guide__video">
                    @if ('' !== $video_id)
                        <iframe src="https://www.youtube-nocookie.com/embed/{{ $video_id }}?rel=0"
                            title="{{ $guide['label'] }} video guide" loading="lazy"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    @else
                        <div class="fl-guide__soon">
                            <i class="bi bi-play-circle"></i>
                            <strong>Video coming soon</strong>
                            <span>Follow the steps below in the meantime.</span>
                        </div>
                    @endif
                </div>

                <div class="fl-guide__extra">
                    <ul class="fl-ticks">
                        @foreach ($guide['notes'] as $note)
                            <li><i class="bi bi-check-circle-fill"></i> {{ $note }}</li>
                        @endforeach
                    </ul>

                    <div class="fl-actions">
                        <a class="fl-btn" href="{{ $apply_personal }}">
                            Apply for Personal Loan <i class="bi bi-arrow-right"></i>
                        </a>
                        <a class="fl-btn fl-btn--ghost" href="{{ $apply_business }}">
                            Apply for Business Loan <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="fl-how">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title style_three style_four text-center">
                        <h4>STEP BY STEP</h4>
                        <h1>{{ $guide['label'] }} in {{ count($guide['steps']) }} steps</h1>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                @foreach ($guide['steps'] as $k => $step)
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="fl-how__item">
                            <div class="fl-how__no">{{ $k + 1 }}</div>
                            <h5>{{ $step[0] }}</h5>
                            <p>{{ $step[1] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="fl-guide__foot">
                <div class="fl-actions">
                    <a class="fl-btn" href="{{ $apply_personal }}">
                        Apply for Personal Loan <i class="bi bi-arrow-right"></i>
                    </a>
                    <a class="fl-btn fl-btn--ghost" href="{{ $apply_business }}">
                        Apply for Business Loan <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <p class="fl-consent">
                    Loan approval and disbursal are at the sole discretion of the lending partner. Flubbi is a technology
                    and service provider, not a lender. Need help? Write to
                    <a href="mailto:{{ config('web.store_data.support_mail') }}">{{ config('web.store_data.support_mail') }}</a>
                    or call <a href="tel:{{ config('web.store_data.phone') }}">{{ config('web.store_data.phone') }}</a>.
                </p>
            </div>
        </div>
    </section>
@endsection
