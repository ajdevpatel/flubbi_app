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
                    }
                }
            },
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex" },
                { data: "rec_date", name: "rec_date" },
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
