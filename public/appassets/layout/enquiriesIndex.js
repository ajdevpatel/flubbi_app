document.addEventListener("DOMContentLoaded", function() {

    if (1 == jQuery("#ajax_enquiry_datatables").length) {
        var ajax_enquiry_datatables = $("#ajax_enquiry_datatables").DataTable({
            stateSave: true,
            stateDuration: -1,
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                type: "POST",
                url: $("#ajax_enquiry_datatables").attr("data-url"),
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="key-token"]').attr("content")
                },
                data: {
                    fdate: function() {
                        return $("#fdate").val();
                    },
                    tdate: function() {
                        return $("#tdate").val();
                    },
                    status: function() {
                        return $("#status_filter").val();
                    }
                }
            },
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false },
                { data: "rec_date", name: "rec_date" },
                { data: "rec_time", name: "rec_time" },
                { data: "u_name", name: "u_name" },
                { data: "u_mobile", name: "u_mobile" },
                { data: "u_mail", name: "u_mail" },
                { data: "message", name: "message", className: "text-wrap", width: "340px" },
                { data: "status_badge", name: "status_badge" },
                { data: "action", name: "action", orderable: false, searchable: false }
            ],
            lengthMenu: window.lengthMenu,
            pageLength: 10,
            dom: 'Blfrtip',
            buttons: window.ex_button
        });

        $(document).on("click", "#filter_btn_date", function(e) {
            ajax_enquiry_datatables.ajax.reload(null, true);
        });

        $(document).on("change", "#status_filter", function(e) {
            ajax_enquiry_datatables.ajax.reload(null, true);
        });
    }

});
