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
                                        <li class="text-warning"><strong><i
                                                    class="bi bi-arrow-right-circle text-warning"></i>
                                                Check Eligibility</strong></li>
                                        <li><i class="bi bi-dash-circle"></i> Pre-Approval Offer</li>
                                        <li><i class="bi bi-dash-circle"></i> Buy Subscription</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-12">

                            <form
                                action="{{ route('_loanEligibilityStepIndex', [
                                    'type' => $type,
                                ]) }}"
                                id="_eligibilityModule" method="POST" enctype="multipart/form-data" class="twm-s-contact"
                                autocomplete="off">
                                @csrf
                                <input type="hidden" name="amount" value="{{ $loan_amount }}">

                                <div class="row">
                                    <div class="twm-title-large">
                                        <h3>Just a few more details to receive our Partner NBFCs' pre-approved
                                            {{ config('web.webapp.loan_word.view_file') }} offer</h3>
                                    </div>


                                    <div class="col-xl-6 col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>Are you?</label>
                                            <div class="row twm-form-radio-inline">
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    <input class="form-check-input" type="radio" name="user_type"
                                                        id="flexRadioDefault1" value="1">
                                                    Salaried
                                                </label>

                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    <input class="form-check-input" type="radio" name="user_type"
                                                        id="flexRadioDefault2" value="2" checked="">
                                                    Self-Employed
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>Monthly Income *</label>
                                            <div class="ls-inputicon-box">
                                                <input class="form-control numeric" name="monthly_income"
                                                    id="monthly_income" type="number" placeholder="Your Monthly Income"
                                                    required>
                                                <i class="fs-input-icon fas fa-rupee-sign"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label
                                                for="purpose">{{ Str::ucfirst(config('web.webapp.loan_word.view_file')) }}
                                                Purpose *</label>
                                            <div class="ls-inputicon-box">
                                                <select class="wt-select-box form-control" name="purpose">
                                                    <option value="">Choose...</option>
                                                    @if ([] != $loan_purpose_index)
                                                        @foreach ($loan_purpose_index as $k => $v)
                                                            <option value="{{ $v->id }}">
                                                                {{ Str::ucfirst($v->label) }} </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <i class="fs-input-icon fas fa-list-ul"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label class="label" for="cibil_scores">Estimated credit score</label>
                                            <div class="ls-inputicon-box">
                                                <select class="wt-select-box form-control" name="cibil_scores">
                                                    <option value="">Choose...</option>
                                                    @if ([] != $cibil_score_index)
                                                        @foreach ($cibil_score_index as $k => $v)
                                                            <option value="{{ $v->id }}">
                                                                {{ Str::ucfirst($v->label) }} </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <i class="fs-input-icon fas fa-sort-numeric-down-alt"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-12">
                                        <div class="form-group">
                                            <label class="label" for="state">State</label>
                                            <div class="ls-inputicon-box">
                                                <select class="wt-select-box form-control" name="state">
                                                    <option value="">Choose...</option>
                                                    @if ([] != $state_index)
                                                        @foreach ($state_index as $k => $v)
                                                            <option value="{{ $v->id }}">
                                                                {{ Str::ucfirst($v->name) }} </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <i class="fs-input-icon fas fa-map-marked-alt"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-12">
                                        <div class="form-group">
                                            <label class="label" for="city">City</label>
                                            <div class="ls-inputicon-box">
                                                <input class="form-control alphabet" name="city" id="city"
                                                    type="text" placeholder="Enter your city" required>
                                                <i class="fs-input-icon fas fa-map-marker-alt"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-12">
                                        <div class="form-group">
                                            <label class="label" for="pincode">Pin code</label>
                                            <div class="ls-inputicon-box">
                                                <input class="form-control numeric" name="pincode" id="pincode"
                                                    type="number" placeholder="Enter Pin code" required>
                                                <i class="fs-input-icon fas fa-map-pin"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="text-left">
                                            <button type="submit" name="calc_submit" class="site-button">Get Offer
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
@endsection


@section('jsIndex')
    <script src="{{ asset('assets/app/loanApplyIndex.js') }}"></script>
@endsection
