document.addEventListener("DOMContentLoaded", function() {

    if(1 == jQuery("#_offerLeadModule").length) {
        
        const chatInputPhone = document.querySelectorAll("#_offerLeadModule input[name='phone']"); 
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

        jQuery.validator.addMethod("phone", function(value, element) {
            return this.optional(element) || /^[0-9]{10}$/.test(value); // Simple validation for 10-digit numbers
        }, "Please enter a valid 10-digit phone number.");

        jQuery("#_offerLeadModule").validate({
    		rules: {
    		    name: {
    		      	required: true
    		    },
    		    phone: {
    		          required: true,
    		          phone: true
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
          
        jQuery(document).on("submit", "#_offerLeadModule", function(e) {
          	e.preventDefault();
            if (jQuery("#_offerLeadModule").valid() === true && jQuery(this).attr("action")) {
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
                            redirectUrl(xhr.next_step,800);
                        }
                    },
                    error: function(xhr) {
                        ajaxResponseFailure(xhr);
                    }
                });
            }
        });
    }
    
});
