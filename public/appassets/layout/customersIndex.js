document.addEventListener("DOMContentLoaded", function () {
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
                    "X-CSRF-TOKEN": $('meta[name="key-token"]').attr("content"),
                },
                data: {
                    fdate: function () {
                        return $("#fdate_two").val();
                    },
                    tdate: function () {
                        return $("#tdate_two").val();
                    },
                    type: function () {
                        return $("#type").val();
                    },
                    login_type: function () {
                        return $("#login_type").val();
                    },
                },
            },
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex" },
                { data: "created_at", name: "created_at" },
                { data: "status", name: "status" },
                { data: "full_name", name: "full_name" },
                { data: "phone", name: "phone" },
                { data: "email", name: "email" },
                { data: "city", name: "city" },
                { data: "state", name: "state" },
                { data: "pincode", name: "pincode" },
                { data: "action", name: "action" },
            ],
            lengthMenu: window.lengthMenu,
            pageLength: 10,
            dom: "Blfrtip",
            buttons: window.ex_button,
        });

        $(document).on("click", "#filter_btn_date", function (e) {
            ajax_datatables.ajax.reload(null, true);
        });
    }

    if (1 == jQuery("#ajax_dnd_datatables").length) {
        var ajax_dnd_datatables = $("#ajax_dnd_datatables").DataTable({
            stateSave: true,
            stateDuration: -1,
            processing: true,
            serverSide: true,
            ajax: {
                type: "POST",
                url: $("#ajax_dnd_datatables").attr("data-url"),
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="key-token"]').attr("content"),
                },
                data: {
                    fdate: function () {
                        return $("#fdate_two").val();
                    },
                    tdate: function () {
                        return $("#tdate_two").val();
                    },
                },
            },
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex" },
                { data: "dnd_at", name: "dnd_at" },
                { data: "name", name: "name" },
                { data: "phone", name: "phone" },
                { data: "email", name: "email" },
                { data: "action", name: "action" },
            ],
            lengthMenu: window.lengthMenu,
            pageLength: 10,
            dom: "Blfrtip",
            buttons: window.ex_button,
        });

        $("#dndActionModule").validate({
            rules: {
                dnd_csv: {
                    required: true,
                },
            },
            messages: {},
            submitHandler: function (form) {
                return true;
            },
        });

        $(document).on("submit", "#dndActionModule", function (e) {
            e.preventDefault();
            if (
                $("#dndActionModule").valid() === true &&
                $(this).attr("action")
            ) {
                loadLoader("on");
                $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    data: new FormData(this),
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (xhr) {
                        if (xhr.message) {
                            Notify(xhr.message, "success");
                        }
                        location.reload();
                    },
                    error: function (xhr) {
                        ajaxResponseFailure(xhr);
                    },
                });
            }
        });
    }

    $(document).on("click", "#ajax_datatables .delete_btn", function (e) {
        e.preventDefault();
        if (
            confirm(
                "Are you sure you want to delete this customer and all their applications permanently?",
            )
        ) {
            loadLoader("on");
            $.ajax({
                type: "DELETE",
                url: $(this).attr("href"),
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="key-token"]').attr("content"),
                },
                success: function (xhr) {
                    if (xhr.message) {
                        Notify(xhr.message, "success");
                    }
                    location.reload();
                },
                error: function (xhr) {
                    ajaxResponseFailure(xhr);
                },
            });
        }
    });
});
