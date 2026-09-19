{{-- Step 6 - self login vs hire agent --}}
@extends('frontend.services.stepLayoutIndex')

@section('stepSubtitle')
    Both routes give you the same pre-approved offer. Pick how much help you want.
@endsection

@section('stepBody')
    <form action="{{ $urls['login-type'] }}" method="POST" class="js-fl-step" novalidate>
        @csrf

        <div class="row">
            @foreach ([$fee_self, $fee_consultant] as $plan)
                <div class="col-md-6">
                    <label class="fl-plan fl-plan--select">
                        <input type="radio" name="login_type" value="{{ $plan['login_type'] }}"
                            @checked($selected === $plan['login_type'])>
                        <span class="fl-plan__inner">
                            <span class="fl-choice__tick"></span>
                            @if ('consultant' === $plan['login_type'])
                                <span class="fl-plan__flag">Recommended</span>
                            @endif
                            <i class="fl-plan__icon bi {{ 'self' === $plan['login_type'] ? 'bi-person-check' : 'bi-headset' }}"></i>
                            <h4>{{ $plan['label'] }}</h4>
                            <span class="fl-plan__price">
                                &#8377; {{ number_format($plan['total_amount'], 2) }}
                                <small>&#8377; {{ number_format($plan['base_amount']) }} + {{ (int) $plan['gst_rate'] }}% GST &middot; one time</small>
                            </span>
                            <ul class="fl-plan__list">
                                @if ('self' === $plan['login_type'])
                                    <li><i class="bi bi-check2"></i> Instant access to 30+ lender apply links</li>
                                    <li><i class="bi bi-check2"></i> Apply directly on the lender's portal</li>
                                    <li><i class="bi bi-check2"></i> Email + WhatsApp support</li>
                                @else
                                    <li><i class="bi bi-check2"></i> Dedicated Flubbi agent on call</li>
                                    <li><i class="bi bi-check2"></i> Documents, follow-ups and lender coordination done for you</li>
                                    <li><i class="bi bi-check2"></i> Best-fit lender chosen for your profile</li>
                                @endif
                            </ul>
                        </span>
                    </label>
                </div>
            @endforeach
        </div>

        <div class="fl-actions">
            <a class="fl-btn fl-btn--ghost" href="{{ $urls['offer'] }}"><i class="bi bi-arrow-left"></i> Back</a>
            <button type="submit" class="fl-btn fl-btn--grow">
                Continue to payment <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>
@endsection
