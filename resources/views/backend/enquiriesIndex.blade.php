@extends('backend.layoutIndex')


@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Enquiries </h4>
                <p class="text-muted mb-0">Messages sent from the Contact Us page and the home page enquiry form.</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable text-nowrap tab-content border border-primary ">
            <div class="row m-3">
                <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div class="d-flex align-content-center flex-wrap gap-3">
                        <select id="status_filter" class="form-select">
                            <option value="">All status</option>
                            <option value="0">Pending</option>
                            <option value="1">In Progress</option>
                            <option value="2">Done</option>
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
            <table class="datatables-ajax table ajax_table" id="ajax_enquiry_datatables"
                data-url="{{ route('_enquiriesIndex') }}">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Message</th>
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
    <script src="{{ asset('appassets/layout/enquiriesIndex.js') }}"></script>
@endsection
