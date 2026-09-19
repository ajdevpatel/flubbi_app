{{--
    Shared shell for every pl-service / bl-service step.

    Expects: $service, $step, $step_meta, $steps, $total_steps, $session_user, $loan
    A step view extends this and fills @section('stepBody'); it may also fill
    @section('stepSide') and @section('stepJs').
--}}
@extends('frontend.layoutIndex')

@php
    $progress_labels = [];
    foreach ($steps as $s_meta) {
        if (!array_key_exists($s_meta['no'], $progress_labels)) {
            $progress_labels[$s_meta['no']] = $s_meta['label'];
        }
    }
    $current_no = (int) $step_meta['no'];
@endphp

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

            <div class="row">
                <div class="col-12">
                    <div class="fl-progress">
                        <div class="fl-progress__count">
                            Step {{ $current_no }} of {{ $total_steps }} &mdash;
                            {{ $progress_labels[$current_no] ?? '' }}
                        </div>
                        <div class="fl-progress__track">
                            @foreach ($progress_labels as $p_no => $p_label)
                                <div
                                    class="fl-progress__item {{ $p_no < $current_no ? 'is-done' : '' }} {{ $p_no == $current_no ? 'is-active' : '' }}">
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
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7 col-xl-8">
                    <div class="fl-card">
                        <div class="fl-card__head">
                            <span class="fl-card__eyebrow">{{ $service['short'] }} &middot; Step {{ $current_no }}</span>
                            <h2 class="fl-card__title">{{ $step_meta['title'] }}</h2>
                            @hasSection('stepSubtitle')
                                <p class="fl-card__sub">@yield('stepSubtitle')</p>
                            @endif
                        </div>

                        <div class="fl-inline-msg"></div>

                        @yield('stepBody')
                    </div>
                </div>

                <div class="col-lg-5 col-xl-4">
                    <div class="fl-side">
                        @hasSection('stepSide')
                            @yield('stepSide')
                        @else
                            <div class="fl-summary">
                                <h4>Your application</h4>

                                <div class="fl-summary__row">
                                    <span>Service</span>
                                    <span>{{ $service['label'] }}</span>
                                </div>

                                @if (!empty($session_user->name))
                                    <div class="fl-summary__row">
                                        <span>Name</span>
                                        <span>{{ $session_user->name }}</span>
                                    </div>
                                @endif

                                @if (!empty($session_user->phone))
                                    <div class="fl-summary__row">
                                        <span>Mobile</span>
                                        <span>{{ $session_user->phone }}</span>
                                    </div>
                                @endif

                                @if (!empty($loan->application_no))
                                    <div class="fl-summary__row">
                                        <span>Application no</span>
                                        <span>{{ $loan->application_no }}</span>
                                    </div>
                                @endif

                                @if (!empty($loan->eligible_amount))
                                    <div class="fl-summary__row">
                                        <span>Offer amount</span>
                                        <span>&#8377; {{ number_format((float) $loan->eligible_amount) }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="fl-note">
                                <i class="bi bi-shield-check"></i>
                                <div>
                                    Your progress is saved after every step. Close this page any time and continue from
                                    exactly where you left off.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('jsIndex')
    <script src="{{ asset('assets/app/loanService.js') }}"></script>
    @yield('stepJs')
@endsection
