@extends('backend.layoutIndex')


@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> SMS Messages </h4>
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
                    </div>
                </div>
            </div>
            <table class="datatables-ajax table ajax_table" id="ajax_datatables" data-url="{{ route('_smsMessageIndex') }}">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Update Date</th>
                        <th>SMS Type</th>
                        <th>SMS</th>
                        <th class='text-center'>Edit</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection



@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('appassets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
@endsection

@section('jsIndex')
    <script src="{{ asset('appassets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {

        if (1 == jQuery("#ajax_datatables").length) {
            var ajax_datatables = $("#ajax_datatables").DataTable({
                stateSave: true,
                stateDuration: -1,
                processing: true,
                serverSide: true,
                ajax: {
                    type: "POST",
                    url: $("#ajax_datatables").attr("data-url"),
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="key-token"]').attr("content")
                    },
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex"
                    },
                    {
                        data: "updated_at",
                        name: "updated_at"
                    },
                    {
                        data: "op_label",
                        name: "op_label"
                    },
                    {
                        data: "op_value",
                        name: "op_value"
                    },
                    {
                        data: "edit",
                        name: "edit"
                    }
                ],
                lengthMenu: window.lengthMenu,
                pageLength: 10,
            });
        }

    });
</script>
