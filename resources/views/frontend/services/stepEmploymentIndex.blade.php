{{-- Step 3 - salaried / self employed --}}
@extends('frontend.services.stepLayoutIndex')

@section('stepSubtitle')
    This decides which lenders and offers fit you.
@endsection

@section('stepBody')
    <form action="{{ $urls['employment'] }}" method="POST" class="js-fl-step" novalidate>
        @csrf

        <div class="row">
            <div class="col-md-6">
                <label class="fl-choice">
                    <input type="radio" name="employment_type" value="salaried"
                        @checked(($loan->employment_type ?? 'salaried') === 'salaried')>
                    <span class="fl-choice__tick"></span>
                    <span class="fl-choice__body">
                        <i class="bi bi-briefcase fl-choice__icon"></i>
                        <span class="fl-choice__title">Salaried</span>
                        <span class="fl-choice__text">You receive a monthly salary from a private or government
                            employer.</span>
                    </span>
                </label>
            </div>
            <div class="col-md-6">
                <label class="fl-choice">
                    <input type="radio" name="employment_type" value="self_employed"
                        @checked(($loan->employment_type ?? '') === 'self_employed')>
                    <span class="fl-choice__tick"></span>
                    <span class="fl-choice__body">
                        <i class="bi bi-shop fl-choice__icon"></i>
                        <span class="fl-choice__title">Self employed</span>
                        <span class="fl-choice__text">You run a business, trade, practice or work as a
                            professional.</span>
                    </span>
                </label>
            </div>
        </div>

        <div class="fl-actions">
            <a class="fl-btn fl-btn--ghost" href="{{ $urls['profile'] }}"><i class="bi bi-arrow-left"></i> Back</a>
            <button type="submit" class="fl-btn fl-btn--grow">
                Continue <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>
@endsection
