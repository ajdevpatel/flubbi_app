@extends('backend.layoutIndex')


@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3">Remarketing User Statistics</h4>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable text-nowrap tab-content border border-primary ">
            <div class="card-header p-0">
                <h5 class="card-title"><span class="badge bg-label-info">SMS</span> Remarketing User Statistics</h5>
            </div>
            <div class="row">
                @foreach ($sms_data as $item)
                    <div class="col-lg-3 col-sm-6 mb-3">
                        <div class="card card-border-shadow-info h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-1">
                                    <div class="avatar me-4">
                                        <span class="avatar-initial rounded bg-label-info"><i
                                                class="icon-base ti ti-users icon-28px"></i></span>
                                    </div>
                                    <h4 class="mb-0">{{ $item['applications'] }}</h4>
                                </div>
                                <p class="mb-1">Date : {{ $item['udate'] }}</p>
                                <p class="mb-0">
                                    <span class="text-heading fw-medium me-2">Days : <span
                                            class="badge bg-label-info">{{ $item['day'] }}</span></span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-datatable text-nowrap tab-content border border-primary ">
            <div class="card-header p-0">
                <h5 class="card-title"><span class="badge bg-label-success">Whatsapp</span> Remarketing User Statistics</h5>
            </div>
            <div class="row">
                @foreach ($whatsapp_data as $item)
                    <div class="col-lg-3 col-sm-6 mb-3">
                        <div class="card card-border-shadow-success h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-1">
                                    <div class="avatar me-4">
                                        <span class="avatar-initial rounded bg-label-success"><i
                                                class="icon-base ti ti-users icon-28px"></i></span>
                                    </div>
                                    <h4 class="mb-0">{{ $item['applications'] }}</h4>
                                </div>
                                <p class="mb-1">Date : {{ $item['udate'] }}</p>
                                <p class="mb-0">
                                    <span class="text-heading fw-medium me-2">Days : <span
                                            class="badge bg-label-success">{{ $item['day'] }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <hr class="my-5" />
@endsection

@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('appassets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
@endsection

@section('jsIndex')
    <script src="{{ asset('appassets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection
