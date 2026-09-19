@extends('frontend.layoutIndex')


@section('bodyIndex')
    {{-- for failed payment page --}}

    <section class="reviews-details section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="reviews-details__area">
                    <div class="reviews-details__part p-0">
                        <div class="loan-reviews loan-reviews--quaternary">
                            <div class="loan-reviews_card card sign-up">
                                <div class="loan-reviews__part-one">

                                    <div class="section__content mt-1">
                                        <h2 class="section__content-title text-warning"><img
                                                src="{{ asset('assets/images/title_vector.png') }}" alt="vector"> Payment
                                            Unsuccessful!</h2>
                                        <p class="mt-1">Sorry,
                                            Your Subscription Plan Payment Was not Successful.</p>
                                        <p class="mt-1">We request you to try another payment method.</p>

                                        <a href="https://flubbi.com/cardoffer"
                                            class="btn_theme btn_theme_active mt_40 ">Try Another
                                            Method<i class="bi bi-arrow-up-right"></i><span></span></a>
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
