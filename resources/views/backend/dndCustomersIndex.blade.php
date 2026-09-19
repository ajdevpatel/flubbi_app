@extends('backend.layoutIndex')



@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> DND Customers List </h4>
                <?php /* <p class="text-muted"></p> */ ?>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-3">
                <button type="submit" class="btn btn-primary waves-effect waves-light" data-bs-target="#addNewPopupModal"
                    data-bs-toggle="offcanvas" tabindex="0">
                    <span class="ti-xs ti ti-file-plus me-2"></span>
                    <span class="d-none d-sm-inline-block">Add DND User</span>
                </button>
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
            <table class="datatables-ajax table ajax_table" id="ajax_dnd_datatables"
                data-url="{{ route('_dndCustomersIndex') }}">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Created Date</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>E-mail</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <hr class="my-5" />
@endsection


@section('addNewPopupModalIndex')
    <form action="{{ route('_dndAddRemoveIndex', [
        'key' => 'import-csv',
    ]) }}" class="add-new-user pt-0 row"
        id="dndActionModule" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <a href="{{ asset('store/dnd-sample.csv') }}" download="" class="btn btn-label-facebook me-sm-3">Download
                Sample File</a>
        </div>
        <div class="mb-3">
            <label class="form-label" for="type"> DND User File </label>
            <input type="file" name="dnd" class="form-control" />
        </div>
        <div class="divider divider-dashed">
            <div class="divider-text"></div>
        </div>
        <div class="md-3 mb-2 col-12">
            <button type="submit" class="btn btn-label-linkedin me-sm-3 ">
                <span class="ti-xs ti ti-square-plus me-2"></span>Submit
            </button>
            <button type="reset" class="btn btn-label-pinterest me-sm-3" data-bs-dismiss="offcanvas">
                <span class="ti-xs ti ti-square-x me-2"></span>Close
            </button>
        </div>
    </form>
@endsection



@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('appassets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
@endsection

@section('jsIndex')
    <script src="{{ asset('appassets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('appassets/layout/customersIndex.js') }}"></script>
@endsection
