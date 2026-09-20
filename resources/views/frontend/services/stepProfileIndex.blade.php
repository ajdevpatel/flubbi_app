@extends('frontend.services.stepLayoutIndex')

@section('stepSubtitle')
    Used only for your application and offer updates.
@endsection

@section('stepBody')
    <form action="{{ $urls['profile'] }}" method="POST" class="js-fl-step" autocomplete="off" novalidate>
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="fl-field">
                    <label class="fl-field__label" for="email">Email address <span class="fl-req">*</span></label>
                    <input class="fl-field__control" type="email" name="email" id="email" placeholder="you@example.com"
                        value="{{ $session_user->email ?? '' }}" required autofocus>
                </div>
            </div>
            <div class="col-md-6">
                <div class="fl-field">
                    <label class="fl-field__label" for="pincode">PIN code <span class="fl-req">*</span></label>
                    <input class="fl-field__control numeric" type="text" name="pincode" id="pincode" inputmode="numeric"
                        maxlength="6" pattern="[0-9]{6}" placeholder="6 digit PIN code"
                        value="{{ $session_user->pincode ?? '' }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="fl-field">
                    <label class="fl-field__label" for="city">City <span class="fl-req">*</span></label>
                    <input class="fl-field__control alphabet" type="text" name="city" id="city" placeholder="e.g. Surat"
                        maxlength="100" value="{{ $session_user->city ?? '' }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="fl-field">
                    <label class="fl-field__label" for="state_id">State <span class="fl-req">*</span></label>
                    <select class="fl-field__control" name="state_id" id="state_id" required>
                        <option value="">Select state</option>
                        @foreach ($states as $state)
                            <option value="{{ $state->id }}" @selected(($session_user->state_id ?? 0) == $state->id)>
                                {{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="fl-actions">
            <button type="submit" class="fl-btn fl-btn--block">
                Continue <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>
@endsection
