@extends("frontend.layoutIndex")


@section("bodyIndex")
    <!-- Hero Section Start -->
    <section class="hero hero-version5 position-relative dev-font">
        <div class="container position-relative">
            <div class="row g-7">
                <div class="col-lg-12 col-md-12">
                    <div class="hero-content5">
                        <h4 class="fw-semibold d-flex align-items-center mb-md-3 mb-2 gap-2">
                            <img src="{{ asset('assets/images/section-arrow-right.png') }}" alt="icon"> Quick Online Procedure | Suitable EMI Choices | Dedicated Financial Consultant
                        </h4>
                        <h1 class="mb-lg-12 mb-3 text-white">Buy Subscription Plan Now & Start Process Instantly</h1>
                        <div class="d-flex flex-md-nowrap flex-wrap align-items-center gap-xl-3 gap-2">
                            <div class="line d-md-block d-none"></div>
                            <p class="text-white" >We provide clear, step-by-step assistance tailored to your unique financial needs, ensuring that you find the perfect loan with confidence.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Element -->
        <img src="{{ asset('assets/images/faq-loan-ele.png') }}" alt="img" class="loan-doller">
        {{--  <img src="{{ asset('assets/images/faq-loan-ele.png') }}" alt="img" class="loan-ele">
        <img src="{{ asset('assets/images/faq-home-ele.png') }}" alt="img" class="home-ele">
        <img src="{{ asset('assets/images/loan-doller.png') }}" alt="img" class="loan-doller"> --}}
    </section>
    <!--Hero Section End -->

    <!-- anyloan Start -->
    @include("frontend.block.our_partners_box_slider")
    <!-- anyloan End -->

    <!-- Ammout Comaprison start -->
    <section class="ammount-comparison style5 ">
        <div class="container">
            <h2 class="section__content-title m-0">
                <span class="section__content-sub-title headingFour">
                    <img src="{{ asset('assets/images/title_vector.png') }}" alt="vector"> 
                    <small><del class="text-danger" style="font-size: 1.5rem;">₹{{ $in_data["price"] }}/-</del></small> ₹{{ $in_data["s_price"] }}/- only
                </span>
            </h2>
            <hr>
            <form action="{{ route('_offerLeadsIndex', [
                'type' => $type
            ]) }}" id="_offerLeadModule" method="POST" enctype="multipart/form-data" class="ammount-comparison4-head align-items-end" autocomplete="off" >
                @csrf
                <div class="amount-item-form">
                    <span class="amount-title d-block fw-semibold mb-1"> Full Name </span>
                    <input type="text" name="name" class="form-control alphabet" placeholder="Enter Name">
                </div>
                <div class="amount-item-form">
                    <span class="amount-title d-block fw-semibold mb-1"> Mobile </span>
                    <input type="text" name="phone" class="form-control" placeholder="Enter Phone">
                </div>
                <div class="amount-item-form">
                    <span class="amount-title d-block fw-semibold mb-1"> Email </span>
                    <input type="text" name="mail" class="form-control" placeholder="Enter Email">
                </div>
                <div class="amount-item-filter">
                    <button type="submit" class="funnel text-nowrap"> Pay Now </button>
                </div>
            </form>
        </div>
        
        <div class="container position-relative">
            <div class="row gy-5 gy-xl-0 justify-content-center justify-content-xxl-between align-items-center">
                <div class="col-12 col-lg-6 col-xxl-6">
                    <div class="section__content ms-xl-4 ms-xl-0">
                        <!--- <span class="section__content-sub-title headingFour">
                            <img src="{{ asset('assets/images/title_vector.png') }}" alt="vector"> Buy Subscription Plan Now & Start Process Instantly
                        </span>
                        <h2 class="section__content-title m-0">
                            <small><del class="text-danger" style="font-size: 1.5rem;">₹199/-</del></small> ₹199/- only
                        </h2> --->
                        
                        <span class="badge price-badge text-bg-success rounded-pill mb-4" style="font-size: 1rem;">65% OFF</span>

                        <h5 class="fw-semibold d-flex align-items-center mb-1 gap-2">
                            <img src="{{ asset('assets/images/section-icon4.png') }}" alt="icon"> Plan Validity : 1 Years
                        </h5>
                        <h5 class="fw-semibold d-flex align-items-center mb-1 gap-2">
                            <img src="{{ asset('assets/images/section-icon4.png') }}" alt="icon"> Dedicated Loan Experts
                        </h5>
                        <h5 class="fw-semibold d-flex align-items-center mb-1 gap-2">
                            <img src="{{ asset('assets/images/section-icon4.png') }}" alt="icon"> Start Process Instantly
                        </h5>
                        <h5 class="fw-semibold d-flex align-items-center mb-1 gap-2">
                            <img src="{{ asset('assets/images/section-icon4.png') }}" alt="icon"> 100% seamless Online Process
                        </h5>
                        <h5 class="fw-semibold d-flex align-items-center mb-1 gap-2">
                            <img src="{{ asset('assets/images/section-icon4.png') }}" alt="icon"> On-call Expert Guidance
                        </h5>
                        <h5 class="fw-semibold d-flex align-items-center mb-1 gap-2">
                            <img src="{{ asset('assets/images/section-icon4.png') }}" alt="icon"> Compare and Choose the Best Loan
                        </h5>
                        <h5 class="fw-semibold d-flex align-items-center mb-1 gap-2">
                            <img src="{{ asset('assets/images/section-icon4.png') }}" alt="icon"> Dedicated Financial Consultant
                        </h5>
                        <h5 class="fw-semibold d-flex align-items-center mb-1 gap-2">
                            <img src="{{ asset('assets/images/section-icon4.png') }}" alt="icon"> No Negative impact on CIBIL Score
                        </h5>

                    </div>
                </div>
                <div class="col-12 col-sm-8 col-md-6 col-xxl-5">
                    <div class="loan-solution__thumb unset-xxl wow fadeInDown" data-wow-duration="0.8s">
                        <img src="{{ asset('assets/images/loan_solution.png') }}" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Ammout Comaprison End -->


    <!-- Countdown Start -->
   {{-- @include('frontend.block.satisfied_customers') --}}
    <!-- Countdown End -->

    <!-- working-process start -->
    @include('frontend.block.how_it_works')
    <!-- working-process end -->

    <!-- Client Testimonials start -->
   @include('frontend.block.testimonials')
    <!-- Client Testimonials end -->
@endsection



@section("jsIndex")
    <script src="{{ asset('assets/app/leadsIndex.js') }}"></script>
@endsection
