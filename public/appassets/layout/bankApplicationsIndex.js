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
					"X-CSRF-TOKEN": $('meta[name="key-token"]').attr("content")
				},
				data: function (d) {
					d.fdate = $("#fdate_two").val();
					d.tdate = $("#tdate_two").val();
				}
			},
			columns: [
				{ data: "DT_RowIndex", name: "DT_RowIndex" },
				{ data: "customer", name: "customer" },
				{ data: "application", name: "application" },
				{ data: "bank", name: "bank" },
				{ data: "apply_count", name: "apply_count" },
				{ data: "applied", name: "applied" },
				{ data: "action", name: "action" }
			],
			lengthMenu: window.lengthMenu,
			pageLength: 10,
			dom: 'Blfrtip',
			buttons: window.ex_button
		});

		$(document).on("click", "#filter_btn_date", function (e) {
			e.preventDefault();
			ajax_datatables.ajax.reload();
		});
	}

});
