document.addEventListener("DOMContentLoaded", function() {

    $("#_addCustomerModule").validate({
        rules: {
        name: {
            required: true
        },
        r_date: {
            required: true
        },
        loan_purposes: {
            required: true
        },
        phone: {
            required: true
        },
        mail: {
            required: true,
            email: true
        },
        city: {
            required: true
        },
        pincode: {
            required: true
        },
        state: {
            required: true
        },
        user_type: {
            required: true
        },
        loan_types: {
            required: true
        },
        loan_amount: {
            required: true
        },
        income: {
            required: true
        },
        cibil_scores: {
            required: true
        },
        emi_paying: {
            required: true
        },
        emi_bounce: {
            required: true
        },
        card_amount: {
            required: true
        },
        card_number: {
            required: true
        },
        payment_id: {
            required: true
        }
        },
        messages: {},
        submitHandler: function(form) {
        return true;
        }
    });

    $(document).on("submit", "#_addCustomerModule", function(e) {
        e.preventDefault();
        if ($("#_addCustomerModule").valid() === true && $(this).attr("action")) {
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

});
