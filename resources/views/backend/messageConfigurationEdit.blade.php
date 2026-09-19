@extends('backend.layoutIndex')


@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center ">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Message Edit </h4>
                <?php /* <p class="text-muted"></p> */ ?>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-3">
                <a href="javascript:history.back()">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        <span class="ti-xs ti ti-chevrons-left me-2"></span>
                        <span class="d-none d-sm-inline-block"> Back </span>
                    </button>
                </a>
            </div>
        </div>
    </div>


    <form action="{{ route('_messageConfigurationEdit', ['key' => $data->uuid]) }}" class="add-new-user pt-0"
        id="_moduleupdate" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row my-4">
            <div class="col-12 col-md-6">
                <div class="card mb-4  border-1 border-success">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label" for="label">Name</label>
                                <input type="text" class="form-control" disabled value="{{ $data->op_label }}" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">Updated At </label>
                                <input type="text" class="form-control" disabled value="{{ $data->updated_at }}" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">Message Text</label>
                                <textarea class="form-control" rows="6" name="op_value">{{ $data->op_value }}</textarea>
                            </div>
                            <div class="mb-3"> <br>
                                <button type="submit" class="btn btn-label-linkedin me-sm-3 ">
                                    <span class="ti-xs ti ti-square-plus me-2"></span>Update
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card mb-4  border-1 border-success">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3">
                                <div class="alert alert-success border-1 border-danger">
                                    <strong>Offer Message</strong>
                                </div>
                                <ul>
                                    <li>
                                        Set <#preamount> variable for amount. Replace with amount i.e. 500000
                                    </li>
                                    <li>
                                        Remain '&' symbol as it is.
                                    </li>
                                </ul>
                            </div>
                            <div class="mb-3">
                                <div class="alert alert-success border-1 border-danger">
                                    <strong>Remarketing Message</strong>
                                </div>
                                <ul>
                                    <li>
                                        Set <#cronamount> variable for amount. Replace with amount i.e. 500000
                                    </li>
                                    <li>
                                        Add %26 instad of '&' symbol.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <hr class="my-5" />
@endsection
