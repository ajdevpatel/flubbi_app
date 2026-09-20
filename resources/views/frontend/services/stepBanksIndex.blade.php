@extends('frontend.services.stepLayoutIndex')

@section('stepSubtitle')
    Payment received. Choose one partner &mdash; you will be taken to its application page.
@endsection

@section('stepBody')
    <form action="{{ $urls['banks'] }}" method="POST" id="fl-bank-form" novalidate>
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
            <p class="fl-field__hint">This choice is final for this application. The partner's secure website opens in a new tab and this application is saved under My Account.</p>
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

            var $form = $("#fl-bank-form"), busy = false;
            $form.on("submit", function (e) {
                e.preventDefault();
                if (busy || !$("#fl-bank-id").val()) {
                    return false;
                }
                busy = true;
                var $btn = $form.find("[type=submit]").first();
                var label = $btn.html();
                $btn.prop("disabled", true).html('<i class="fas fa-spinner fa-spin"></i> Please wait...');
                var tab = window.open("", "_blank");

                $.ajax({
                    type: "POST",
                    url: $form.attr("action"),
                    data: new FormData($form.get(0)),
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        if (res.open && tab) {
                            tab.location.href = res.open;
                        } else if (tab) {
                            tab.close();
                        }
                        if (res.message && typeof Notify === "function") {
                            Notify(res.message, "success");
                        }
                        window.location.href = res.step;
                    },
                    error: function (xhr) {
                        if (tab) {
                            tab.close();
                        }
                        busy = false;
                        $btn.prop("disabled", false).html(label);
                        if (typeof ajaxResponseFailure === "function") {
                            ajaxResponseFailure(xhr);
                        }
                        if (xhr.responseJSON && xhr.responseJSON.step) {
                            setTimeout(function () { window.location.href = xhr.responseJSON.step; }, 1500);
                        }
                    }
                });
                return false;
            });
        })();
    </script>
@endsection
