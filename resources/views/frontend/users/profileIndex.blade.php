@extends("frontend.layoutIndex")
 

@section("bodyIndex")

    <!-- Banner Start -->
    <section class="banner">
        <div class="container ">
            <div class="row gy-4 gy-sm-0 align-items-center">
                <div class="col-12 col-sm-6">
                    <div class="banner__content">
                        <h1 class="banner__title display-4 wow fadeInLeft" data-wow-duration="0.8s">Profile</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb wow fadeInRight" data-wow-duration="0.8s">
                                <li class="breadcrumb-item"><a href="{{ route('_homeIndex') }}">Home</a></li>
                                <li class="breadcrumb-item">User</li>
                                <li class="breadcrumb-item active" aria-current="page">Profile</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="banner__thumb text-end">
                        <img src="{{ asset('assets/images/about_banner.png') }}" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner End -->

    <section class="reviews-details section">
        <div class="container ">
            <div class="row"> 
                <div class="col-12 col-xl-3 btn_sticky advantage-boxes">
                    @include("frontend.users.menuIndex")
                </div>
                <div class="col-12 col-xl-9 order-1 order-xl-0">
                    <div class="reviews-details__area">
                            
                        <form action="{{ route('_userProfileIndex') }}" id="_userProfileModule" method="POST" enctype="multipart/form-data" class="reviews-details__part write-commnets" autocomplete="off" >
                            @csrf
                            <h4 class="average-reviews__title">User Information</h4>                            
                            <div class="d-grid gap-xxl-4 gap-3">
                                <div class="input-group">
                                    <div class="input-single">
                                        <label class="label" for="name">Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}" placeholder="Enter Your Name...">
                                    </div>
                                    <div class="input-single">
                                        <label class="label" for="email">Email</label>
                                        <input type="email" class="form-control" name="mail" value="{{ auth()->user()->email }}" placeholder="Enter Your Email...">
                                    </div>
                                </div> 
                                <div class="input-group">
                                    <div class="input-single">
                                        <label class="label" for="name">Mobile</label>
                                        <input type="text" class="form-control" disabled name="phone" value="{{ auth()->user()->phone }}" placeholder="Enter Your Name..." >
                                    </div>
                                    <div class="input-single">
                                        <label class="label" for="email">Pin Code</label>
                                        <input type="number" class="form-control" name="pincode" value="{{ auth()->user()->pincode }}" placeholder="Enter Your Pin Code...">
                                    </div>
                                </div>   
                                <div class="input-group">
                                    <div class="input-single">
                                        <label class="label" for="name">City</label>
                                        <input type="text" class="form-control" name="city" value="{{ auth()->user()->city }}" placeholder="Enter Your City..." >
                                    </div>
                                    <div class="input-single">
                                        <label class="label" for="email">State</label>
                                        <select name="state" class="form-control" data-allow-clear="true">
                                            <option value="">Select State</option>
                                            @if ([] != $state_index)
                                                @foreach ($state_index as $k => $v)
                                                    <option @if ($v->id == auth()->user()->state_id)
                                                        selected
                                                    @endif value="{{ $v->id }}">{{ Str::ucfirst($v->name) }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>   
                           </div>
                           <hr>
                           <h4 class="average-reviews__title">Change Password</h4>                            
                            <div class="d-grid gap-xxl-4 gap-3">
                                <div class="input-single">
                                    <label class="label" for="name">Current Password</label>
                                    <input type="password" class="form-control" name="opassword" placeholder="*********">
                                </div>
                                <div class="input-group">
                                    <div class="input-single">
                                        <label class="label" for="name">New Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="*********">
                                    </div>
                                    <div class="input-single">
                                        <label class="label" for="email">Confirm Password</label>
                                        <input type="password" class="form-control" name="cpassword" placeholder="*********">
                                    </div>
                                </div> 
                            </div>
                            <div class="section__cta text-start mt-xl-3 mt-2">
                                <button type="submit" class="btn_theme btn_theme_active">Update Profile <i class="bi bi-arrow-up-right"></i><span></span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section> 

@endsection

@section("jsIndex")
    <script src="{{ asset('assets/app/userPanelIndex.js') }}"></script>
@endsection
