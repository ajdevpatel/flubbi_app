@extends('frontend.layoutIndex')


@section('bodyIndex')
    <div class="page-content">

        <div class="section-full p-t120 p-b120 twm-for-employee-area site-bg-white">
            <div class="container">

                <div class="section-content">
                    <div class="row">

                        <div class="col-lg-5 col-md-12">
                            <div class="twm-explore-media-wrap">
                                <div class="twm-media">
                                    <img src="assets/images/boy-large.png" alt="">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-12">

                            <form
                                action="{{ route('_loansApplyIndex', [
                                    'type' => $type,
                                ]) }}"
                                id="_loanModule" method="POST" enctype="multipart/form-data" class="twm-s-contact"
                                autocomplete="off">
                                @csrf

                                <input type="hidden" name="msg_key" id="msg_key">
                                <div class="row">
                                    <div class="twm-title-small">It's Quick. Flexible. Paperless</div>
                                    <div class="twm-title-large">
                                        <h3>Get up to<span class="ms-2 fw-bold" id="amount-display">₹10,000</span> in
                                            {{ Str::ucfirst($type) }}
                                            {{ Str::ucfirst(config('web.webapp.loan_word.view_file')) }} in just a few
                                            Minutes!</h3>
                                    </div>

                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <div class="loan-amount-head">
                                                <div class="sidebar-filter__part">
                                                    <div class="sidebar-cat__price-range">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h5 class="sidebar-filter__part-title">
                                                                {{ Str::ucfirst(config('web.webapp.loan_word.view_file')) }}
                                                                Amount
                                                            </h5>
                                                            <h6 id="range-output"></h6>
                                                            <input type="hidden" id="customRange" min="10000"
                                                                max="2500000" name="amount" value="10000">
                                                        </div>
                                                        <div class="d-flex align-items-center gap-2 mt-2">
                                                            <button type="button" class="btn btn-outline-secondary"
                                                                id="amount-minus" style="min-width:40px;">-</button>
                                                            <input class="form-control text-center" name="amount_visible"
                                                                id="amount-visible" type="text" value="10000"
                                                                min="1000" max="2500000" step="1000"
                                                                style="max-width:150px;" placeholder="Enter amount">
                                                            <button type="button" class="btn btn-outline-secondary"
                                                                id="amount-plus" style="min-width:40px;">+</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="name">Full Name *</label>
                                            <div class="ls-inputicon-box">
                                                <input class="form-control" name="name" id="name" type="text"
                                                    placeholder="Enter your name" required>
                                                <i class="fs-input-icon fa fa-user "></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="email">Email Address *</label>
                                            <div class="ls-inputicon-box">
                                                <input class="form-control" name="mail" id="mail" type="email"
                                                    placeholder="devpatel@gmail.com" required>
                                                <i class="fs-input-icon fas fa-at"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="phone">Phone Number *</label>
                                            <div class="ls-inputicon-box">
                                                <input class="form-control" name="phone" id="phone" type="text"
                                                    placeholder="9874543210" required>
                                                <i class="fs-input-icon fa fa-phone-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12" id="otp_section">
                                        <div class="form-group">
                                            <label for="otp" class="label">OTP</label>
                                            <div class="ls-inputicon-box d-flex align-items-center" style="gap:10px;">
                                                <input type="text" class="form-control numeric" name="otp"
                                                    id="otp" autocomplete="one-time-code" inputmode="numeric"
                                                    maxlength="4" pattern="\d{4}" placeholder="Enter OTP">
                                                <button type="button" class="btn btn-outline-secondary" id="resend_otp"
                                                    data-href="{{ route('_loanSendOtpIndex', ['type' => $type]) }}">
                                                    Resend OTP
                                                </button>
                                            </div>
                                            <small class="form-text text-muted mt-1">
                                                Didn't receive OTP? Click <span class="text-primary"
                                                    style="cursor:pointer;" id="resend_otp_link">Resend OTP</span>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 col-lg-12">
                                        <div class="form-group">
                                            <label class="form-check-label" for="flexRadioDefault2">
                                                By submitting this form, you agree to our Terms of Service
                                                and
                                                Privacy Policy and consent to receive communications from
                                                flubbi through SMS, Email, and WhatsApp.
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="text-left">
                                            <button type="submit" class="site-button" id="send_otp"
                                                data-href="{{ route('_loanSendOtpIndex', [
                                                    'type' => $type,
                                                ]) }}">Send
                                                OTP
                                                <i class="feather-arrow-right"></i></button>

                                            <button type="submit" id="form_submit_btn" class="site-button">Start Process
                                                <i class="feather-arrow-right"></i></button>
                                        </div>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
@push('customJs')
    <script>
        function formatAmount(val) {
            return '₹' + Number(val).toLocaleString('en-IN');
        }
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('amount-visible');
            const minus = document.getElementById('amount-minus');
            const plus = document.getElementById('amount-plus');
            const display = document.getElementById('amount-display');
            const hidden = document.getElementById('customRange');
            let min = 1000,
                max = 2500000,
                step = 1000;

            function updateDisplay() {
                let val = parseInt(input.value.replace(/[^0-9]/g, '')) || min;
                val = Math.max(min, Math.min(max, val));
                input.value = val;
                hidden.value = val;
                display.textContent = formatAmount(val);
            }

            minus.addEventListener('click', function() {
                let val = parseInt(input.value.replace(/[^0-9]/g, '')) || min;
                val = Math.max(min, val - step);
                input.value = val;
                updateDisplay();
            });

            plus.addEventListener('click', function() {
                let val = parseInt(input.value.replace(/[^0-9]/g, '')) || min;
                val = Math.min(max, val + step);
                input.value = val;
                updateDisplay();
            });

            input.addEventListener('input', updateDisplay);

            // Initialize display
            updateDisplay();
        });
    </script>
@endpush
@section('jsIndex')
    <script src="{{ asset('assets/app/loanApplyIndex.js') }}"></script>
@endsection
