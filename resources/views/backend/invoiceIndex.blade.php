@extends('backend.layoutIndex')


@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Invoice List </h4>
                <p class="text-muted mb-0">Tax invoices for successful platform fee payments. Download any invoice as a
                    PDF.</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable text-nowrap tab-content border border-primary ">
            <div class="row m-3">
                <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div class="d-flex align-content-center flex-wrap gap-3">
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
            <table class="datatables-ajax table ajax_table" id="ajax_invoice_datatables"
                data-url="{{ route('_invoicesIndex') }}">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Invoice No</th>
                        <th>Date</th>
                        <th>Payment ID</th>
                        <th>Order ID</th>
                        <th>Base Amt</th>
                        <th>GST %</th>
                        <th>GST Amt</th>
                        <th>Total</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Application No</th>
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
    <script src="{{ asset('appassets/layout/invoiceIndex.js') }}"></script>
@endsection
