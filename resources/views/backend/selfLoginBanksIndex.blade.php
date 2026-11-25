@extends('backend.layoutIndex')



@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Self Login Banks </h4>
                <?php /* <p class="text-muted"></p> */ ?>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-3">
                <button type="button" id="addNewBankBtn" class="btn btn-primary waves-effect waves-light"
                    data-bs-target="#addNewPopupModal" data-bs-toggle="offcanvas" tabindex="0">
                    <span class="ti-xs ti ti-file-plus me-2"></span>
                    <span class="d-none d-sm-inline-block">Add New</span>
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable text-nowrap tab-content border border-primary ">
            <table class="datatables-ajax table ajax_table" id="ajax_datatables"
                data-url="{{ route('_selfLoginBanksIndex') }}">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Logo</th>
                        <th>Bank</th>
                        <th>Links</th>
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
    <form action="{{ route('_selfLoginBanksStore') }}" class="add-new-user pt-0 row" id="addBankModule" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('put')
        <input type="hidden" name="id" id="bank_id" value="">
        <div class="mb-2 col-12">
            <label class="form-label" for="label"> Bank Name </label>
            <input type="text" class="form-control" name="label" id="label" placeholder="e.g. HDFC Bank" />
        </div>
        <div class="mb-2 col-12">
            <label class="form-label" for="logo"> Logo </label>
            <input type="file" class="form-control" name="logo" id="logo" accept="image/png,image/jpeg,image/webp" />
            <small class="text-muted">Square image (PNG/JPG). Edit vakhate blank raakho to junu logo rahe.</small>
            <div class="mt-2" id="logo_preview_wrap" style="display:none;">
                <img src="" id="logo_preview" width="70" class="img-thumbnail">
            </div>
        </div>
        <div class="mb-2 col-12">
            <label class="form-label" for="pl_link"> Personal Loan (PL) Link </label>
            <textarea class="form-control" name="pl_link" id="pl_link" rows="2"></textarea>
        </div>
        <div class="mb-2 col-12">
            <label class="form-label" for="bl_link"> Business Loan (BL) Link </label>
            <textarea class="form-control" name="bl_link" id="bl_link" rows="2"></textarea>
        </div>
        <div class="mb-2 col-6">
            <label class="form-label" for="sort_order"> Sort Order </label>
            <input type="number" class="form-control" name="sort_order" id="sort_order" value="0" min="0" />
        </div>
        <div class="mb-2 col-6">
            <label class="form-label" for="status"> Status </label>
            <select name="status" id="status" class="form-select">
                <option value="1">Active</option>
                <option value="0">De-Active</option>
            </select>
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
    <script src="{{ asset('appassets/layout/selfLoginBanksIndex.js') }}"></script>
@endsection
