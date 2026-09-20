@extends('frontend.layoutIndex')

@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('assets/app/loanService.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/app/userPanel.css') }}">
@endsection

@section('bodyIndex')
    <div class="breadcumb-area fl-breadcumb d-flex">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="breadcumb-content">
                        <div class="breadcumb-title">
                            <h4>@yield('panelTitle', 'My Account')</h4>
                        </div>
                        <ul>
                            <li><a href="{{ route('_homeIndex') }}"><i class="bi bi-house-door-fill"></i> Home</a></li>
                            <li class="rotates"><i class="bi bi-slash-lg"></i><a href="{{ route('_userDashboard') }}">My Account</a></li>
                            @hasSection('panelCrumb')
                                <li class="rotates"><i class="bi bi-slash-lg"></i>@yield('panelCrumb')</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="up-wrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="up-side">
                        @include('frontend.user.menuIndex')
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="fl-inline-msg"></div>
                    @yield('panelBody')
                    @if ($sample)
                        <div class="up-sample">Sample data &mdash; log in to see your own account</div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('jsIndex')
    <script src="{{ asset('assets/app/loanService.js') }}"></script>
    @yield('panelJs')
@endsection
