
$.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": jQuery('meta[name="csrf-token"]').attr("content")
    }
});

function loadLoader(action = "off") {
    if (jQuery(".preloader").length != 0) {
        if (action === "on") {
            jQuery(".preloader").delay(10).animate({
                "opacity": "0.5"
            }).fadeIn(500);
            return true; 
        }
        jQuery(".preloader").delay(200).fadeOut(500);
    }
    return true;
}
 
var loaderOn = document.querySelector(".preloader");
if (loaderOn !== null && typeof loaderOn !== "undefined") {
    loadLoader("on");
}
document.addEventListener("DOMContentLoaded", () => loadLoader());

function Notify(message = "", type = "error", timeOut = 7000) {
    toastr.options = {
        maxOpened: 10,
        autoDismiss: true,
        closeButton: true,
        positionClass: "toast-top-right",
        showDuration: parseInt(300),
        hideDuration: parseInt(1000),
        timeOut: parseInt(timeOut),
        extendedTimeOut: parseInt(1000),
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
        newestOnTop: true,
        progressBar: true,
        debug: false,
        preventDuplicates: false,
        onclick: null,
        rtl: "ltr"
    };
  
    //Type :- success,info,warning,error
    //Using toastr[type] directly
    var $toast = toastr[type](message, "");
  
    // Returning false if $toast is undefined - false
    return $toast !== undefined;
}

function ajaxResponseFailure(xhr) {
    loadLoader();
    if (xhr.status === 422) {
        var x_msg = "";
        $.each(xhr.responseJSON.errors, function(key, value) {
            x_msg += value.join(", ") + "<br>";
        });
        Notify(x_msg);
    } else {
        Notify("Try this action again after refreshing your credentials and page");
    }
}

function redirectUrl(url = "", timeOut = 1400) {
    loadLoader();
    if (url != "") {
        setTimeout(function() {
            window.location.href = url;
        }, timeOut);
    }
}

document.addEventListener("contextmenu", function(event) {
    event.preventDefault();
});
document.addEventListener("keydown", function(event) {
    if (event.keyCode == 123) {
        event.preventDefault();
    }
    if (event.ctrlKey && event.shiftKey && event.keyCode == 'I'.charCodeAt(0)) {
        event.preventDefault();
    }
    if (event.ctrlKey && event.shiftKey && event.keyCode == 'J'.charCodeAt(0)) {
        event.preventDefault();
    }
    if (event.ctrlKey && event.keyCode == 'U'.charCodeAt(0)) {
        event.preventDefault();
    }
}); 



/********************************************************************/
document.addEventListener('DOMContentLoaded', function () {
    
    if(0 != jQuery(".alphabet").length) {
        const alphabetInputs = document.querySelectorAll(".alphabet");
        alphabetInputs.forEach(function (input) {
            input.addEventListener("keypress", function (e) {
                const charCode = e.which || e.keyCode;
                const charStr = String.fromCharCode(charCode);
                if (!/^[a-zA-Z ]$/.test(charStr)) {
                    e.preventDefault();
                }
            });
            input.addEventListener("paste", function (e) {
                const pastedData = e.clipboardData.getData("text");
                if (!/^[a-zA-Z]+$/.test(pastedData)) {
                    e.preventDefault();
                }
            });
        });
    }

    if(0 != jQuery(".numeric").length) {
        const numericInputs = document.querySelectorAll(".numeric");
        numericInputs.forEach(function (input) {
            input.addEventListener("keypress", function (e) {
                const charCode = e.which || e.keyCode;
                const charStr = String.fromCharCode(charCode);
                if (!/^\d$/.test(charStr)) {
                    e.preventDefault();
                }
            });
    
            input.addEventListener("paste", function (e) {
                const pastedData = e.clipboardData.getData("text");
                if (!/^\d+$/.test(pastedData)) {
                    e.preventDefault();
                }
            });
        });
    }

    if(0 != jQuery(".alpha-numeric").length) {
        const alphaNumericInputs = document.querySelectorAll(".alpha-numeric");
        alphaNumericInputs.forEach(function (input) {
            input.addEventListener("keypress", function (e) {
                const charCode = e.which || e.keyCode;
                const charStr = String.fromCharCode(charCode);
                if (!/^[a-zA-Z0-9]$/.test(charStr)) {
                    e.preventDefault();
                }
            });
            input.addEventListener("paste", function (e) {
                const pastedData = e.clipboardData.getData("text");
                if (!/^[a-zA-Z0-9]+$/.test(pastedData)) {
                    e.preventDefault();
                }
            });
        });
    }
    /********************************************************************/


    if(1 == jQuery("#fixed-form-container").length) {  
        jQuery("#fixed-form-container .enquiry-body").hide();
        jQuery("#fixed-form-container .enquiry-button").click(function () {
            jQuery(this).next("#fixed-form-container div").slideToggle(400);
            jQuery(this).toggleClass("expanded");
        });

        jQuery(".enquiry-now-btn, .chat-lead-enquiry-btn").click(function () {
            jQuery("#fixed-form-container .enquiry-button").click();
        });
    }

    if(1 == jQuery("#cta_toggle").length) {    
        jQuery("#cta_toggle").on("click", function (ev) {
            jQuery("body").toggleClass("shadow_bg");
            jQuery(this).parents("#cta_menu").toggleClass("activated");
        });
    }
    /********************************************************************/

    if(1 == jQuery("#_chatEnquiry").length) {
        const chatInputPhone = document.querySelectorAll("#_chatEnquiry input[name='phone']"); 
        chatInputPhone.forEach(function (chatInput) {
            chatInput.addEventListener("keypress", function (e) {
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
        });

        jQuery("#_chatEnquiry").validate({
            rules: {
                name: {
                    required: true
                },
                phone: {
                    required: true,
                    minlength: 10,
                    maxlength: 12
                },
                type: {
                    required: true,
                },
                amount: {
                    required: true
                }
            },
            messages: {},
            submitHandler: function(form) {
                return true;
            }
        });

        jQuery(document).on("submit", "#_chatEnquiry", function(e) {
            e.preventDefault();
            if (jQuery("#_chatEnquiry").valid() === true && jQuery(this).attr("action")) {
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
                        loadLoader();
                        if (xhr.message) {
                            Notify(xhr.message, "success");
                        }
                        setTimeout(function() {
                            jQuery("#_chatEnquiry")[0].reset();
                            jQuery("#fixed-form-container .button").click();
                        }, 500);
                    },
                    error: function(xhr) {
                        ajaxResponseFailure(xhr);
                    }
                });
            }
        });
    }
    /********************************************************************/


    if(1 == jQuery("#_userLogin").length) {
        jQuery("#_userLogin").validate({
            rules: {
                userid: {
                    required: true,
                },
                password: {
                    required: true
                }
            },
            messages: {},
            submitHandler: function(form) {
                return true;
            }
        });

        jQuery(document).on("submit", "#_userLogin", function(e) {
            e.preventDefault();
            if (jQuery("#_userLogin").valid() === true && jQuery(this).attr("action")) {
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
                        loadLoader();
                        if (xhr.message) {
                            Notify(xhr.message, "success");
                        }
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
    /********************************************************************/


    if(1 == jQuery("#_forgotpassword").length) {
        jQuery("#_forgotpassword").validate({
            rules: {
                loginKey: {
                    required: true,
                    email: true
                },
            },
            messages: {},
            submitHandler: function(form) {
                return true;
            }
        });

        jQuery(document).on("submit", "#_forgotpassword", function(e) {
            e.preventDefault();
            if (jQuery("#_forgotpassword").valid() === true && jQuery(this).attr("action")) {
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
                        loadLoader();
                        if (xhr.message) {
                            Notify(xhr.message, "success");
                        }
                        if (xhr.next_step) {
                            redirectUrl(xhr.next_step,2000);
                        }
                    },
                    error: function(xhr) {
                        ajaxResponseFailure(xhr);
                    }
                });
            }
        });
    }
    /********************************************************************/

});