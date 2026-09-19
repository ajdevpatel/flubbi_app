@extends("frontend.layoutIndex")


@section("bodyIndex")

    <!-- Hero Section Start -->
    <section class="hero reviews-details">
        <div class="hero__animation">
            <img src="{{ asset('assets/images/hero_vector_dollar.png') }}" alt="Image">
            <img src="{{ asset('assets/images/hero_vector_message.png') }}" alt="Image">
            <img src="{{ asset('assets/images/hero_vector_dollar.png') }}" alt="Image">
            <img src="{{ asset('assets/images/hero_vector_setting.png') }}" alt="Image">
            <img src="{{ asset('assets/images/hero_vector_arrow.png') }}" alt="Image">
        </div>
        <div class="container">
            <div class="row gy-5 gy-lg-0 d-flex justify-content-center">
                <div class="col-12 col-lg-6 col-xxl-5 mx-auto mx-lg-0">
                    <div class="calculator-input">
                        <div class="row card card--custom calc custom-card-gap">

                            <div class="col-12 section__content">
                                <h3 class="section__content-sub-title headingFour wow fadeInDown" data-wow-duration="0.8s"><img src="{{ asset('assets/images/title_vector.png') }}" alt="vector"> @php
                                    echo $heading_text_one;
                                @endphp </h3>
                                <!--- <span class="section__content-sub-title headingFour wow fadeInDown" data-wow-duration="0.8s"><img src="{{ asset('assets/images/title_vector.png') }}" alt="vector"> {{ $heading_text_two }}</span> --->
                                <p>
                                    <strong>{{ $heading_text_two }}</strong></p>
                            </div>

                            <div class="col-12 card--custom__loan">
                                <div class="card--custom__form">

                                    <form action="{{ route('_loanPreApprovalStepIndex', [
                                            'type' => $type,
                                        ]) }}" id="_preApprovalModule" method="POST" enctype="multipart/form-data"
                                        class="calculate__form" autocomplete="off">
                                        @csrf

                                        <div class="gy-5 gy-md-0  form-group">
                                            <div class="calculate__form-part">
                                                {{-- <div class="input-single mb-2">
                                                    <ul>
                                                        @if ([] != $emi_list)
                                                            @foreach ($emi_list as $k => $v)
                                                                <li>
                                                                    <label class="label" for="emi_tenure">
                                                                        <input type="radio" class="form-control"
                                                                            name="emi_tenure" value="{{ $k }}">
                                                                        {{ $v }}
                                                                    </label>
                                                                    <hr>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </div> --}}

                                                <div class="radio-group">
                                                    @if ([] != $emi_list)
                                                        @foreach ($emi_list as $k => $v)

                                                            <input class="radio-input" name="emi_tenure"
                                                                id="emi_tenure-{{ $k }}" @if ($k == 24)
                                                                    checked
                                                                @endif
                                                                value="{{ $k }}" type="radio">
                                                            <label class="radio-label"
                                                                for="emi_tenure-{{ $k }}">
                                                                <span class="radio-inner-circle"></span>
                                                                {{ $k }} Months &nbsp;&nbsp;<i
                                                                    class="bi bi-chevron-double-right"></i>&nbsp;&nbsp;
                                                                ₹{{ $v }}/-
                                                            </label>
                                                        @endforeach
                                                    @endif

                                                    {{-- <input class="radio-input" name="radio-group" id="radio2"
                                                        type="radio">
                                                    <label class="radio-label" for="radio2">
                                                        <span class="radio-inner-circle"></span>
                                                        Option 2
                                                    </label>

                                                    <input class="radio-input" name="radio-group" id="radio3"
                                                        type="radio">
                                                    <label class="radio-label" for="radio3">
                                                        <span class="radio-inner-circle"></span>
                                                        Option 3
                                                    </label> --}}
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn_theme btn_theme_active mt_40" name="calc_submit">
                                            Submit <i class="bi bi-arrow-up-right"></i><span></span></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="sidebar sidebar_fixed sidebar-xl-fixed cus_scrollbar">
                        <div class="sidebar__part">
                            <h4 class="sidebar__part-title">Customer Details</h4>
                            <ul>
                                <li><i class="bi bi-person-check"></i> Full Name :- 
                                    <strong>{{ session('in_user')['name'] }}</strong>
                                </li>
                                <li><i class="bi bi-phone"></i> Mobile :- 
                                    <strong>{{ session('in_user')['phone'] }}</strong>
                                </li>
                                <li><i class="bi bi-currency-rupee"></i> {{ Str::ucfirst(config("web.webapp.loan_word.view_file")) }} Amount :- 
                                    <strong>₹{{ session('in_user')['format_amount'] }}</strong>
                                </li>
                            </ul>
                        </div>
                        <div class="sidebar__part">
                            <h4 class="sidebar__part-title">{{ Str::ucfirst(config("web.webapp.loan_word.view_file")) }} Applying Steps</h4>
                            <ul>
                                <li><i class="bi bi-check2-circle"></i> Registration Process</li>
                                <li><i class="bi bi-check2-circle"></i> Check Eligibility</li>
                                <li class="text-warning"><strong><i class="bi bi-arrow-right-circle text-warning"></i>
                                        Pre-Approval Offer</strong></li>
                                <li><i class="bi bi-dash-circle"></i> Buy Subscription</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Hero Section End -->

    
    <!-- Ammout Comaprison start -->
    <section class="ammount-comparison style5 ">
        <div class="container">
            <div class="ammount-comparison4-head align-items-end">
            </div>
        </div>
        <div class="loan-reviews">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="d-flex flex-column gap-4 loan-reviews-information5">
                            @include("frontend.block.loan_comparison_list")
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Ammout Comaprison End -->


@endsection

@section("jsIndex")
    <script src="{{ asset('assets/app/loanApplyIndex.js') }}"></script>
@endsection
