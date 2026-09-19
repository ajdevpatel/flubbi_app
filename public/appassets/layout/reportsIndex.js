document.addEventListener("DOMContentLoaded", function() {

	if(1 == jQuery("#ajax_report_gst_datatables").length) {
		var ajax_report_gst_datatables = $("#ajax_report_gst_datatables").DataTable({
			stateSave: true,
			stateDuration: -1,
			processing: true,
			serverSide: true,
            scrollX: true,
			ajax: {
				type: "POST",
				url: $("#ajax_report_gst_datatables").attr("data-url"),
				headers: {
					"X-CSRF-TOKEN": $('meta[name="key-token"]').attr("content")
				},
				data: {
					fdate: function() {
						return $("#fdate_two").val();
					},
					tdate: function() {
						return $("#tdate_two").val();
					}
				}
			},
			columns: [
				{ data: "DT_RowIndex", name: "DT_RowIndex" },
				{ data: "inv_date", name: "inv_date" },
				{ data: "cgst", name: "cgst" },
				{ data: "grandtotal", name: "grandtotal" },
				{ data: "u_name", name: "u_name" },
				{ data: "u_city", name: "u_city" }
			],
			lengthMenu: window.lengthMenu,
			pageLength: 10,
			dom: 'Blfrtip',
			buttons: window.ex_button
		});

		$(document).on("click", "#filter_btn_date", function(e) {
			ajax_report_gst_datatables.ajax.reload(null, true);
		});
	}




});
