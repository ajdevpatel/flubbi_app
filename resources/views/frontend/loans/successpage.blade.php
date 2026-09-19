@extends('frontend.layoutIndex')


@section('bodyIndex')
    {{-- for success page --}}

    <section class="reviews-details section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="reviews-details__area">
                    <div class="reviews-details__part p-0">
                        <div class="loan-reviews loan-reviews--quaternary">
                            <div class="loan-reviews_card card sign-up">
                                <div class="loan-reviews__part-one">
                                    <div class="section__content mt-1">
                                        <h2 class="section__content-title text-success mt-1">
                                            <img src="{{ asset('assets/images/title_vector.png') }}" alt="vector">
                                            Congratulations!
                                        </h2>
                                        <p class="mt-1">Your loan application has been submitted
                                            successfully.</p>
                                        <p class="mt-1">Please sign in to the customer portal using the credentials sent
                                            to your registered email address and upload the required documents. </p>
                                        <p class="mt-1"><small>
                                                <strong>For any further queries, raise a request here:
                                                    <a href="https://flubbi.com/support/request"
                                                        class="more hover primary-color">Click Here</a>
                                                </strong>
                                            </small></p>
                                        <a href="https://flubbi.com/" class="btn_theme btn_theme_active mt_40 ">Go
                                            to Homepage<i class="bi bi-arrow-up-right"></i><span></span></a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('jsIndex')
    <script src="{{ asset('assets/app/contactIndex.js') }}"></script>
@endsection
