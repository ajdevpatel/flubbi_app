@extends('backend.layoutIndex')


@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Support Request List </h4>
                <?php /* <p class="text-muted"></p> */ ?>
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
                                <input type="date" id="fdate" placeholder="MM/DD/YYYY" class="form-control" value="@php echo config('web.webapp.filter_from_date'); @endphp" />
                                <span class="input-group-text">To</span>
                                <input type="date" id="tdate" placeholder="MM/DD/YYYY" class="form-control" value="@php echo config('web.webapp.filter_to_date'); @endphp" />
                                <button class="btn btn-primary me-sm-3 me-1 waves-effect waves-light"
                                    id="filter_btn_date">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="datatables-ajax table ajax_table" id="ajax_datatables"
                data-url="{{ route('_supportPostIndex') }}">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Date</th>
                        <th>Full Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Reason</th>
                        <th>Message</th>
                        <th>Ticket No</th>
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

	if(1 == jQuery("#ajax_datatables").length) {
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
                data: {
                    fdate: function() {
                        return $("#fdate").val();
                    },
                    tdate: function() {
                        return $("#tdate").val();
                    }
                }
            },
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex" },
                { data: "created_at", name: "created_at" },
                { data: "name", name: "name" },
                { data: "mobile", name: "mobile" },
                { data: "email", name: "email" },
                { data: "reason_label", name: "reason_label" },
                { data: "message", name: "message" },
                { data: "ticket_no", name: "ticket_no" }
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

	if(1 == jQuery("#_supportUpdateModule").length) {
        $("#_supportUpdateModule").validate({
            rules: {
                status: {
                    required: true
                },
                note: {
                    required: true
                }
            },
            messages: {},
            submitHandler: function(form) {
                return true;
            }
        });

        $(document).on("submit", "#_supportUpdateModule", function(e) {
            e.preventDefault();
            if ($("#_supportUpdateModule").valid() === true && $(this).attr("action")) {
                loadLoader("on");
                $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    data: new FormData(this),
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(xhr) {
                        if (xhr.message) {
                            Notify(xhr.message, "success");
                        }
                        location.reload();
                    },
                    error: function(xhr) {
                        ajaxResponseFailure(xhr);
                    }
                });
            }
        });
    }


});
</script>