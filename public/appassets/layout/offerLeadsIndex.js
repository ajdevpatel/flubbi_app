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
				{ data: "created_at", name: "created_at" },
				{ data: "gateway_transaction_id", name: "gateway_transaction_id" },
				{ data: "phone", name: "phone" },
				{ data: "type", name: "type" },
				{ data: "action", name: "action" }
			],
			lengthMenu: window.lengthMenu,
			pageLength: 10,
			dom: 'Blfrtip',
			buttons: window.ex_button
		});

		$(document).on("click", "#filter_btn_date", function(e) {
			ajax_datatables.ajax.reload(null, true);
		});
	}

});
