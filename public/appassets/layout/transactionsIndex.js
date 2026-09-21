document.addEventListener("DOMContentLoaded", function() {

	if(1 == jQuery("#ajax_tz_datatables").length) {
        var ajax_tz_datatables = $("#ajax_tz_datatables").DataTable({
            stateSave: true,
            stateDuration: -1,
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                type: "POST",
                url: $("#ajax_tz_datatables").attr("data-url"),
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
                    },
                    login_type: function() {
                        return $("#login_type_filter").val();
                    }
                },
                dataSrc: function(json) {
                    if (json.summary) {
                        $("#sum_count").text(json.summary.count);
                        $("#sum_success_count").text(json.summary.success_count);
                        $("#sum_success_amount").text(json.summary.success_amount);
                        $("#sum_success_gst").text(json.summary.success_gst);
                    }
                    return json.data;
                }
            },
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false },
                { data: "rec_date", name: "rec_date" },
                { data: "rec_time", name: "rec_time" },
                { data: "gateway", name: "gateway" },
                { data: "payment_id", name: "payment_id" },
                { data: "order_id", name: "order_id" },
                { data: "total_amt", name: "total_amt" },
                { data: "u_name", name: "u_name" },
                { data: "u_mobile", name: "u_mobile" },
                { data: "u_mail", name: "u_mail" },
                { data: "app_no", name: "app_no" },
                { data: "loan_type", name: "loan_type" },
                { data: "login_type", name: "login_type" },
                { data: "status", name: "status" },
                { data: "action", name: "action", orderable: false, searchable: false }
            ],
            lengthMenu: window.lengthMenu,
            pageLength: 10,
            dom: 'Blfrtip',
            buttons: window.ex_button
        });

        $(document).on("click", "#filter_btn_date", function(e) {
            ajax_tz_datatables.ajax.reload(null, true);
        });

        $(document).on("change", "#status_filter, #login_type_filter", function(e) {
            ajax_tz_datatables.ajax.reload(null, true);
        });
    }

	if(1 == jQuery("#ajax_sup_datatables").length) {
        var ajax_sup_datatables = $("#ajax_sup_datatables").DataTable({
            stateSave: true,
            stateDuration: -1,
            processing: true,
            serverSide: true,
            ajax: {
                type: "POST",
                url: $("#ajax_sup_datatables").attr("data-url"),
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
                { data: "rec_date", name: "rec_date" },
                { data: "card_number", name: "card_number" },
                { data: "transaction_id", name: "transaction_id" },
                { data: "u_name", name: "u_name" },
                { data: "action", name: "action" }
            ],
            lengthMenu: window.lengthMenu,
            pageLength: 10,
            dom: 'Blfrtip',
            buttons: window.ex_button
        });

        $(document).on("click", "#filter_btn_date", function(e) {
            ajax_sup_datatables.ajax.reload(null, true);
        });
    }


});
