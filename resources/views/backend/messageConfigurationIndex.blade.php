@extends('backend.layoutIndex')



@section('bodyIndex')

    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Message Configuration </h4>
                <?php /* <p class="text-muted"></p> */ ?>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-3">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable text-nowrap tab-content border border-primary ">
            <table class="datatables-ajax table ajax_table" id="ajax_datatables" data-url="{{ route('_customersIndex') }}">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Updated At</th>
                        <th>Type</th>
                        <th>Message Text</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <body>
                    @if ([] != $message_list)
                        @foreach ($message_list as $k => $v)
                            <tr>
                                <td> {{ $k + 1 }} </td>
                                <td> {{ $v->updated_at }} </td>
                                <td> {{ $v->op_label }} </td>
                                <td>
                                    <textarea disabled class="form-control" rows="5">{{ $v->op_value }}</textarea>
                                </td>
                                <td>
                                    <a
                                        href="{{ route('_messageConfigurationEdit', [
                                            'key' => $v->uuid,
                                        ]) }}">
                                        <button class="mx-1 btn btn-icon btn-label-primary waves-effect"
                                            fdprocessedid="h1nmak">
                                            <span class="ti ti-edit"></span>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </body>
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
@endsection
