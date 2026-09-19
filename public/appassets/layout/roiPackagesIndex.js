document.addEventListener("DOMContentLoaded", function() {

	if(1 == jQuery("#ajax_datatables").length) {
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
					"X-CSRF-TOKEN": $('meta[name="key-token"]').attr("content")
				}
			},
			columns: [
				{ data: "DT_RowIndex", name: "DT_RowIndex" },
				{ data: "partner_logo", name: "partner_logo" },
				{ data: "our_partner_label", name: "our_partner_label" },
				{ data: "roi", name: "roi" },
				{ data: "terms_years", name: "terms_years" },
				{ data: "status", name: "status" },
				{ data: "action", name: "action" }
			],
			lengthMenu: window.lengthMenu,
			pageLength: 10,
			dom: 'Blfrtip',
			buttons: window.ex_button
		});

		$("#addroiPackageModule").validate({
			rules: {
				partner: {
					required: true
				},
				type: {
					required: true
				},
				min: {
					required: true
				},
				max: {
					required: true
				},
				roi: {
					required: true
				},
				fee: {
					required: true
				},
				t_years: {
					required: true
				},
				t_months: {
					required: true
				},
				label: {
					required: true
				},
			},
			messages: {},
			submitHandler: function(form) {
			return true;
			}
		});

		$(document).on("submit", "#addroiPackageModule", function(e) {
			e.preventDefault();
			if ($("#addroiPackageModule").valid() === true && $(this).attr("action")) {
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
							loadLoader();
						}
						ajax_datatables.ajax.reload(null, true);
						setTimeout(function() {
							$("button.btn-close").click();
						}, 100);
					},
					error: function(xhr) {
						ajaxResponseFailure(xhr);
					}
				});
			}
		});
	}

});
