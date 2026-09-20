@extends('frontend.services.stepLayoutIndex')

@section('stepSubtitle')
    One time {{ strtolower($fee['label']) }} fee. Secured by Razorpay.
@endsection

@section('stepBody')
    <div class="fl-fee">
        <div class="fl-fee__head">
            <i class="bi {{ 'self' === $fee['login_type'] ? 'bi-person-check' : 'bi-headset' }}"></i>
            <div>
                <strong>{{ $fee['label'] }}</strong>
                <span>{{ $fee['note'] }}</span>
            </div>
            <a class="fl-link" href="{{ $urls['login-type'] }}">Change</a>
        </div>
        <div class="fl-fee__row"><span>Platform fee</span><span>&#8377; {{ number_format($fee['base_amount'], 2) }}</span></div>
        <div class="fl-fee__row"><span>GST @ {{ (int) $fee['gst_rate'] }}%</span><span>&#8377; {{ number_format($fee['gst_amount'], 2) }}</span></div>
        <div class="fl-fee__row fl-fee__row--total"><span>Total payable</span><span>&#8377; {{ number_format($fee['total_amount'], 2) }}</span></div>
    </div>

    <form action="{{ $urls['payment'] }}" method="POST" id="fl-pay-form" novalidate>
        @csrf

        <div class="fl-actions">
            <button type="submit" class="fl-btn fl-btn--block fl-btn--pay" id="fl-pay-btn" @disabled(!$gateway_ready)>
                <i class="bi bi-lock-fill"></i> Pay &#8377; {{ number_format($fee['total_amount'], 2) }} securely
            </button>
        </div>

        @unless ($gateway_ready)
            <p class="fl-field__hint" style="text-align:center;">Online payment is being set up. Please try again shortly.</p>
        @endunless

        <div class="fl-paybadges">
            <span><i class="bi bi-shield-lock"></i> 256-bit encrypted</span>
            <span><i class="bi bi-credit-card"></i> UPI &middot; Cards &middot; Net banking</span>
            <span><i class="bi bi-arrow-repeat"></i> Refund as per policy</span>
        </div>
    </form>
@endsection

@section('stepFoot')
    Payment is processed by Razorpay; Flubbi never sees your card or UPI details. See our
    <a href="{{ route('_refundPolicyPost') }}" target="_blank">Refund Policy</a> and
    <a href="{{ route('_termsAndConditionsPost') }}" target="_blank">Terms &amp; Conditions</a>.
@endsection

@section('stepJs')
    @if ($gateway_ready)
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @endif
    <script>
        (function () {
            var $form = $("#fl-pay-form"), $btn = $("#fl-pay-btn"), busy = false;
            var token = $form.find("[name=_token]").val();

            function post(url, data) {
                return $.ajax({ type: "POST", url: url, data: data, dataType: "json" });
            }

            function reset() {
                busy = false;
                $btn.prop("disabled", false).html($btn.data("label"));
            }

            function failed(xhr) {
                if (typeof ajaxResponseFailure === "function") {
                    ajaxResponseFailure(xhr);
                }
                if (xhr.responseJSON && xhr.responseJSON.step) {
                    setTimeout(function () { window.location.href = xhr.responseJSON.step; }, 1500);
                }
            }

            $form.on("submit", function (e) {
                e.preventDefault();
                if (busy || typeof Razorpay === "undefined") {
                    return false;
                }
                busy = true;
                $btn.data("label", $btn.html()).prop("disabled", true).html('<i class="fas fa-spinner fa-spin"></i> Starting payment...');

                post($form.attr("action"), $form.serialize()).done(function (res) {
                    var o = res.order;
                    var rzp = new Razorpay({
                        key: o.key,
                        amount: o.amount,
                        currency: o.currency,
                        name: o.name,
                        description: o.description,
                        order_id: o.order_id,
                        prefill: o.prefill,
                        notes: o.notes,
                        theme: { color: "#cdb676" },
                        handler: function (r) {
                            $btn.html('<i class="fas fa-spinner fa-spin"></i> Confirming payment...');
                            post(res.verify_url, {
                                _token: token,
                                razorpay_order_id: r.razorpay_order_id,
                                razorpay_payment_id: r.razorpay_payment_id,
                                razorpay_signature: r.razorpay_signature
                            }).done(function (v) {
                                window.location.href = v.step;
                            }).fail(failed);
                        },
                        modal: { ondismiss: reset }
                    });

                    rzp.on("payment.failed", function (r) {
                        post(res.verify_url, { _token: token, status: "failed", razorpay_order_id: o.order_id, error: r.error })
                            .always(function () { window.location.href = res.failed_url; });
                    });

                    rzp.open();
                }).fail(function (xhr) {
                    reset();
                    failed(xhr);
                });

                return false;
            });
        })();
    </script>
@endsection
