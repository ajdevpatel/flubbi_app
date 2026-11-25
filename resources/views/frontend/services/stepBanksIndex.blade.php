@extends('frontend.services.stepLayoutIndex')

@section('stepSubtitle')
    Payment received. Apply to as many lending partners as you like &mdash; you can come back and apply to more anytime.
@endsection

@section('stepBody')
    <form action="{{ $urls['banks'] }}" method="POST" id="fl-bank-form" novalidate>
        @csrf
        <input type="hidden" name="bank_id" id="fl-bank-id" value="">

        <div class="fl-bank-grid" id="fl-bank-grid">
            @foreach ($banks as $bank)
                @php $count = (int) ($applied[$bank->id] ?? 0); @endphp
                <button type="button" class="fl-bank fl-bank--apply @if ($count > 0) is-applied @endif"
                    data-id="{{ $bank->id }}" data-label="{{ $bank->label }}" data-link="{{ trim($bank->link) }}"
                    title="{{ $bank->label }}">
                    <span class="fl-bank__badge" @if ($count < 1) hidden @endif>Applied<span class="fl-bank__count">@if ($count > 1) ({{ $count }}) @endif</span></span>
                    <span class="fl-bank__logo"><img src="{{ asset('banks/' . $bank->logo) }}" alt="{{ $bank->label }}" loading="lazy"></span>
                    <span class="fl-bank__name">{{ ucwords($bank->label) }}</span>
                    <span class="fl-bank__apply"><i class="bi bi-box-arrow-up-right"></i> Apply</span>
                </button>
            @endforeach
        </div>

        <div class="fl-actions mt-3">
            <a href="{{ route('_userApplications') }}" class="fl-btn fl-btn--block">
                Go to My Account <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <p class="fl-field__hint">Each lending partner's secure website opens in a new tab. You can apply to more than one, and this application stays saved under My Account.</p>
    </form>
@endsection

@section('stepFoot')
    Loan approval and disbursal are at the sole discretion of the lending partner. Flubbi is a technology and
    service provider, not a lender.
@endsection

@section('stepJs')
    <script>
        (function () {
            var $grid = $("#fl-bank-grid"), $form = $("#fl-bank-form"), busy = false;

            $grid.on("click", ".fl-bank--apply", function () {
                if (busy) {
                    return false;
                }
                var $b = $(this);
                var link = $b.data("link");
                busy = true;
                $b.addClass("is-loading");
                var tab = window.open("", "_blank");

                $("#fl-bank-id").val($b.data("id"));

                $.ajax({
                    type: "POST",
                    url: $form.attr("action"),
                    data: new FormData($form.get(0)),
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        busy = false;
                        $b.removeClass("is-loading");
                        if (res.open && tab) {
                            tab.location.href = res.open;
                        } else if (tab) {
                            tab.close();
                        }
                        $b.addClass("is-applied");
                        var $badge = $b.find(".fl-bank__badge");
                        $badge.prop("hidden", false);
                        if (res.apply_count && res.apply_count > 1) {
                            $b.find(".fl-bank__count").text(" (" + res.apply_count + ")");
                        }
                        if (res.message && typeof Notify === "function") {
                            Notify(res.message, "success");
                        }
                    },
                    error: function (xhr) {
                        busy = false;
                        $b.removeClass("is-loading");
                        if (tab) {
                            tab.close();
                        }
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
