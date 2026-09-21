@extends('backend.layoutIndex')


@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> OTP Logs </h4>
                <?php /* <p class="text-muted"></p> */ ?>
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
                    </div>
                    <div class="d-flex align-content-center flex-wrap gap-3">
                        <div class="col-12">
                            <div class="input-group input-daterange" id="from-to-date">
                                <input type="date" id="fdate" placeholder="MM/DD/YYYY" class="form-control"
                                    value="@php
echo today()->format('Y-m-d'); @endphp" />
                                <span class="input-group-text">To</span>
                                <input type="date" id="tdate" placeholder="MM/DD/YYYY" class="form-control"
                                    value="@php
echo today()->format('Y-m-d'); @endphp" />
                                <button class="btn btn-primary me-sm-3 me-1 waves-effect waves-light"
                                    id="filter_btn_date">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="datatables-ajax table ajax_table" id="ajax_otp_logs_datatables"
                data-url="{{ route('_otpLogsIndex') }}">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Mobile</th>
                        <th>OTP</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Time</th>
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
    <script src="{{ asset('appassets/layout/manualMarketingIndex.js') }}"></script>
@endsection
