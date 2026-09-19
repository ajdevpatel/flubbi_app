document.addEventListener("DOMContentLoaded", function() {
 
	if(1 == jQuery("#_comparisonLazyLoad").length) {
		$(document).on("submit", "#_comparisonLazyLoad", function(e) {
			e.preventDefault();
			if ($("#_comparisonLazyLoad").valid() === true && $(this).attr("action")) {
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
                        $("#lazy_load_html").html(xhr.lazy_html);
                    }
                    loadLoader();
				},
				error: function(xhr) {
				  ajaxResponseFailure(xhr);
				}
			  });
			}
		});
	}

});
