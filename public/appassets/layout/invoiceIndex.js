document.addEventListener("DOMContentLoaded", function() {

    if (1 == jQuery("#ajax_invoice_datatables").length) {
        var ajax_invoice_datatables = $("#ajax_invoice_datatables").DataTable({
            stateSave: true,
            stateDuration: -1,
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                type: "POST",
                url: $("#ajax_invoice_datatables").attr("data-url"),
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
                    login_type: function() {
                        return $("#login_type_filter").val();
                    }
                }
            },
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false },
                { data: "inv_no", name: "inv_no" },
                { data: "rec_date", name: "rec_date" },
                { data: "payment_id", name: "payment_id" },
                { data: "order_id", name: "order_id" },
                { data: "base_amt", name: "base_amt" },
                { data: "gst_rate", name: "gst_rate" },
                { data: "gst_amt", name: "gst_amt" },
                { data: "total_amt", name: "total_amt" },
                { data: "u_name", name: "u_name" },
                { data: "u_mail", name: "u_mail" },
                { data: "u_mobile", name: "u_mobile" },
                { data: "app_no", name: "app_no" },
                { data: "action", name: "action", orderable: false, searchable: false }
            ],
            lengthMenu: window.lengthMenu,
            pageLength: 10,
            dom: 'Blfrtip',
            buttons: window.ex_button
        });

        $(document).on("click", "#filter_btn_date", function(e) {
            ajax_invoice_datatables.ajax.reload(null, true);
        });

        $(document).on("change", "#login_type_filter", function(e) {
            ajax_invoice_datatables.ajax.reload(null, true);
        });
    }

});
