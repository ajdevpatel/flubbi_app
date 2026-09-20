@extends('frontend.layoutIndex')

@php
    $progress_labels = [];
    foreach ($steps as $s_meta) {
        if (!array_key_exists($s_meta['no'], $progress_labels)) {
            $progress_labels[$s_meta['no']] = $s_meta['label'];
        }
    }
    $current_no = (int) $step_meta['no'];
    $is_result = in_array($step, ['success', 'failed'], true);
@endphp

@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('assets/app/loanService.css') }}">
@endsection

@section('bodyIndex')
    <div class="breadcumb-area fl-breadcumb d-flex">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="breadcumb-content">
                        <div class="breadcumb-title">
                            <h4>{{ $service['label'] }} Application</h4>
                        </div>
                        <ul>
                            <li><a href="{{ route('_homeIndex') }}"><i class="bi bi-house-door-fill"></i> Home</a></li>
                            <li class="rotates"><i class="bi bi-slash-lg"></i>
                                <a href="{{ route('_' . $service['type'] . 'ServiceIndex') }}">{{ $service['label'] }}</a>
                            </li>
                            <li class="rotates"><i class="bi bi-slash-lg"></i>Apply</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="fl-service">
        <div class="container">
            <div class="row fl-row">

                <div class="col-lg-5 order-2 order-lg-1">
                    <div class="fl-info">
                        <span class="fl-card__eyebrow">{{ $service['short'] }} &middot; 100% digital</span>
                        <h2 class="fl-info__title">
                            Get up to <span>&#8377; 8,75,000</span><br>
                            {{ $service['label'] }} in just a few clicks!
                        </h2>
                        <p class="fl-info__sub">Paperless process &nbsp;|&nbsp; Offer in minutes &nbsp;|&nbsp; RBI regulated
                            partners</p>

                        <ul class="fl-ticks">
                            <li><i class="bi bi-check-circle-fill"></i> No impact on your credit score</li>
                            <li><i class="bi bi-check-circle-fill"></i> Apply yourself or hire a Flubbi agent</li>
                            <li><i class="bi bi-check-circle-fill"></i> Progress saved after every step</li>
                        </ul>

                        @if (!empty($session_user->name) || !empty($loan->application_no))
                            <div class="fl-summary">
                                <h4>Your application</h4>
                                @if (!empty($session_user->name))
                                    <div class="fl-summary__row"><span>Name</span><span>{{ $session_user->name }}</span></div>
                                @endif
                                @if (!empty($session_user->phone))
                                    <div class="fl-summary__row"><span>Mobile</span><span>{{ $session_user->phone }}</span></div>
                                @endif
                                @if (!empty($loan->application_no))
                                    <div class="fl-summary__row"><span>Application no</span><span>{{ $loan->application_no }}</span></div>
                                @endif
                                @if (!empty($loan->eligible_amount))
                                    <div class="fl-summary__row"><span>Offer amount</span><span>&#8377; {{ number_format((float) $loan->eligible_amount) }}</span></div>
                                @endif
                            </div>
                        @endif

                        @if (count($partners))
                            <div class="fl-info__partners">
                                <h6>Finance facility by our NBFC / lending partners</h6>
                                <div class="fl-partners fl-partners--compact">
                                    @foreach ($partners as $partner)
                                        <img src="{{ asset('banks/' . $partner->logo) }}" alt="{{ $partner->label }}"
                                            title="{{ $partner->label }}" loading="lazy">
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-7 order-1 order-lg-2">
                    <div class="fl-card">

                        @unless ($is_result)
                            <div class="fl-progress">
                                <div class="fl-progress__count">
                                    Step {{ $current_no }} of {{ $total_steps }} &mdash; {{ $progress_labels[$current_no] ?? '' }}
                                </div>
                                <div class="fl-progress__track">
                                    @foreach ($progress_labels as $p_no => $p_label)
                                        <div class="fl-progress__item {{ $p_no < $current_no ? 'is-done' : '' }} {{ $p_no == $current_no ? 'is-active' : '' }}">
                                            <div class="fl-progress__dot">
                                                @if ($p_no < $current_no)
                                                    <i class="bi bi-check-lg"></i>
                                                @else
                                                    {{ $p_no }}
                                                @endif
                                            </div>
                                            <span class="fl-progress__label">{{ $p_label }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endunless

                        <div class="fl-card__head">
                            <h2 class="fl-card__title">{{ $step_meta['title'] }}</h2>
                            @hasSection('stepSubtitle')
                                <p class="fl-card__sub">@yield('stepSubtitle')</p>
                            @endif
                        </div>

                        <div class="fl-inline-msg"></div>

                        @yield('stepBody')

                        @hasSection('stepFoot')
                            <div class="fl-consent">@yield('stepFoot')</div>
                        @else
                            <div class="fl-consent">
                                By continuing you agree to Flubbi's
                                <a href="{{ route('_termsAndConditionsPost') }}" target="_blank">Terms &amp; Conditions</a> and
                                <a href="{{ route('_privacyPolicyPost') }}" target="_blank">Privacy Policy</a>, and consent to be
                                contacted by SMS, email and WhatsApp.
                            </div>
                        @endif
                    </div>

                    @if ($preview)
                        <div class="fl-preview-tag">Design preview &mdash; nothing is saved yet</div>
                    @endif
                </div>

            </div>
        </div>
    </section>
@endsection

@section('jsIndex')
    <script src="{{ asset('assets/app/loanService.js') }}"></script>
    @yield('stepJs')
@endsection
