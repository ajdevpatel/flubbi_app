{{-- Step 7 - platform fee payment (Razorpay) --}}
@extends('frontend.services.stepLayoutIndex')

@section('stepSubtitle')
    One time {{ strtolower($fee['label']) }} fee. Secured by Razorpay.
@endsection

@section('stepBody')
    <div class="fl-fee">
        <div class="fl-fee__head">
            <i class="bi {{ 'self' === $fee['login_type'] ? 'bi-person-check' : 'bi-headset' }}"></i>
            <div>
                <strong>{{ $fee['label'] }}</strong>
                <span>{{ $fee['note'] }}</span>
            </div>
            <a class="fl-link" href="{{ $urls['login-type'] }}">Change</a>
        </div>
        <div class="fl-fee__row"><span>Platform fee</span><span>&#8377; {{ number_format($fee['base_amount'], 2) }}</span></div>
        <div class="fl-fee__row"><span>GST @ {{ (int) $fee['gst_rate'] }}%</span><span>&#8377; {{ number_format($fee['gst_amount'], 2) }}</span></div>
        <div class="fl-fee__row fl-fee__row--total"><span>Total payable</span><span>&#8377; {{ number_format($fee['total_amount'], 2) }}</span></div>
    </div>

    <form action="{{ $urls['payment-verify'] }}" method="POST" class="js-fl-step" novalidate>
        @csrf
        <input type="hidden" name="login_type" value="{{ $fee['login_type'] }}">

        <div class="fl-actions">
            <button type="submit" class="fl-btn fl-btn--block fl-btn--pay">
                <i class="bi bi-lock-fill"></i> Pay &#8377; {{ number_format($fee['total_amount'], 2) }} securely
            </button>
        </div>

        <div class="fl-paybadges">
            <span><i class="bi bi-shield-lock"></i> 256-bit encrypted</span>
            <span><i class="bi bi-credit-card"></i> UPI &middot; Cards &middot; Net banking</span>
            <span><i class="bi bi-arrow-repeat"></i> Refund as per policy</span>
        </div>
    </form>
@endsection

@section('stepFoot')
    Payment is processed by Razorpay; Flubbi never sees your card or UPI details. See our
    <a href="{{ route('_refundPolicyPost') }}" target="_blank">Refund Policy</a> and
    <a href="{{ route('_termsAndConditionsPost') }}" target="_blank">Terms &amp; Conditions</a>.
@endsection
