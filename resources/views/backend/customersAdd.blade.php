@extends('backend.layoutIndex')



@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center ">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Add Customer </h4>
                <?php /* <p class="text-muted"></p> */ ?>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-3">
                <a href="javascript:history.back()">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        <span class="ti-xs ti ti-chevrons-left me-2"></span>
                        <span class="d-none d-sm-inline-block"> Back </span>
                    </button>
                </a>
            </div>
        </div>
    </div>


    <form action="{{ route('_customersAddIndex') }}" class="add-new-user pt-0" id="_addCustomerModule" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="row my-4">

            <div class="col-12 col-md-6 ">
                <div class="card mb-4 border border-success">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3">
                                <h5 class="text-danger mb-0 btn rounded-pill btn-outline-danger waves-effect">
                                    <i class="menu-icon tf-icons ti ti-user"></i>
                                    Personal Info
                                </h5>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="label">Full name</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ app('request')->input('in_name') }}" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="label">Mobile No</label>
                                <input type="text" class="form-control numinput" inputmode="numeric" name="phone"
                                    value="{{ app('request')->input('in_phone') }}" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="label">Emai Id</label>
                                <input type="email" class="form-control" name="mail"
                                    value="{{ app('request')->input('in_mail') }}" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="label">Pin Code</label>
                                <input type="text" class="form-control" name="pincode" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="label">City</label>
                                <input type="text" class="form-control" name="city" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="is_home">State</label>
                                <select name="state" class="form-select" data-allow-clear="true">
                                    <option value="">Select State</option>
                                    @foreach ($state_index as $k => $v)
                                        <option value="{{ $v->id }}">{{ Str::ucfirst($v->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="label">Password </label>
                                <input type="text" class="form-control" name="password" value="{{ $password }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card mb-4 border border-success">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3">
                                <h5 class="text-danger mb-0 btn rounded-pill btn-outline-danger waves-effect">
                                    <i class="menu-icon tf-icons ti ti-building-bank"></i>
                                    Loan Info
                                </h5>
                                <input type="hidden" name="emi_tenure" value="60">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="name">User Type</label>
                                <select name="user_type" class="form-select" data-allow-clear="true">
                                    <option value="">User Type</option>
                                    <option value="1">Salaried Person</option>
                                    <option value="2">Self Employed Person</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="name">Loan Type</label>
                                <select name="loan_types" class="form-select" data-allow-clear="true">
                                    <option value="">Loan Type</option>
                                    @foreach ($loan_type_index as $k => $v)
                                        <option value="{{ $v->id }}">{{ Str::ucfirst($v->label) }} -
                                            {{ $v->s_price }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="name">Loan Amount</label>
                                <input type="text" class="form-control numinput" inputmode="numeric"
                                    placeholder="Loan Amount" name="loan_amount" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="name">Monthly Income</label>
                                <input type="text" class="form-control numinput" inputmode="numeric"
                                    placeholder="Monthly Income" name="income" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="name">Loan Purpose</label>
                                <select name="loan_purposes" class="form-select" data-allow-clear="true">
                                    <option value="">Select Loan Purpose</option>
                                    @foreach ($purpose_index as $k => $v)
                                        <option value="{{ $v->id }}">{{ Str::ucfirst($v->label) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="name">Cibil Score</label>
                                <select name="cibil_scores" class="form-select" data-allow-clear="true">
                                    <option value="">Select Score</option>
                                    @foreach ($cibil_score_index as $k => $v)
                                        <option value="{{ $v->id }}">{{ Str::ucfirst($v->label) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="name">Monthly EMI You are Already Paying </label>
                                <input type="text" class="form-control numinput" inputmode="numeric"
                                    placeholder="Monthly EMI You are Already Paying" name="emi_paying" />
                            </div>

                        </div>
                    </div>
                </div>
            </div>



            <div class="col-12 col-md-5">
                <button type="submit" class="btn btn-label-linkedin me-sm-3 ">
                    <span class="ti-xs ti ti-square-plus me-2"></span>Submit
                </button>
            </div>

        </div>
    </form>
    <hr class="my-5" />
@endsection


@section('jsIndex')
    <script src="{{ asset('appassets/layout/customerAdd.js') }}"></script>
@endsection
