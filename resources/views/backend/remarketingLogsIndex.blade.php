@extends('backend.layoutIndex')


@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Remarketing Logs </h4>
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
                                <input type="date" id="fdate" placeholder="MM/DD/YYYY" class="form-control"
                                    value="@php echo date('Y-m-d'); @endphp" />
                                <span class="input-group-text">To</span>
                                <input type="date" id="tdate" placeholder="MM/DD/YYYY" class="form-control"
                                    value="@php echo date('Y-m-d'); @endphp" />
                                <button class="btn btn-primary me-sm-3 me-1 waves-effect waves-light"
                                    id="filter_btn_date">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="datatables-ajax table ajax_table" id="ajax_datatables"
                data-url="{{ route('_remarketingLogsIndex') }}">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Message For</th>
                        <th>Cron Name</th>
                        <th>Message Count</th>
                        <th>Details</th>
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
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {

        if (1 == jQuery("#ajax_datatables").length) {
            var ajax_datatables = $("#ajax_datatables").DataTable({
                stateSave: true,
                stateDuration: -1,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    type: "POST",
                    url: $("#ajax_datatables").attr("data-url"),
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="key-token"]').attr("content")
                    },
                    data: {
                        fdate: function() {
                            return $("#fdate").val();
                        },
                        tdate: function() {
                            return $("#tdate").val();
                        }
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex"
                    },
                    {
                        data: "rec_date",
                        name: "rec_date"
                    },
                    {
                        data: "rec_time",
                        name: "rec_time"
                    },
                    {
                        data: "cron_type",
                        name: "cron_type"
                    },
                    {
                        data: "cronname",
                        name: "cronname"
                    },
                    {
                        data: "msgcount",
                        name: "msgcount"
                    },
                    {
                        data: "details",
                        name: "details"
                    }
                ],
                lengthMenu: window.lengthMenu,
                pageLength: 10,
                dom: 'Blfrtip',
                buttons: window.ex_button
            });

            $(document).on("click", "#filter_btn_date", function(e) {
                ajax_datatables.ajax.reload(null, true);
            });
        }

    });
</script>
