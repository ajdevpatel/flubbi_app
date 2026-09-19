jQuery(document).ready(function() {

    /*#########################################################*/
    if(1 == jQuery("#_userProfileModule").length) {
        jQuery("#_userProfileModule").validate({
            rules: {
                name: {
                    required: true
                },
                mail: {
                    required: true,
                    email: true,
                },
                city: {
                    required: true
                },
                state: {
                    required: true
                },
                pincode: {
                    required: true,
                    digits: true,
                    minlength: 6,
                    maxlength: 6,
                },
            },
            messages: {},
            submitHandler: function(form) {
                return true;
            }
        });
  
        jQuery(document).on("submit", "#_userProfileModule", function(e) {
        e.preventDefault();
            if (jQuery("#_userProfileModule").valid() === true && jQuery(this).attr("action")) {
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
                    },
                    error: function(xhr) {
                        ajaxResponseFailure(xhr);
                    }
                });
            }
        });
    }


    /*#########################################################*/
    if(0 != jQuery(".documentsModule").length) {
        $(document).on("submit", ".documentsModule", function(e) {
            e.preventDefault();
            if ($(this).attr("action")) {
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
                        loadLoader();
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
      
    
    /*#########################################################*/
    if(1 == jQuery("#_raiseRequestModule").length) {        
        $("#_raiseRequestModule").validate({
            rules: {
            req_reason: {
                required: true
            },
            req_message: {
                required: true
            }
            },
            messages: {},
            submitHandler: function(form) {
                return true;
            }
        });
        $(document).on("submit", "#_raiseRequestModule", function(e) {
            e.preventDefault();
            if ($(this).attr("action")) {
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
                        loadLoader();
                        if (xhr.message) {
                            Notify(xhr.message, "success");
                        }
                        setTimeout(function() {
                          location.reload();
                        }, 2000);
                    },
                    error: function(xhr) {
                        ajaxResponseFailure(xhr);
                    }
                });
            }
        });
    }
});
  