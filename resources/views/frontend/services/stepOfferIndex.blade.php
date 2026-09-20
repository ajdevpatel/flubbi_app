@extends('frontend.services.stepLayoutIndex')

@section('stepSubtitle')
    Based on your income and running EMIs. Pick a tenure to see the EMI.
@endsection

@section('stepBody')
    <div class="fl-offer">
        <div class="fl-offer__badge"><i class="bi bi-patch-check-fill"></i> Congratulations, you are eligible</div>
        <div class="fl-offer__amount">&#8377; {{ number_format($offer_amount) }}</div>
        <div class="fl-offer__meta">
            <span>Interest from <strong>{{ rtrim(rtrim(number_format($rate, 2), '0'), '.') }}% p.a.*</strong></span>
            <span>Eligible up to <strong>&#8377; {{ number_format($eligible_amount) }}</strong></span>
        </div>
    </div>

    <form action="{{ $urls['offer'] }}" method="POST" class="js-fl-step" novalidate>
        @csrf
        <input type="hidden" name="loan_amount" value="{{ (int) $offer_amount }}">
        <input type="hidden" name="interest_rate" value="{{ $rate }}">

        <label class="fl-field__label">Choose your tenure <span class="fl-req">*</span></label>
        <div class="row fl-tenures">
            @foreach ($tenures as $t)
                <div class="col-6 col-md-4">
                    <label class="fl-tenure">
                        <input type="radio" name="loan_emi" value="{{ $t['months'] }}" @checked($selected_tenure == $t['months'])>
                        <span class="fl-tenure__box">
                            <span class="fl-tenure__months">{{ $t['months'] }} <small>months</small></span>
                            <span class="fl-tenure__emi">&#8377; {{ $t['emi'] }}<small>/mo</small></span>
                        </span>
                    </label>
                </div>
            @endforeach
        </div>

        <div class="fl-actions">
            <a class="fl-btn fl-btn--ghost" href="{{ $urls['eligibility'] }}"><i class="bi bi-arrow-left"></i> Edit details</a>
            <button type="submit" class="fl-btn fl-btn--grow">
                Accept offer <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>
@endsection

@section('stepFoot')
    *EMI shown is indicative. The final amount, rate and tenure are decided solely by the lending partner after
    verification. By continuing you agree to Flubbi's
    <a href="{{ route('_termsAndConditionsPost') }}" target="_blank">Terms &amp; Conditions</a>.
@endsection
