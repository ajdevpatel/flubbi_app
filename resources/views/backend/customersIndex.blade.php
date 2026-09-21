@extends('backend.layoutIndex')



@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3">
                    {{ ucfirst($type_name) }} Customers List
                </h4>
                <input type="hidden" id="type" value="{{ $type }}">
                <input type="hidden" id="login_type" value="{{ $login_type }}">
            </div>
            <div class="d-flex align-content-center flex-wrap gap-3">
                <a href="{{ route('_customersAddIndex') }}">
                    <button type="submit" class="btn btn-primary waves-effect waves-light" tabindex="0">
                        <span class="ti-xs ti ti-file-plus me-2"></span>
                        <span class="d-none d-sm-inline-block">Add New</span>
                    </button>
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable text-nowrap tab-content border border-primary ">
            <div class="row m-3">
                <div
                    class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="d-flex flex-column justify-content-center">
                    </div>
                    <div class="d-flex align-content-center flex-wrap gap-3">
                        <div class="col-12">
                            <div class="input-group input-daterange" id="from-to-date">
                                <input type="date" id="fdate_two" placeholder="MM/DD/YYYY" class="form-control"
                                    value="@php echo today()->format('Y-m-d'); @endphp" />
                                <span class="input-group-text">To</span>
                                <input type="date" id="tdate_two" placeholder="MM/DD/YYYY" class="form-control"
                                    value="@php
echo today()->format('Y-m-d'); @endphp" />
                                <button class="btn btn-primary me-sm-3 me-1 waves-effect waves-light"
                                    id="filter_btn_date">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="datatables-ajax table ajax_table" id="ajax_datatables" data-url="{{ request()->url() }}">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Created Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Full Name</th>
                        <th>Phone</th>
                        <th>E-mail</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Pin Code</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <hr class="my-5" />
@endsection



@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('appassets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
@endsection

@section('jsIndex')
    <script src="{{ asset('appassets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('appassets/layout/customersIndex.js') }}"></script>
@endsection
