@extends('frontend.layoutIndex')

@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('assets/app/loanService.css') }}">
    <style>
        .emi-result { background: linear-gradient(160deg, var(--fl-ink), #16264a); color: #fff; border-radius: var(--fl-radius); padding: 26px 24px; height: 100%; }
        .emi-result__label { font-size: 13px; letter-spacing: 0.8px; text-transform: uppercase; color: #a9b4c6; }
        .emi-result__value { font-size: 40px; line-height: 1.1; font-weight: 700; color: var(--fl-accent); margin: 6px 0 20px; }
        .emi-result__row { display: flex; justify-content: space-between; gap: 12px; padding: 10px 0; border-top: 1px solid rgba(255,255,255,0.12); font-size: 14.5px; }
        .emi-result__row span:first-child { color: #c5cede; }
        .emi-result__row strong { color: #fff; }
        .emi-bar { display: flex; height: 12px; border-radius: 8px; overflow: hidden; margin: 18px 0 8px; background: rgba(255,255,255,0.12); }
        .emi-bar__p { background: var(--fl-accent); }
        .emi-bar__i { background: #6fb1ff; }
        .emi-legend { display: flex; gap: 18px; font-size: 12.5px; color: #c5cede; }
        .emi-legend i { display: inline-block; width: 10px; height: 10px; border-radius: 3px; margin-right: 6px; vertical-align: middle; }
        .emi-field { background: var(--fl-soft); border: 1px solid var(--fl-line); border-radius: 12px; padding: 14px 16px 12px; margin-bottom: 16px; }
        .emi-field__head { display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; margin-bottom: 10px; }
        .emi-field__head .fl-field__label { margin: 0; }
        .emi-field__box { width: 150px; }
        .emi-field__box .fl-field__control { height: 42px; font-weight: 600; background: #fff; }
        .emi-field__box .fl-field__prefix > span, .emi-field__box .fl-field__suffix > span { left: auto; }
        .emi-field__suffix { position: relative; }
        .emi-field__suffix > span { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); font-size: 14px; font-weight: 600; color: var(--fl-muted); pointer-events: none; }
        .emi-field__prefix > span { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 14px; font-weight: 600; color: var(--fl-muted); pointer-events: none; }
        .emi-field__prefix .fl-field__control { padding-left: 32px; }
        @media (max-width: 575px) { .emi-field__box { width: 100%; } .emi-result__value { font-size: 32px; } }
    </style>
@endsection

@section('bodyIndex')
    <div class="breadcumb-area d-flex ">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="breadcumb-content">
                        <div class="breadcumb-title style_two">
                            <h4>EMI Calculator</h4>
                        </div>
                        <ul>
                            <li><a href="{{ route('_homeIndex') }}"><i class="bi bi-house-door-fill"></i> Home </a></li>
                            <li class="rotates"><i class="bi bi-slash-lg"></i>EMI Calculator</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="fl-service">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title style_three style_four text-center">
                        <h4>PLAN BEFORE YOU BORROW</h4>
                        <h1>Loan EMI Calculator</h1>
                    </div>
                </div>
            </div>

            <div class="fl-card">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="emi-field">
                            <div class="emi-field__head">
                                <label class="fl-field__label" for="emi_amount">Loan amount</label>
                                <div class="emi-field__box emi-field__prefix">
                                    <span>&#8377;</span>
                                    <input class="fl-field__control numeric" type="text" id="emi_amount" inputmode="numeric" maxlength="8" value="500000">
                                </div>
                            </div>
                            <input type="range" class="fl-range" id="emi_amount_range" min="10000" max="5000000" step="10000" value="500000" aria-label="Loan amount">
                            <div class="fl-range__scale"><span>&#8377; 10,000</span><span>&#8377; 50,00,000</span></div>
                        </div>

                        <div class="emi-field">
                            <div class="emi-field__head">
                                <label class="fl-field__label" for="emi_rate">Interest rate (per year)</label>
                                <div class="emi-field__box emi-field__suffix">
                                    <input class="fl-field__control" type="text" id="emi_rate" inputmode="decimal" maxlength="5" value="10.55">
                                    <span>%</span>
                                </div>
                            </div>
                            <input type="range" class="fl-range" id="emi_rate_range" min="5" max="36" step="0.05" value="10.55" aria-label="Interest rate">
                            <div class="fl-range__scale"><span>5%</span><span>36%</span></div>
                        </div>

                        <div class="emi-field">
                            <div class="emi-field__head">
                                <label class="fl-field__label" for="emi_months">Tenure</label>
                                <div class="emi-field__box emi-field__suffix">
                                    <input class="fl-field__control numeric" type="text" id="emi_months" inputmode="numeric" maxlength="3" value="36">
                                    <span>months</span>
                                </div>
                            </div>
                            <input type="range" class="fl-range" id="emi_months_range" min="6" max="84" step="6" value="36" aria-label="Tenure">
                            <div class="fl-range__scale"><span>6 months</span><span>84 months</span></div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="emi-result">
                            <div class="emi-result__label">Monthly EMI</div>
                            <div class="emi-result__value" id="emi_out">&#8377; 0</div>
                            <div class="emi-result__row"><span>Principal</span><strong id="emi_principal">&#8377; 0</strong></div>
                            <div class="emi-result__row"><span>Total interest</span><strong id="emi_interest">&#8377; 0</strong></div>
                            <div class="emi-result__row"><span>Total payment</span><strong id="emi_total">&#8377; 0</strong></div>
                            <div class="emi-bar"><div class="emi-bar__p" id="emi_bar_p" style="width:70%"></div><div class="emi-bar__i" id="emi_bar_i" style="width:30%"></div></div>
                            <div class="emi-legend"><span><i class="emi-bar__p"></i>Principal</span><span><i class="emi-bar__i"></i>Interest</span></div>
                        </div>
                    </div>
                </div>

                <div class="fl-actions" style="margin-top:20px;">
                    <a class="fl-btn" href="{{ route('_personalServiceStart') }}">Check Personal Loan eligibility <i class="bi bi-arrow-right"></i></a>
                    <a class="fl-btn fl-btn--ghost" href="{{ route('_businessServiceStart') }}">Business Loan <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="fl-consent">
                    Figures are indicative and calculated on a reducing-balance basis. Actual EMI, rate and tenure are decided by the lending partner at sanction.
                </div>
            </div>
        </div>
    </section>
@endsection

@section('jsIndex')
    <script>
        (function () {
            function inr(n) { return "₹ " + Math.round(n).toLocaleString("en-IN"); }
            function clamp(v, min, max) { v = isNaN(v) ? min : v; return Math.max(min, Math.min(max, v)); }
            function bind(boxId, rangeId, parse) {
                var $box = $("#" + boxId), $range = $("#" + rangeId);
                var min = parseFloat($range.attr("min")), max = parseFloat($range.attr("max"));
                function paint(v) { $range.val(v).css("--fl-range-fill", ((v - min) / (max - min) * 100) + "%"); }
                $range.on("input change", function () { $box.val(this.value); paint(parseFloat(this.value)); calc(); });
                $box.on("input", function () { var v = parse(this.value); if (!isNaN(v)) { paint(clamp(v, min, max)); calc(); } });
                $box.on("blur", function () { var v = clamp(parse(this.value), min, max); $box.val(v); paint(v); calc(); });
                paint(clamp(parse($box.val()), min, max));
            }
            function num(s) { return parseInt(String(s).replace(/[^0-9]/g, ""), 10); }
            function dec(s) { return parseFloat(String(s).replace(/[^0-9.]/g, "")); }
            function calc() {
                var p = clamp(num($("#emi_amount").val()), 10000, 5000000);
                var r = clamp(dec($("#emi_rate").val()), 5, 36) / 1200;
                var n = clamp(num($("#emi_months").val()), 6, 84);
                var emi = r > 0 ? p * r * Math.pow(1 + r, n) / (Math.pow(1 + r, n) - 1) : p / n;
                var total = emi * n, interest = total - p;
                $("#emi_out").text(inr(emi));
                $("#emi_principal").text(inr(p));
                $("#emi_interest").text(inr(interest));
                $("#emi_total").text(inr(total));
                var ip = Math.round(interest / total * 100);
                $("#emi_bar_p").css("width", (100 - ip) + "%");
                $("#emi_bar_i").css("width", ip + "%");
            }
            bind("emi_amount", "emi_amount_range", num);
            bind("emi_rate", "emi_rate_range", dec);
            bind("emi_months", "emi_months_range", num);
            calc();
        })();
    </script>
@endsection
