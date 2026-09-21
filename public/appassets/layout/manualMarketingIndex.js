document.addEventListener("DOMContentLoaded", function() {

	if(1 == jQuery("#ajax_manual_datatables").length) {
        var ajax_manual_datatables = jQuery("#ajax_manual_datatables").DataTable({
            stateSave: true,
            stateDuration: -1,
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                type: "POST",
                url: jQuery("#ajax_manual_datatables").attr("data-url"),
                headers: {
                    "X-CSRF-TOKEN": jQuery('meta[name="key-token"]').attr("content")
                },
                data: {
                    fdate: function() {
                        return jQuery("#fdate").val();
                    },
                    tdate: function() {
                        return jQuery("#tdate").val();
                    }
                }
            },
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex" },
                { data: "rec_date", name: "rec_date" },
                { data: "is_phone", name: "is_phone" },
                { data: "status", name: "status" },
                { data: "message_text", name: "message_text" },
                { data: "action", name: "action" }
            ],
            lengthMenu: window.lengthMenu,
            pageLength: 10,
            dom: 'Blfrtip',
            buttons: window.ex_button
        });

        jQuery(document).on("click", "#filter_btn_date", function(e) {
            ajax_manual_datatables.ajax.reload(null, true);
        });
    }

	if(1 == jQuery("#addManualModule").length) {
        $("#addManualModule").validate({
            rules: {
                name: {
                    required: true
                },
                r_date: {
                    required: true
                },
                import_file: {
                    required: true
                }
            },
            messages: {},
            submitHandler: function(form) {
            return true;
            }
        });

        jQuery(document).on("submit", "#addManualModule", function(e) {
            e.preventDefault();
            if (jQuery("#addManualModule").valid() === true && jQuery(this).attr("action")) {
                loadLoader("on");
                $.ajax({
                    type: "POST",
                    url: jQuery(this).attr("action"),
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


	if(1 == jQuery("#ajax_otp_logs_datatables").length) {
        var ajax_otp_logs_datatables = jQuery("#ajax_otp_logs_datatables").DataTable({
            stateSave: true,
            stateDuration: -1,
            processing: true,
            serverSide: true,
            ajax: {
                type: "POST",
                url: jQuery("#ajax_otp_logs_datatables").attr("data-url"),
                headers: {
                    "X-CSRF-TOKEN": jQuery('meta[name="key-token"]').attr("content")
                },
                data: {
                    fdate: function() {
                        return jQuery("#fdate").val();
                    },
                    tdate: function() {
                        return jQuery("#tdate").val();
                    }
                }
            },
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex" },
                { data: "phone", name: "phone" },
                { data: "otp", name: "otp" },
                { data: "status", name: "status" },
                { data: "date", name: "created_at" },
                { data: "time", name: "created_at" }
            ],
            lengthMenu: window.lengthMenu,
            pageLength: 10,
            dom: 'Blfrtip',
            buttons: window.ex_button
        });

        jQuery(document).on("click", "#filter_btn_date", function(e) {
            ajax_otp_logs_datatables.ajax.reload(null, true);
        });
    }

});
