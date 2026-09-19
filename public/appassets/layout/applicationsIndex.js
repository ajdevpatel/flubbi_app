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
                    login_type: function () {
                        return $("#login_type").val();
                    },
                    type: function () {
                        return $("#type").val();
                    },
                },
            },

            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex" },
                { data: "rec_date", name: "rec_date" },
                { data: "status", name: "status" },
                { data: "u_name", name: "u_name" },
                { data: "phone", name: "phone" },
                { data: "email", name: "email" },
                { data: "state", name: "state" },
                { data: "city", name: "city" },
                { data: "pincode", name: "pincode" },
                { data: "income", name: "income" },
                { data: "loan_amount", name: "loan_amount" },
                { data: "loan_purposes", name: "loan_purposes" },
                { data: "cibil_scores", name: "cibil_scores" },
                { data: "loantenure", name: "loantenure" },
                { data: "current_emi", name: "current_emi" },
                { data: "emi_bounce", name: "emi_bounce" },
                { data: "credit_card_usage", name: "credit_card_usage" },
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

    if (1 == jQuery("#ajax_applications_status").length) {
        jQuery("#ajax_applications_status").DataTable();
    }

    if (1 == jQuery("#_addStatusModule").length) {
        $(".show-field").hide();
        $(document).on("change", "#app_status", function (e) {
            if (10 == $(this).val()) {
                $(".show-field").show();
                return true;
            }
            $(".show-field").hide();
            return true;
        });

    }
});
