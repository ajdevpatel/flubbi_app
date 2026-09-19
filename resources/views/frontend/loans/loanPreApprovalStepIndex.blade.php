@extends('frontend.layoutIndex')


@section('bodyIndex')


    <div class="page-content">

        <div class="section-full p-t120 p-b120 twm-for-employee-area site-bg-white">
            <div class="container">

                <div class="section-content">
                    <div class="row">

                        <div class="col-lg-5 col-md-12">
                            <div class="twm-s-contact">
                                <div class="sidebar__part">
                                    <h4 class="sidebar__part-title">Customer Details</h4>
                                    <ul>
                                        <li><i class="bi bi-person-check"></i> Full Name :-
                                            <strong>{{ session('in_user')['name'] }}</strong>
                                        </li>
                                        <li><i class="bi bi-phone"></i> Mobile :-
                                            <strong>{{ session('in_user')['phone'] }}</strong>
                                        </li>
                                        <li><i class="bi bi-currency-rupee"></i>
                                            {{ Str::ucfirst(config('web.webapp.loan_word.view_file')) }} Amount :-
                                            <strong>₹{{ session('in_user')['format_amount'] }}</strong>
                                        </li>
                                    </ul>
                                </div>
                                <div class="sidebar__part">
                                    <h4 class="sidebar__part-title">
                                        {{ Str::ucfirst(config('web.webapp.loan_word.view_file')) }}
                                        Applying Steps</h4>
                                    <ul>
                                        <li><i class="bi bi-check2-circle"></i> Registration Process</li>
                                        <li><i class="bi bi-check2-circle"></i> Check Eligibility</li>
                                        <li class="text-warning"><strong><i
                                                    class="bi bi-arrow-right-circle text-warning"></i>Pre-Approval
                                                Offer</strong></li>
                                        <li><i class="bi bi-dash-circle"></i> Buy Subscription</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-12">

                            <form
                                action="{{ route('_loanPreApprovalStepIndex', [
                                    'type' => $type,
                                ]) }}"
                                id="_preApprovalModule" method="POST" enctype="multipart/form-data" class="twm-s-contact"
                                autocomplete="off">
                                @csrf

                                <div class="row">
                                    <div class="twm-title-large">
                                        <h3>@php
                                            echo $heading_text_one;
                                        @endphp </h3>
                                        <p>
                                            <strong>{{ $heading_text_two }}</strong>
                                        </p>
                                    </div>

                                    <div class="emi-card-group">
                                        @if (!empty($emi_list))
                                            @foreach ($emi_list as $k => $v)
                                                <label class="emi-card">
                                                    <input type="radio" name="emi_tenure" class="emi-card-radio"
                                                        value="{{ $k }}" @checked($k == 24)>
                                                    <div class="emi-card-body">
                                                        <span class="emi-card-title">{{ $k }} Months</span>
                                                        <span class="emi-card-cost">₹{{ $v }}/-</span>
                                                        <span class="emi-card-check"><i
                                                                class="fas fa-check-circle"></i></span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        @endif
                                    </div>


                                    <div class="col-xl-12 col-lg-12 col-md-12 pt-2">
                                        <div class="text-left">
                                            <button type="submit" name="calc_submit" class="site-button">Submit
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


    <!-- Ammout Comaprison start -->
    {{-- <section class="ammount-comparison style5 ">
        <div class="container">
            <div class="ammount-comparison4-head align-items-end">
            </div>
        </div>
        <div class="loan-reviews">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="d-flex flex-column gap-4 loan-reviews-information5">
                            @include('frontend.block.loan_comparison_list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- Ammout Comaprison End -->


@endsection

@section('jsIndex')
    <script src="{{ asset('assets/app/loanApplyIndex.js') }}"></script>
@endsection
