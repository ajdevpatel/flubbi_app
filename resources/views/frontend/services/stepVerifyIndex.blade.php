@extends('frontend.services.stepLayoutIndex')

@section('stepSubtitle')
    @if ($otp_sent)
        Enter the 6 digit code sent to <strong>+91 {{ $phone }}</strong>.
    @else
        We will send a 6 digit OTP to confirm it is you.
    @endif
@endsection

@section('stepBody')
    <form action="{{ $urls['verify'] }}" method="POST" class="js-fl-step" id="fl-verify-form" autocomplete="off" novalidate>
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="fl-field">
                    <label class="fl-field__label" for="name">Full name <span class="fl-req">*</span></label>
                    <input class="fl-field__control alphabet" type="text" name="name" id="name"
                        placeholder="As per your PAN card" maxlength="100" value="{{ $name }}" required
                        @readonly($otp_sent) @if (!$otp_sent) autofocus @endif>
                </div>
            </div>
            <div class="col-md-6">
                <div class="fl-field">
                    <label class="fl-field__label" for="phone">Mobile number <span class="fl-req">*</span></label>
                    <div class="fl-field__prefix">
                        <span>+91</span>
                        <input class="fl-field__control numeric" type="tel" name="phone" id="phone"
                            placeholder="10 digit mobile number" inputmode="numeric" maxlength="10" pattern="[0-9]{10}"
                            value="{{ $phone }}" required @readonly($otp_sent)>
                    </div>
                    <span class="fl-field__hint">OTP will be sent to this number</span>
                </div>
            </div>
        </div>

        <div class="fl-actions" id="fl-send-wrap" @if ($otp_sent) hidden @endif>
            <button type="button" class="fl-btn fl-btn--block" data-fl-post="{{ $urls['send-otp'] }}"
                data-fl-form="#fl-verify-form">
                Send OTP <i class="bi bi-arrow-right"></i>
            </button>
        </div>

        <div class="fl-otp-wrap" id="fl-otp-wrap" @unless ($otp_sent) hidden @endunless>
            <div class="fl-field">
                <label class="fl-field__label" for="otp">One time password <span class="fl-req">*</span></label>
                <input class="fl-field__control fl-otp numeric" type="text" name="otp" id="otp" inputmode="numeric"
                    autocomplete="one-time-code" maxlength="6" pattern="[0-9]{6}"
                    placeholder="&bull;&bull;&bull;&bull;&bull;&bull;" data-fl-otp
                    @if ($otp_sent) autofocus @endif>
                <span class="fl-field__hint">Valid for 5 minutes. Do not share it with anyone.</span>
            </div>

            <div class="fl-actions">
                <button type="submit" class="fl-btn fl-btn--block">
                    Verify &amp; continue <i class="bi bi-arrow-right"></i>
                </button>
            </div>

            <div class="fl-resend">
                Did not get the code?
                <button type="button" class="fl-btn fl-btn--link" id="fl-resend-btn" data-fl-post="{{ $urls['send-otp'] }}"
                    data-fl-form="#fl-verify-form" @if ($otp_sent) data-fl-cooldown="{{ $cooldown }}" @endif>Resend OTP</button>
                &nbsp;&middot;&nbsp;
                <a class="fl-link" href="{{ $urls['verify'] }}?change=1">Change number</a>
            </div>
        </div>
    </form>
@endsection
