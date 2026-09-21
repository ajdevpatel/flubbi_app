@extends('backend.layoutIndex')


@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Support Request List </h4>
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
                                <input type="date" id="fdate" placeholder="MM/DD/YYYY" class="form-control" value="@php echo config('web.webapp.filter_from_date'); @endphp" />
                                <span class="input-group-text">To</span>
                                <input type="date" id="tdate" placeholder="MM/DD/YYYY" class="form-control" value="@php echo config('web.webapp.filter_to_date'); @endphp" />
                                <button class="btn btn-primary me-sm-3 me-1 waves-effect waves-light"
                                    id="filter_btn_date">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="datatables-ajax table ajax_table" id="ajax_datatables"
                data-url="{{ route('_supportPostIndex') }}">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Full Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Reason</th>
                        <th>Message</th>
                        <th>Ticket No</th>
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
    <script src="{{ asset('appassets/layout/supportPostIndex.js') }}"></script>
@endsection
