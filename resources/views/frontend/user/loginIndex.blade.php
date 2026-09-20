@extends('frontend.layoutIndex')

@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('assets/app/loanService.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/app/userPanel.css') }}">
@endsection

@section('bodyIndex')
    <div class="breadcumb-area fl-breadcumb d-flex">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="breadcumb-content">
                        <div class="breadcumb-title"><h4>Login</h4></div>
                        <ul>
                            <li><a href="{{ route('_homeIndex') }}"><i class="bi bi-house-door-fill"></i> Home</a></li>
                            <li class="rotates"><i class="bi bi-slash-lg"></i>Login</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="fl-service">
        <div class="container">
            <div class="up-login">
                <div class="fl-card">
                    <div class="fl-card__head" style="text-align:center;">
                        <span class="fl-card__eyebrow">My Account</span>
                        <h2 class="fl-card__title">Welcome back</h2>
                        <p class="fl-card__sub">
                            @if ($otp_sent)
                                Enter the 6 digit code sent to <strong>+91 {{ $phone }}</strong>.
                            @else
                                Log in with the mobile number you applied with.
                            @endif
                        </p>
                    </div>

                    <div class="fl-inline-msg"></div>

                    <form action="{{ route('_userLogin') }}" method="POST" class="js-fl-step" id="fl-login-form" autocomplete="off" novalidate>
                        @csrf

                        <div class="fl-field">
                            <label class="fl-field__label" for="phone">Mobile number <span class="fl-req">*</span></label>
                            <div class="fl-field__prefix">
                                <span>+91</span>
                                <input class="fl-field__control numeric" type="tel" name="phone" id="phone" placeholder="10 digit mobile number"
                                    inputmode="numeric" maxlength="10" pattern="[0-9]{10}" value="{{ $phone }}" required @readonly($otp_sent) @if (!$otp_sent) autofocus @endif>
                            </div>
                        </div>

                        <div class="fl-actions" id="fl-send-wrap" @if ($otp_sent) hidden @endif>
                            <button type="button" class="fl-btn fl-btn--block" data-fl-post="{{ route('_userLoginSendOtp') }}" data-fl-form="#fl-login-form">
                                Send OTP <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>

                        <div class="fl-otp-wrap" id="fl-otp-wrap" @unless ($otp_sent) hidden @endunless>
                            <div class="fl-field">
                                <label class="fl-field__label" for="otp">One time password <span class="fl-req">*</span></label>
                                <input class="fl-field__control fl-otp numeric" type="text" name="otp" id="otp" inputmode="numeric" autocomplete="one-time-code"
                                    maxlength="6" pattern="[0-9]{6}" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;" data-fl-otp @if ($otp_sent) autofocus @endif>
                                <span class="fl-field__hint">Valid for 5 minutes. Do not share it with anyone.</span>
                            </div>
                            <div class="fl-actions">
                                <button type="submit" class="fl-btn fl-btn--block">Verify &amp; login <i class="bi bi-box-arrow-in-right"></i></button>
                            </div>
                            <div class="fl-resend">
                                Did not get the code?
                                <button type="button" class="fl-btn fl-btn--link" id="fl-resend-btn" data-fl-post="{{ route('_userLoginSendOtp') }}" data-fl-form="#fl-login-form" @if ($otp_sent && $cooldown) data-fl-cooldown="{{ $cooldown }}" @endif>Resend OTP</button>
                                &nbsp;&middot;&nbsp;
                                <a class="fl-link" href="{{ route('_userLogin') }}?change=1">Change number</a>
                            </div>
                        </div>
                    </form>

                    <div class="fl-consent" style="text-align:center;">
                        New to Flubbi? <a href="{{ route('_personalServiceIndex') }}">Check your loan eligibility</a> &mdash; your account is created on the way.
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('jsIndex')
    <script src="{{ asset('assets/app/loanService.js') }}"></script>
@endsection
