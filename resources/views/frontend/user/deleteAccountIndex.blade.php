@extends('frontend.user.layoutIndex')

@section('panelTitle', 'Delete Account')
@section('panelCrumb', 'Delete account')

@section('panelBody')
    <div class="up-head">
        <div>
            <h2>Delete my account</h2>
            <p>This is permanent. Please read before you continue.</p>
        </div>
    </div>

    <div class="up-card">
        <div class="up-note up-note--danger" style="margin-bottom:18px;">
            <i class="bi bi-exclamation-triangle"></i>
            <div>
                <strong>What happens when you delete</strong>
                <ul style="margin:8px 0 0 18px;padding:0;">
                    <li>Your login, profile and contact details are removed.</li>
                    <li>Applications already submitted to a lender stay with that lender under their own policy.</li>
                    <li>Platform fees already paid are non-refundable as per the <a href="{{ route('_refundPolicyPost') }}" target="_blank">Refund Policy</a>.</li>
                    <li>You can register again any time with the same mobile number.</li>
                </ul>
            </div>
        </div>

        <form action="{{ route('_userDeleteAccount') }}" method="POST" class="js-fl-step" id="fl-delete-form" novalidate>
            @csrf

            <div id="fl-send-wrap" @if ($otp_sent) hidden @endif>
                <div class="fl-field">
                    <label class="fl-choice" style="margin:0;">
                        <input type="checkbox" name="confirm" value="1">
                        <span class="fl-choice__tick" style="border-radius:5px;"></span>
                        <span class="fl-choice__body">
                            <span class="fl-choice__title">I understand this cannot be undone</span>
                            <span class="fl-choice__text">An OTP will be sent to +91 {{ $user->phone }} to confirm.</span>
                        </span>
                    </label>
                </div>
                <div class="fl-actions">
                    <button type="button" class="fl-btn" style="background:var(--fl-err);border-color:var(--fl-err);" data-fl-post="{{ route('_userDeleteAccount') }}" data-fl-form="#fl-delete-form" data-fl-data='{"action":"send"}'>
                        <i class="bi bi-trash3"></i> Send OTP to delete
                    </button>
                    <a class="fl-btn fl-btn--ghost" href="{{ route('_userDashboard') }}">Keep my account</a>
                </div>
            </div>

            <div class="fl-otp-wrap" id="fl-otp-wrap" @unless ($otp_sent) hidden @endunless>
                <div class="fl-field">
                    <label class="fl-field__label" for="otp">Enter the OTP sent to +91 {{ $user->phone }} <span class="fl-req">*</span></label>
                    <input class="fl-field__control fl-otp numeric" type="text" name="otp" id="otp" inputmode="numeric" autocomplete="one-time-code"
                        maxlength="6" pattern="[0-9]{6}" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;" data-fl-otp @if ($otp_sent) autofocus @endif>
                    <span class="fl-field__hint">Valid for 5 minutes. Your account is deleted the moment it is verified.</span>
                </div>
                <div class="fl-actions">
                    <button type="submit" class="fl-btn" style="background:var(--fl-err);border-color:var(--fl-err);"><i class="bi bi-trash3"></i> Delete my account</button>
                    <a class="fl-btn fl-btn--ghost" href="{{ route('_userDashboard') }}">Cancel</a>
                </div>
                <div class="fl-resend">
                    Did not get the code?
                    <button type="button" class="fl-btn fl-btn--link" id="fl-resend-btn" data-fl-post="{{ route('_userDeleteAccount') }}" data-fl-form="#fl-delete-form" data-fl-data='{"action":"send","confirm":1}' @if ($otp_sent && $cooldown) data-fl-cooldown="{{ $cooldown }}" @endif>Resend OTP</button>
                </div>
            </div>
        </form>
    </div>
@endsection
