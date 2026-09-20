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

        <form action="{{ route('_userDeleteAccount') }}" method="POST" class="js-fl-step" novalidate>
            @csrf
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
                <button type="submit" class="fl-btn" style="background:var(--fl-err);border-color:var(--fl-err);"><i class="bi bi-trash3"></i> Send OTP &amp; delete</button>
                <a class="fl-btn fl-btn--ghost" href="{{ route('_userDashboard') }}">Keep my account</a>
            </div>
        </form>
    </div>
@endsection
