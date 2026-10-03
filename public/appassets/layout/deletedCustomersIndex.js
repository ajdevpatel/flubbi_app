document.addEventListener("DOMContentLoaded", function() {

    if (1 == jQuery("#ajax_deleted_datatables").length) {
        var ajax_deleted_datatables = $("#ajax_deleted_datatables").DataTable({
            stateSave: true,
            stateDuration: -1,
            processing: true,
            serverSide: true,
            scrollX: true,
            order: [[5, "desc"]],
            ajax: {
                type: "POST",
                url: $("#ajax_deleted_datatables").attr("data-url"),
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
                { data: "name", name: "name" },
                { data: "phone", name: "phone" },
                { data: "email", name: "email" },
                { data: "created_at", name: "created_at" },
                { data: "created_time", name: "created_time", orderable: false, searchable: false },
                { data: "deleted_at", name: "deleted_at" },
                { data: "deleted_time", name: "deleted_time", orderable: false, searchable: false }
            ],
            lengthMenu: window.lengthMenu,
            pageLength: 10,
            dom: 'Blfrtip',
            buttons: window.ex_button
        });

        $(document).on("click", "#filter_btn_date", function(e) {
            ajax_deleted_datatables.ajax.reload(null, true);
        });
    }

});
