document.addEventListener("DOMContentLoaded", function() {

	if(1 == jQuery("#ajax_datatables").length) {
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
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex" },
                { data: "created_at", name: "created_at" },
                { data: "ticket_no", name: "ticket_no" },
                { data: "full_name", name: "full_name" },
                { data: "mobile", name: "mobile" },
                { data: "email", name: "email" },
                { data: "reason_label", name: "reason_label" },
                { data: "status", name: "status" },
                { data: "action", name: "action" }
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
