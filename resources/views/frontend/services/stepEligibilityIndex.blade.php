{{-- Step 4 - Monthly Income + CIBIL + Existing EMI + Loan Required (spec order) --}}
@extends('frontend.services.stepLayoutIndex')

@section('stepSubtitle')
    Just a few numbers to work out your pre-approved offer.
@endsection

@section('stepBody')
    <form action="{{ $urls['eligibility'] }}" method="POST" class="js-fl-step" autocomplete="off" novalidate>
        @csrf
        {{-- purpose is defaulted per service for now; the dropdown can come back any time --}}
        <input type="hidden" name="loan_purpose_id" value="{{ $service['default_purpose_id'] }}">

        <div class="row">
            <div class="col-md-6">
                <div class="fl-field">
                    <label class="fl-field__label" for="monthly_income">Monthly income <span class="fl-req">*</span></label>
                    <div class="fl-field__prefix">
                        <span>&#8377;</span>
                        <input class="fl-field__control numeric" type="text" name="monthly_income" id="monthly_income"
                            inputmode="numeric" placeholder="e.g. 45000" maxlength="9"
                            value="{{ isset($loan->monthly_income) ? (int) $loan->monthly_income : '' }}" required autofocus>
                    </div>
                    <span class="fl-field__hint">Net in-hand, per month</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="fl-field">
                    <label class="fl-field__label" for="cibil_score">CIBIL score <span class="fl-req">*</span></label>
                    <select class="fl-field__control" name="cibil_score" id="cibil_score" required>
                        <option value="">Select your score range</option>
                        @foreach ($cibil_scores as $cs)
                            <option value="{{ $cs->id }}" @selected(($loan->cibil_score ?? 0) == $cs->id)>{{ $cs->label }}</option>
                        @endforeach
                    </select>
                    <span class="fl-field__hint">An estimate is fine</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="fl-field">
                    <label class="fl-field__label" for="existing_emi">Existing EMI per month <span class="fl-req">*</span></label>
                    <div class="fl-field__prefix">
                        <span>&#8377;</span>
                        <input class="fl-field__control numeric" type="text" name="existing_emi" id="existing_emi"
                            inputmode="numeric" placeholder="0 if none" maxlength="9"
                            value="{{ isset($loan->existing_emi) ? (int) $loan->existing_emi : '' }}" required>
                    </div>
                    <span class="fl-field__hint">Total of all running loans / cards</span>
                </div>
            </div>
            <div class="col-md-6 d-none d-md-block"></div>

            {{-- loan amount: slider 10K - 30L with a typed box kept in sync --}}
            @php
                $amt_min = 10000;
                $amt_max = 3000000;
                $amt_step = 10000;
                $amt_val = (int) ($loan->eligible_amount ?? 500000);
                $amt_val = max($amt_min, min($amt_max, $amt_val));
            @endphp
            <div class="col-12">
                <div class="fl-field fl-amount">
                    <div class="fl-amount__head">
                        <label class="fl-field__label" for="loan_amount">Loan amount required <span class="fl-req">*</span></label>
                        <div class="fl-field__prefix fl-amount__box">
                            <span>&#8377;</span>
                            <input class="fl-field__control numeric" type="text" name="loan_amount" id="loan_amount"
                                inputmode="numeric" maxlength="7" value="{{ $amt_val }}" required
                                data-min="{{ $amt_min }}" data-max="{{ $amt_max }}" data-step="{{ $amt_step }}">
                        </div>
                    </div>
                    <div class="fl-amount__display" id="loan_amount_display">&#8377; {{ number_format($amt_val) }}</div>
                    <input type="range" class="fl-range" id="loan_amount_range" min="{{ $amt_min }}" max="{{ $amt_max }}"
                        step="{{ $amt_step }}" value="{{ $amt_val }}" aria-label="Loan amount slider">
                    <div class="fl-range__scale">
                        <span>&#8377; 10,000</span>
                        <span>&#8377; 30,00,000</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="fl-actions">
            <a class="fl-btn fl-btn--ghost" href="{{ $urls['employment'] }}"><i class="bi bi-arrow-left"></i> Back</a>
            <button type="submit" class="fl-btn fl-btn--grow">
                Get my offer <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>
@endsection

@section('stepJs')
    <script>
        // Slider <-> typed amount <-> big display, all kept in sync and clamped to 10K - 30L.
        (function () {
            var $box = $("#loan_amount"), $range = $("#loan_amount_range"), $show = $("#loan_amount_display");
            var min = parseInt($box.data("min"), 10), max = parseInt($box.data("max"), 10), step = parseInt($box.data("step"), 10);

            function fmt(n) { return "₹ " + Number(n).toLocaleString("en-IN"); }
            function clamp(n) { n = isNaN(n) ? min : n; return Math.max(min, Math.min(max, n)); }
            function paint(n) {
                $show.text(fmt(n));
                $range.val(n).css("--fl-range-fill", ((n - min) / (max - min) * 100) + "%");
            }

            $range.on("input change", function () {
                var n = parseInt(this.value, 10);
                $box.val(n);
                paint(n);
            });

            $box.on("input", function () {
                var n = parseInt(String(this.value).replace(/[^0-9]/g, ""), 10);
                if (!isNaN(n)) { paint(clamp(n)); }
            }).on("blur", function () {
                var n = clamp(parseInt(String(this.value).replace(/[^0-9]/g, ""), 10));
                n = Math.round(n / step) * step;
                $box.val(n);
                paint(n);
            });

            paint(clamp(parseInt($box.val(), 10)));
        })();
    </script>
@endsection
