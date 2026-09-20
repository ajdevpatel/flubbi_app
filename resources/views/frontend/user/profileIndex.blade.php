@extends('frontend.user.layoutIndex')

@section('panelTitle', 'Profile')
@section('panelCrumb', 'Profile')

@php
    $initials = collect(explode(' ', trim($user->name ?: 'U')))->filter()->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->take(2)->implode('');
@endphp

@section('panelBody')
    <div class="up-head">
        <div>
            <h2>Profile</h2>
            <p>Keep your details current &mdash; lenders use them to contact you.</p>
        </div>
    </div>

    <form action="{{ route('_userProfile') }}" method="POST" enctype="multipart/form-data" class="js-fl-step" autocomplete="off" novalidate>
        @csrf
        <div class="up-card">
            <div class="up-photo">
                <div class="up-avatar">
                    @if (!empty($user->profile_pic))
                        <img src="{{ asset('uploads/' . $user->profile_pic) }}" alt="{{ $user->name }}">
                    @else
                        {{ $initials }}
                    @endif
                </div>
                <div>
                    <label class="fl-btn fl-btn--ghost fl-btn--sm up-doc__btn">
                        <i class="bi bi-camera"></i> Change photo
                        <input type="file" name="profile_pic" accept=".jpg,.jpeg,.png">
                    </label>
                    <small>JPG or PNG, up to 2 MB</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="fl-field">
                        <label class="fl-field__label" for="name">Full name <span class="fl-req">*</span></label>
                        <input class="fl-field__control alphabet" type="text" name="name" id="name" value="{{ $user->name }}" maxlength="100" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fl-field">
                        <label class="fl-field__label" for="gender">Gender</label>
                        <select class="fl-field__control" name="gender" id="gender">
                            <option value="">Select</option>
                            @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $k => $v)
                                <option value="{{ $k }}" @selected(($user->gender ?? '') === $k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fl-field">
                        <label class="fl-field__label" for="phone">Mobile number</label>
                        <div class="fl-field__prefix">
                            <span>+91</span>
                            <input class="fl-field__control" type="text" id="phone" value="{{ $user->phone }}" readonly>
                        </div>
                        <span class="fl-field__hint">Verified. Contact support to change it.</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fl-field">
                        <label class="fl-field__label" for="email">Email address <span class="fl-req">*</span></label>
                        <input class="fl-field__control" type="email" name="email" id="email" value="{{ $user->email }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fl-field">
                        <label class="fl-field__label" for="pincode">PIN code <span class="fl-req">*</span></label>
                        <input class="fl-field__control numeric" type="text" name="pincode" id="pincode" inputmode="numeric" maxlength="6" value="{{ $user->pincode }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fl-field">
                        <label class="fl-field__label" for="city">City <span class="fl-req">*</span></label>
                        <input class="fl-field__control alphabet" type="text" name="city" id="city" value="{{ $user->city }}" maxlength="100" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fl-field">
                        <label class="fl-field__label" for="state_id">State <span class="fl-req">*</span></label>
                        <select class="fl-field__control" name="state_id" id="state_id" required>
                            <option value="">Select state</option>
                            @foreach ($states as $s)
                                <option value="{{ $s->id }}" @selected(($user->state_id ?? 0) == $s->id)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="fl-actions">
                <button type="submit" class="fl-btn">Save changes <i class="bi bi-check2"></i></button>
            </div>
        </div>
    </form>
@endsection
