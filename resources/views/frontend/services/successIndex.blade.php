@extends('frontend.services.stepLayoutIndex')

@section('stepBody')
    <div class="fl-result fl-result--ok">
        <i class="bi bi-check-circle-fill"></i>
        <h3>Payment successful</h3>
        <p>Application <strong>{{ $application_no }}</strong> for your {{ strtolower($service['label']) }} is submitted.</p>
    </div>

    @if ('self' === $login_type && $bank)
        <div class="fl-bank-picked__card fl-bank-picked__card--static">
            <span class="fl-bank__logo"><img src="{{ asset('banks/' . $bank->logo) }}" alt="{{ $bank->label }}"></span>
            <div>
                <small>Your lending partner</small>
                <strong>{{ ucwords($bank->label) }}</strong>
            </div>
        </div>
        <div class="fl-actions">
            <a class="fl-btn fl-btn--block" href="{{ $bank_link ?: '#' }}" target="_blank" rel="noopener">
                Open {{ ucwords($bank->label) }} application <i class="bi bi-box-arrow-up-right"></i>
            </a>
        </div>
        <p class="fl-field__hint">Complete your application on the partner's website. Keep your PAN and Aadhaar handy.</p>
    @else
        <div class="fl-next">
            <h5>What happens next</h5>
            <ol>
                <li>A Flubbi agent calls you within one working day.</li>
                <li>They collect your documents over WhatsApp and pick the best-fit lender.</li>
                <li>You get updates by SMS and email until disbursal.</li>
            </ol>
        </div>
        <div class="fl-actions">
            <a class="fl-btn fl-btn--ghost fl-btn--block" href="{{ route('_homeIndex') }}"><i class="bi bi-house-door"></i> Back to home</a>
        </div>
    @endif

    <div class="fl-resend">
        Need another loan?
        <a class="fl-link" href="{{ $urls['start'] }}?new=1">Start a new {{ strtolower($service['label']) }} application</a>
    </div>
@endsection

@section('stepFoot')
    Need help? Write to <a href="mailto:{{ config('web.store_data.support_mail') }}">{{ config('web.store_data.support_mail') }}</a>
    or call <a href="tel:{{ config('web.store_data.phone') }}">{{ config('web.store_data.phone') }}</a>.
@endsection
