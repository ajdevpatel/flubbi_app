@extends('backend.layoutIndex')


@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center ">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Customer Details </h4>
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

    <div class="row">
        <div class="col-12">
            <div class="bs-stepper vertical wizard-vertical-icons-example mt-2 border border-primary">
                <div class="bs-stepper-header d-none">
                    <div class="step" data-target="#form_one">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-circle">
                                <i class="ti ti-user"></i>
                            </span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-title">Account Details</span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="bs-stepper-content">
                    <div id="form_one" class="content">
                        <div class="content-header mb-3">
                            <button type="button"
                                class="text-primary mb-0 btn rounded-pill btn-outline-primary waves-effect">
                                <b>Status :&nbsp;</b>
                                @if (1 == $user->status)
                                    Active
                                @elseif (2 == $user->status)
                                    De-Active
                                @else
                                    Pending
                                @endif
                            </button>
                        </div>
                        <div class="row g-3">
                            <form action="{{ route('_customersUpdate', ['key' => $user->uuid]) }}" class="add-new-user pt-0"
                                id="_moduleupdate" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <input type="hidden" name="p_id" value="{{ $user->p_id }}">
                                <div class="col-12 ">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="label">Created at</label>
                                                    <input type="text" disabled class="form-control"
                                                        value="{{ $user->created_at }}" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="label">Updated at</label>
                                                    <input type="text" disabled class="form-control"
                                                        value="{{ $user->updated_at }}" />
                                                </div>
                                                <div class="mb-3 col-12">
                                                    <hr>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="label">Full name</label>
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ $user->name }}" />
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="label">Mobile No</label>
                                                    <input type="text" class="form-control numinput" inputmode="numeric"
                                                        name="phone" value="{{ $user->phone }}" />
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="label">Emai Id</label>
                                                    <input type="email" class="form-control" name="mail"
                                                        value="{{ $user->email }}" />
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="label">City</label>
                                                    <input type="text" class="form-control" name="city"
                                                        value="{{ $user->city }}" />
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="label">Pin Code</label>
                                                    <input type="text" class="form-control" name="pincode"
                                                        value="{{ $user->pincode }}" />
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="is_home">State</label>
                                                    <select name="state" class="form-select" data-allow-clear="true">
                                                        <option value="">Select State</option>
                                                        @foreach ($state_list as $k => $v)
                                                            <option @if ($user->state_id == $v->id) selected @endif
                                                                value="{{ $v->id }}">{{ Str::ucfirst($v->name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="status">Status</label>
                                                    <select name="status" class="form-select" data-allow-clear="true">
                                                        <option>Select Status</option>
                                                        <option @if (0 == $user->status) selected @endif
                                                            value="0">Pending</option>
                                                        <option @if (1 == $user->status) selected @endif
                                                            value="1">Active</option>
                                                        <option @if (2 == $user->status) selected @endif
                                                            value="2">De-Active</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-12">
                                                    <hr>
                                                    <h4><b>Change Password</b></h4>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="label">Password </label>
                                                    <input type="password" class="form-control" name="password" />
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="label">Retype Password </label>
                                                    <input type="password" class="form-control" name="cpassword" />
                                                </div>
                                                <div class="mb-3 col-md-2" id="g-pwd-section">
                                                    <label class="form-label" for="label">Generate Password </label>
                                                    <input type="text" disabled class="form-control" id="g_pwd">
                                                </div>
                                                <div class="mb-3 col-md-2">
                                                    <br>
                                                    <button type="button" id="generate_pwd"
                                                        class="btn btn-primary btn-toggle-sidebar waves-effect waves-light">Generate</button>
                                                </div>
                                                <div class="mb-3 col-12">
                                                    <hr class="my-2" />
                                                    <button type="submit"
                                                        class="btn btn-outline-danger waves-effect me-sm-3">
                                                        <span class="ti-xs ti ti-square-plus me-2"></span>Submit
                                                    </button>
                                                </div>
                                            </div>
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
    <hr class="my-5" />
@endsection



@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('appassets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('appassets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
@endsection

@section('jsIndex')
    <script src="{{ asset('appassets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('appassets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('appassets/layout/customerEdit.js') }}"></script>
@endsection
