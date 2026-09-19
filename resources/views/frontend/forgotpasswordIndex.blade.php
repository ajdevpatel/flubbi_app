@extends('frontend.layoutIndex')


@section('bodyIndex')
    <!-- CONTENT START -->
    <div class="page-content">

        <!-- INNER PAGE BANNER -->
        <div class="wt-bnr-inr overlay-wraper bg-center" style="background-image:url(assets/images/banner/1.jpg);">
            <div class="overlay-main site-bg-white opacity-01"></div>
            <div class="container">
                <div class="wt-bnr-inr-entry">
                    <div class="banner-title-outer">
                        <div class="banner-title-name">
                            <h2 class="wt-title">Reset Password</h2>
                        </div>
                    </div>
                    <!-- BREADCRUMB ROW -->

                    <div>
                        <ul class="wt-breadcrumb breadcrumb-style-2">
                            <li><a href="{{ route('_homeIndex') }}">Home</a></li>
                            <li>Reset Password</li>
                        </ul>
                    </div>

                    <!-- BREADCRUMB ROW END -->
                </div>
            </div>
        </div>
        <!-- INNER PAGE BANNER END -->

        <!-- CONTACT FORM -->
        <div class="section-full twm-contact-one">
            <div class="section-content">
                <div class="container">

                    <!-- CONTACT FORM-->
                    <div class="contact-one-inner">
                        <div class="row">

                            <div class="col-lg-6 col-md-12">
                                <div class="contact-form-outer">

                                    <!-- TITLE START-->
                                    <div class="section-head left wt-small-separator-outer">
                                        <h2 class="wt-title">Reset Password!</h2>
                                    </div>
                                    <!-- TITLE END-->

                                    <form class="cons-contact-form" id="_forgotpassword" method="post"
                                        action="{{ route('_forgotpasswordIndex') }}">
                                        @csrf
                                        <div class="row">

                                            <h3 class="sign-up__title">Reset Password!</h3>

                                            <div class="col-lg-8 col-md-8">
                                                <div class="form-group mb-3">

                                                    <label class="label" for="loginKey">Enter Your Email ID</label>
                                                    <input type="email" class="form-control" name="loginKey"
                                                        placeholder="Enter Your Email..." required>
                                                </div>
                                                <p class="text-end"> Already have an account? <a
                                                        href="{{ route('_frontendLogin') }}" class="fs-small forget-pass"> Login
                                                        Now</a> </p>
                                            </div>

                                            <div class="col-md-12">

                                                <button type="submit" class="site-button" name="submit"
                                                    id="submit">Continue<i class="bi bi-arrow-up-right"></i></button>
                                                <p class="have_account">Don't have an account? <a
                                                        href="{{ route('_loansApplyIndex', [
                                                            'type' => 'personal',
                                                        ]) }}"
                                                        class="signin">Apply Now</a></p>

                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
    <!-- CONTENT END -->
@endsection
