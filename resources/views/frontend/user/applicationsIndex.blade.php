@extends('frontend.user.layoutIndex')

@section('panelTitle', 'My Applications')
@section('panelCrumb', 'Applications')

@section('panelBody')
    <div class="up-head">
        <div>
            <h2>My applications</h2>
            <p>{{ $applications->count() }} {{ 1 == $applications->count() ? 'application' : 'applications' }} &middot; personal and business loans</p>
        </div>
        <div class="fl-actions" style="margin:0;">
            <a class="fl-btn fl-btn--sm" href="{{ route('_personalServiceStart') }}"><i class="bi bi-plus-lg"></i> New application</a>
        </div>
    </div>

    @forelse ($applications as $app)
        @include('frontend.user.applicationCardIndex', ['app' => $app])
    @empty
        <div class="up-card">
            <div class="up-empty">
                <i class="bi bi-file-earmark-plus"></i>
                No applications yet.
                <div class="fl-actions" style="justify-content:center;margin-top:14px;">
                    <a class="fl-btn fl-btn--sm" href="{{ route('_personalServiceStart') }}">Personal Loan</a>
                    <a class="fl-btn fl-btn--sm fl-btn--ghost" href="{{ route('_businessServiceStart') }}">Business Loan</a>
                </div>
            </div>
        </div>
    @endforelse
@endsection
