{{-- Result - payment failed / cancelled --}}
@extends('frontend.services.stepLayoutIndex')

@section('stepBody')
    <div class="fl-result fl-result--err">
        <i class="bi bi-x-circle-fill"></i>
        <h3>Payment did not go through</h3>
        <p>No money has been deducted. If it was, it is refunded automatically by your bank within 5&ndash;7 working days.</p>
    </div>

    <div class="fl-actions">
        <a class="fl-btn fl-btn--block" href="{{ $urls['payment'] }}"><i class="bi bi-arrow-repeat"></i> Try the payment again</a>
        <a class="fl-btn fl-btn--ghost fl-btn--block" href="{{ $urls['login-type'] }}">Change how I proceed</a>
    </div>
@endsection

@section('stepFoot')
    Still stuck? Write to <a href="mailto:{{ config('web.store_data.support_mail') }}">{{ config('web.store_data.support_mail') }}</a>
    or call <a href="tel:{{ config('web.store_data.phone') }}">{{ config('web.store_data.phone') }}</a>.
@endsection
