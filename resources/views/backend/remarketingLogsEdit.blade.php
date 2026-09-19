@extends('backend.layoutIndex')


@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Remarketing Log Details </h4>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-3">
                <a href="javascript:history.back()" class="btn btn-primary waves-effect waves-light"><span
                        class="ti-xs ti ti-chevrons-left me-2"></span>Back</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable text-nowrap tab-content border border-primary ">
            <div class="row m-3">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <dl class="row">
                                <dd class="col-md-3"><strong> Date-Time :</strong></dd>
                                <dt class="col-md-9">
                                    {{ $data->rec_date }}
                                </dt>
                            </dl>
                            <hr />
                            <dl class="row">
                                <dd class="col-md-3"><strong> Message For :</strong></dd>
                                <dt class="col-md-9">
                                    {{ $data->cron_type }}
                                </dt>
                            </dl>
                            <hr />
                            <dl class="row">
                                <dd class="col-md-3"><strong> Cron Name :</strong></dd>
                                <dt class="col-md-9">
                                    {{ $data->cronname }}
                                </dt>
                            </dl>
                            <hr />
                            <dl class="row">
                                <dd class="col-md-3"><strong> Message Count :</strong></dd>
                                <dt class="col-md-9">
                                    {{ $data->msgcount }}
                                </dt>
                            </dl>
                            <hr />
                            <dl class="row">
                                <dd class="col-md-3"><strong> Message Response :</strong></dd>
                                <dt class="col-md-9">
                                    <code style="white-space: pre-wrap; word-wrap: break-word;">
                                        {{ $data->msgresponse }}
                                    </code>
                                </dt>
                            </dl>
                            <hr />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="my-5" />
@endsection



@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('appassets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
@endsection
