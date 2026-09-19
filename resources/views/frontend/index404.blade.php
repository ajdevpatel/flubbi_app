@extends('frontend.layoutIndex')


@section('bodyIndex')
    <div class="error-area d-flex align-items-center m-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="error-page-content">
                        <div class="error-info text-center">
                            <h1 class="error-text">404</h1>
                            <h2 class="error-title">Page not Found</h2>
                            <p class="lead">Oh no! you’re searching the page are Not Found. Thank You.</p>
                            <a href="{{ route('_homeIndex') }}"><i class="bi bi-house-door-fill"></i>Back to Home</a>
                        </div>
                        <div class="error_shape1 bounce-animate">
                            <img src="assets/images/inner-img/error_shape2.png" alt="">
                        </div>
                        <div class="error_shape2 dance">
                            <img src="assets/images/inner-img/error_shape3.png" alt="">
                        </div>
                        <div class="error_shape3 dance2">
                            <img src="assets/images/inner-img/404_shape1.png" alt="">
                        </div>
                        <div class="error_shape4 dance3">
                            <img src="assets/images/inner-img/404_shape2.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
