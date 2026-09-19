@extends('backend.layoutIndex')



@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Search Customers </h4>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable text-nowrap tab-content border border-primary ">

            <div class="row mb-3 gy-3">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Search Customers</h5>
                        </div>
                        <div class="card-body">
                            <form id="searchCustomersForm" onsubmit="return false;">

                                <div class="mb-3">
                                    <label class="form-label">Mobile No</label>

                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">
                                            <i class="icon-base ti tabler-phone"></i>
                                        </span>

                                        <input type="text" name="phone" id="customerPhoneSearch"
                                            class="form-control phone-mask" placeholder="Enter mobile number" maxlength="10"
                                            pattern="[0-9]*" inputmode="numeric">
                                    </div>
                                </div>

                                <button type="button" id="searchCustomerBtn"
                                    class="btn btn-primary waves-effect waves-light">
                                    Search
                                </button>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Customers Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div id="customerLoader" class="text-center mt-3" style="display:none;">
                                    <div class="spinner-border text-primary"></div>
                                </div>

                                <ul class="list-group" id="customerCard" style="display:none;">

                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="icon-base ti ti-user icon-md me-3"></i>
                                        <b>Name :</b>
                                        <span id="customerName" class="ms-2"></span>
                                    </li>

                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="icon-base ti ti-phone icon-md me-3"></i>
                                        <b>Mobile No :</b>
                                        <span id="customerPhone" class="ms-2"></span>
                                    </li>

                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="icon-base ti ti-mail icon-md me-3"></i>
                                        <b>Email :</b>
                                        <span id="customerEmail" class="ms-2"></span>
                                    </li>

                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="icon-base ti ti-user icon-md me-3"></i>
                                        <b>Status :</b>
                                        <span id="customerStatus" class="ms-2"></span>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-center align-items-center"
                                        id="viewBtn" style="display:none;">
                                        <a id="viewDetailsLink" href="#" class="btn btn-primary">View Details <i
                                                class="icon-base ti ti-arrow-right"></i></a>
                                    </li>

                                </ul>

                                <div id="noCustomer" class="alert alert-danger mt-3" style="display:none;">
                                    Customer not found
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <hr class="my-5" />
@endsection



@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('appassets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
@endsection

@section('jsIndex')
    <script src="{{ asset('appassets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('appassets/layout/seachCustomersIndex.js') }}"></script>
@endsection
