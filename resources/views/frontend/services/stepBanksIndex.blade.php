@extends('frontend.services.stepLayoutIndex')

@section('stepSubtitle')
    Payment received. Choose one partner &mdash; you will be taken to its application page.
@endsection

@section('stepBody')
    <form action="{{ $urls['banks'] }}" method="POST" class="js-fl-step" id="fl-bank-form" novalidate>
        @csrf
        <input type="hidden" name="bank_id" id="fl-bank-id" value="">

        <div class="fl-bank-grid" id="fl-bank-grid">
            @foreach ($banks as $bank)
                <button type="button" class="fl-bank" data-id="{{ $bank->id }}" data-label="{{ $bank->label }}"
                    data-link="{{ trim($bank->link) }}" title="{{ $bank->label }}">
                    <span class="fl-bank__logo"><img src="{{ asset('banks/' . $bank->logo) }}" alt="{{ $bank->label }}" loading="lazy"></span>
                    <span class="fl-bank__name">{{ ucwords($bank->label) }}</span>
                </button>
            @endforeach
        </div>

        <div class="fl-bank-picked" id="fl-bank-picked" hidden>
            <div class="fl-bank-picked__card">
                <span class="fl-bank__logo"><img src="" alt="" id="fl-bank-picked-logo"></span>
                <div>
                    <small>You selected</small>
                    <strong id="fl-bank-picked-name"></strong>
                </div>
                <button type="button" class="fl-btn fl-btn--link" id="fl-bank-change">Change</button>
            </div>
            <div class="fl-actions">
                <button type="submit" class="fl-btn fl-btn--block">
                    Continue to <span id="fl-bank-picked-btn"></span> <i class="bi bi-box-arrow-up-right"></i>
                </button>
            </div>
            <p class="fl-field__hint">This choice is final for this application. You will be redirected to the partner's
                secure website to complete your application.</p>
        </div>
    </form>
@endsection

@section('stepFoot')
    Loan approval and disbursal are at the sole discretion of the lending partner. Flubbi is a technology and
    service provider, not a lender.
@endsection

@section('stepJs')
    <script>
        (function () {
            var $grid = $("#fl-bank-grid"), $picked = $("#fl-bank-picked");

            $grid.on("click", ".fl-bank", function () {
                var $b = $(this);
                $("#fl-bank-id").val($b.data("id"));
                $("#fl-bank-picked-name, #fl-bank-picked-btn").text($b.data("label"));
                $("#fl-bank-picked-logo").attr({ src: $b.find("img").attr("src"), alt: $b.data("label") });
                $grid.prop("hidden", true);
                $picked.prop("hidden", false);
            });

            $("#fl-bank-change").on("click", function () {
                $("#fl-bank-id").val("");
                $picked.prop("hidden", true);
                $grid.prop("hidden", false);
            });
        })();
    </script>
@endsection
