
document.addEventListener("DOMContentLoaded", function () {

    if(1 == jQuery("#_loanModule").length) {
        const inputPhone = document.getElementById("phone");
        inputPhone.addEventListener("keypress", function (e) {
            const charCode = e.which || e.keyCode;
            const charStr = String.fromCharCode(charCode);
            if (!/^\d$/.test(charStr)) {
                e.preventDefault();
                return;
            }
            if (this.value.length >= 10) {
                e.preventDefault();
            }
        });

        inputPhone.addEventListener("paste", function (e) {
            const pastedData = e.clipboardData.getData("text");
            if (!/^\d{1,10}$/.test(pastedData)) {
                e.preventDefault();
            }
        });

        jQuery.validator.addMethod("phone_number", function(value, element) {
            return this.optional(element) || /^[0-9]+$/i.test(value);
        }, "Please enter a valid phone number.");

        jQuery("#_loanModule").validate({
            rules: {
                phone: {
                    required: true,
                    phone_number: true,
                    minlength : 10,
                    maxlength : 10
                },
                amount: {
                    required: true
                },
                name: {
                    required: true
                },
                mail: {
                    required: true,
                    email: true
                }
            },
            messages: {},
            submitHandler: function(form) {
                return true;
            }
        });

        jQuery("#otp_section").show();
        //jQuery("#otp_section").hide();
        jQuery("#form_submit_btn").hide();

        jQuery(document).on("click", "#send_otp, #resend_otp", function(e) {
            e.preventDefault();
            if (jQuery("#_loanModule").valid() === true && jQuery(this).attr("data-href")) {
                loadLoader("on");
                jQuery.ajax({
                    type: "POST",
                    url: jQuery(this).attr("data-href"),
                    data: new FormData(document.querySelector("#_loanModule")),
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(xhr) {
                        if (xhr.next_step) {
                            Notify(xhr.message, "success");
                            redirectUrl(xhr.next_step,800);
                        }
                        else {
                            loadLoader();
                            Notify(xhr.message, "success");
                            jQuery("input[name='phone']").attr("readonly", true);
                            jQuery("#otp_section").show(1000);
                            jQuery("#send_otp").hide();
                            jQuery("#form_submit_btn").show();
                            resendOtp(30);
                        }
                    },
                    error: function(xhr) {
                        loadLoader();
                        ajaxResponseFailure(xhr);
                    }
                });
            }
        });

        jQuery(document).on("keydown", "#_loanModule", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
            }
        });

        jQuery(document).on("submit", "#_loanModule", function(e) {
            e.preventDefault();
            if (jQuery("#_loanModule").valid() === true && jQuery(this).attr("action")) {
                loadLoader("on");
                jQuery.ajax({
                    type: "POST",
                    url: jQuery(this).attr("action"),
                    data: new FormData(this),
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(xhr) {
                        loadLoader();
                        if (xhr.next_step) {
                            redirectUrl(xhr.next_step,100);
                        }
                    },
                    error: function(xhr) {
                        ajaxResponseFailure(xhr);
                    }
                });
            }
        });

        function resendOtp(timeout) {
            let counter = timeout;
            const link = $("#resend_otp");
            const href = link.attr("href");
            link.removeAttr("href");
            link.addClass("disabled");
            link.text("Please wait "+counter+" seconds before resending.");
            let interval = setInterval(function () {
                counter--;
                link.text("Please wait "+counter+" seconds before resending.");
                if (counter <= 0) {
                    clearInterval(interval);
                    link.attr("href", href);
                    link.text("Resend OTP");
                    $("#resend_otp").removeClass("disabled");
                }
            }, 1000);
        }
    }
    /*################################################################################*/


    if(1 == jQuery("#_eligibilityModule").length) {
        const zipInput = document.getElementById("pincode");
        zipInput.addEventListener("keypress", function (e) {
            const charCode = e.which || e.keyCode;
            const charStr = String.fromCharCode(charCode);
            if (!/^\d$/.test(charStr)) {
                e.preventDefault();
                return;
            }
            if (this.value.length >= 6) {
                e.preventDefault();
            }
        });
        zipInput.addEventListener("paste", function (e) {
            const pastedData = e.clipboardData.getData("text");
            if (!/^\d{1,6}$/.test(pastedData)) {
                e.preventDefault();
            }
        });
        zipInput.addEventListener("blur", function (e) {
            if (this.value.length !== 6) {
                e.preventDefault();
            }
        });

        const monthly_income = document.getElementById("monthly_income");
        monthly_income.addEventListener("keypress", function (e) {
            const charCode = e.which || e.keyCode;
            const charStr = String.fromCharCode(charCode);
            if (!/^\d$/.test(charStr)) {
                e.preventDefault();
                return;
            }
            if (this.value.length >= 7) {
                e.preventDefault();
            }
        });
        monthly_income.addEventListener("paste", function (e) {
            const pastedData = e.clipboardData.getData("text");
            if (!/^\d{1,7}$/.test(pastedData)) {
                e.preventDefault();
            }
        });
        monthly_income.addEventListener("blur", function (e) {
            if (this.value.length !== 7) {
                e.preventDefault();
            }
        });

        jQuery.validator.addMethod("pincode_number", function(value, element) {
            return this.optional(element) || /^[0-9]+$/i.test(value);
        }, "Please enter a valid zipcode number.");

        jQuery.validator.addMethod("monthly_number", function(value, element) {
            return this.optional(element) || /^[0-9]+$/i.test(value);
        }, "Please enter a valid amount number.");

        jQuery("#_eligibilityModule").validate({
            rules: {
                amount: {
                    required: true,
                },
                user_type: {
                    required: true
                },
                purpose: {
                    required: true
                },
                cibil_scores: {
                    required: true
                },
                monthly_income: {
                    required: true,
                    monthly_number: true,
                },
                state: {
                    required: true
                },
                city: {
                    required: true
                },
                pincode: {
                    required: true,
                    pincode_number: true,
                    minlength : 6,
                    maxlength : 6
                }
            },
            messages: {},
            submitHandler: function(form) {
                return true;
            }
        });

        jQuery(document).on("submit", "#_eligibilityModule", function(e) {
            e.preventDefault();
            if (jQuery("#_eligibilityModule").valid() === true && jQuery(this).attr("action")) {
                loadLoader("on");
                jQuery.ajax({
                    type: "POST",
                    url: jQuery(this).attr("action"),
                    data: new FormData(this),
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(xhr) {
                        loadLoader();
                        if (xhr.next_step) {
                            redirectUrl(xhr.next_step,100);
                        }
                    },
                    error: function(xhr) {
                        ajaxResponseFailure(xhr);
                    }
                });
            }
        });
    }
    /*################################################################################*/


    if(1 == jQuery("#_preApprovalModule").length) {
        jQuery("#_preApprovalModule").validate({
            rules: {
                emi_tenure: {
                    required: true
                }
            },
            messages: {},
            submitHandler: function(form) {
                return true;
            }
        });

        jQuery(document).on("submit", "#_preApprovalModule", function(e) {
            e.preventDefault();
            if (jQuery("#_preApprovalModule").valid() === true && jQuery(this).attr("action")) {
                loadLoader("on");
                jQuery.ajax({
                    type: "POST",
                    url: jQuery(this).attr("action"),
                    data: new FormData(this),
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(xhr) {
                        loadLoader();
                        if (xhr.next_step) {
                            redirectUrl(xhr.next_step,100);
                        }
                    },
                    error: function(xhr) {
                        ajaxResponseFailure(xhr);
                    }
                });
            }
        });
    }
    /*################################################################################*/
});
