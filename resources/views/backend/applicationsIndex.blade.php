@extends('backend.layoutIndex')



@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3">
                    @if ('' !== $stage)
                        {{ $type_name }} {{ 'lead' === $stage ? 'Leads' : 'Customers' }} List
                    @else
                        {{ $type_name }} Applications List <span
                            class="badge bg-label-primary me-1">{{ ucfirst($login_type ?? 'self') }}</span>
                    @endif
                </h4>
                @if ('' === $stage)
                    <input type="hidden" id="login_type" value="{{ $login_type }}">
                @endif
                <input type="hidden" id="type" value="{{ $type }}">
                <input type="hidden" id="stage" value="{{ $stage }}">
            </div>
            <div class="d-flex align-content-center flex-wrap gap-3">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable text-nowrap tab-content border border-primary ">
            <div class="row m-3">
                <div
                    class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="d-flex flex-column justify-content-center">
                        @if ('' !== $stage)
                            <select id="login_type" class="form-select">
                                <option value="">All</option>
                                <option value="self" @selected('self' === $login_type)>Self</option>
                                <option value="consultant" @selected('consultant' === $login_type)>Hire Agent</option>
                            </select>
                        @endif
                    </div>
                    <div class="d-flex align-content-center flex-wrap gap-3">
                        <div class="col-12">
                            <div class="input-group input-daterange" id="from-to-date">
                                <input type="date" id="fdate_two" placeholder="MM/DD/YYYY" class="form-control"
                                    value="@php
echo today()->format('Y-m-d'); @endphp" />
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
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        @if ('' !== $stage)
                            <th>Type</th>
                            <th>Payment</th>
                        @endif
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Pincode</th>
                        <th>Income</th>
                        <th>Loan Amount</th>
                        <th>Purpose</th>
                        <th>Cibil Score</th>
                        <th>Loan Tenure</th>
                        <th>Current EMI</th>
                        <th>EMI Bounce</th>
                        <th>Credit Card Usage</th>
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
    <script src="{{ asset('appassets/layout/applicationsIndex.js') }}"></script>
@endsection
