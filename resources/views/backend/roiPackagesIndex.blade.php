@extends('backend.layoutIndex')



@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> ROI Packages List </h4>
                <?php /* <p class="text-muted"></p> */ ?>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-3">
                <button type="submit" class="btn btn-primary waves-effect waves-light" data-bs-target="#addNewPopupModal"
                    data-bs-toggle="offcanvas" tabindex="0">
                    <span class="ti-xs ti ti-file-plus me-2"></span>
                    <span class="d-none d-sm-inline-block">Add New</span>
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable text-nowrap tab-content border border-primary ">
            <table class="datatables-ajax table ajax_table" id="ajax_datatables"
                data-url="{{ route('_roiPackagesIndex') }}">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Partner/NBFCs</th>
                        <th>Partner Label</th>
                        <th>ROI</th>
                        <th>Terms</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <hr class="my-5" />
@endsection


@section('addNewPopupModalIndex')
    <form action="{{ route('_roiPackagesStore') }}" class="add-new-user pt-0 row" id="addroiPackageModule" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="mb-2 col-6">
            <label class="form-label" for="type"> Bank/NBFCs </label>
            <select name="partner" class="form-select">
                <option value="">Partner/Bank</option>
                @foreach ($our_partners_index as $k => $v)
                    <option value="{{ $v->id }}">{{ Str::ucfirst($v->label) }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-6">
            <label class="form-label" for="type"> Type </label>
            <select name="type" class="form-select">
                <option value="">Loan Type</option>
                @foreach ($loan_type_index as $k => $v)
                    <option value="{{ $v->id }}">{{ Str::ucfirst($v->label) }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2 col-6">
            <label class="form-label" for="type"> Min ROI </label>
            <input type="text" class="form-control" name="min" />
        </div>
        <div class="mb-2 col-6">
            <label class="form-label" for="type"> Max ROI </label>
            <input type="text" class="form-control" name="max" />
        </div>
        <div class="mb-2 col-6">
            <label class="form-label" for="type"> ROI </label>
            <input type="text" class="form-control" name="roi" />
        </div>
        <div class="mb-2 col-6">
            <label class="form-label" for="type"> Processing Fee </label>
            <input type="text" class="form-control" name="fee" />
        </div>
        <div class="mb-2 col-6">
            <label class="form-label" for="type"> Terms - Years </label>
            <input type="text" class="form-control" name="t_years" />
        </div>
        <div class="mb-2 col-6">
            <label class="form-label" for="type"> Terms - Months </label>
            <input type="text" class="form-control" name="t_months" />
        </div>
        <div class="mb-2 col-12">
            <label class="form-label" for="type"> Label </label>
            <input type="text" class="form-control" name="label" />
        </div>
        <div class="mb-2 col-12">
            <label class="form-label" for="type"> Description </label>
            <textarea class="form-control" name="description" rows="3"></textarea>
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
    <script src="{{ asset('appassets/layout/roiPackagesIndex.js') }}"></script>
@endsection
