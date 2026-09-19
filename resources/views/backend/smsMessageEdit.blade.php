@extends('backend.layoutIndex')



@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center ">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3">Edit SMS Message</h4>
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


    <form action="{{ route('_smsMessageUpdate', ['key' => $data->uuid]) }}" class="add-new-user pt-0" id="_smsMessageEdit"
        method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $data->id }}" />
        <input type="hidden" name="type" value="log_update" />
        <div class="row my-4">
            <div class="col-12 col-md-7">
                <div class="card mb-4">
                    <div class="card-body border border-primary">
                        <div class="row">
                            <div class="col-12">
                                <h5>SMS Type - {{ $data->op_label }}</h5>
                            </div>
                            <div class="col-12">
                                <h5>Message</h5>
                                <textarea name="message" class="form-control" rows="6" placeholder="message" required>{{ $data->op_value }}</textarea>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-label-linkedin me-sm-3 ">
                                    <span class="ti-xs ti ti-square-plus me-2"></span> Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-5">
                <div class="card shadow-none bg-transparent border border-primary mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Remarketing Message</h5>
                        <p><i class="icon-base ti ti-info-circle"></i> Set &lt;#cronamount&gt; variable for amount.
                            Replace with amount i.e. 500000</p>
                        <p><i class="icon-base ti ti-info-circle"></i> Add %26 instad of '&amp;' symbol.</p>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <hr class="my-5" />
@endsection
