@extends('backend.layoutIndex')


@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Transactions List </h4>
                <p class="text-muted mb-0">Platform fee payments collected from the website and the mobile app.</p>
            </div>
        </div>
    </div>

    {{--
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-primary"><i
                                    class="icon-base ti ti-receipt icon-28px"></i></span>
                        </div>
                        <h4 class="mb-0" id="sum_count">0</h4>
                    </div>
                    <p class="mb-1">Transactions</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-success h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-success"><i
                                    class="icon-base ti ti-progress-check icon-28px"></i></span>
                        </div>
                        <h4 class="mb-0" id="sum_success_count">0</h4>
                    </div>
                    <p class="mb-1">Successful</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-warning h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-warning"><i
                                    class="icon-base ti ti-currency-rupee icon-28px"></i></span>
                        </div>
                        <h4 class="mb-0">&#8377; <span id="sum_success_amount">0.00</span></h4>
                    </div>
                    <p class="mb-1">Collected (success)</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-info"><i
                                    class="icon-base ti ti-receipt-2 icon-28px"></i></span>
                        </div>
                        <h4 class="mb-0">&#8377; <span id="sum_success_gst">0.00</span></h4>
                    </div>
                    <p class="mb-1">GST collected</p>
                </div>
            </div>
        </div>
    </div>
    --}}

    <div class="card">
        <div class="card-datatable text-nowrap tab-content border border-primary ">
            <div class="row m-3">
                <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div class="d-flex align-content-center flex-wrap gap-3">
                        <select id="status_filter" class="form-select">
                            <option value="">All status</option>
                            <option value="success">Success</option>
                            <option value="pending">Pending</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                        <select id="login_type_filter" class="form-select">
                            <option value="">All types</option>
                            <option value="self">Self Login</option>
                            <option value="consultant">Hire Agent</option>
                        </select>
                    </div>
                    <div class="d-flex align-content-center flex-wrap gap-3">
                        <div class="input-group input-daterange" id="from-to-date">
                            <input type="date" id="fdate" class="form-control"
                                value="{{ today()->subDays(30)->format('Y-m-d') }}" />
                            <span class="input-group-text">To</span>
                            <input type="date" id="tdate" class="form-control" value="{{ today()->format('Y-m-d') }}" />
                            <button class="btn btn-primary me-sm-3 me-1 waves-effect waves-light"
                                id="filter_btn_date">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
            <table class="datatables-ajax table ajax_table" id="ajax_tz_datatables"
                data-url="{{ route('_transactionsIndex') }}">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Gateway</th>
                        <th>Payment ID</th>
                        <th>Order ID</th>
                        <th>Total</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Application No</th>
                        <th>Loan Type</th>
                        <th>Login Type</th>
                        <th>Status</th>
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
    <script src="{{ asset('appassets/layout/transactionsIndex.js') }}"></script>
@endsection
