document.addEventListener("DOMContentLoaded", function () {

	if (1 == jQuery("#ajax_datatables").length) {

		var baseUrl = $("#ajax_datatables").attr("data-url");

		var ajax_datatables = $("#ajax_datatables").DataTable({
			stateSave: true,
			stateDuration: -1,
			processing: true,
			serverSide: true,
			scrollX: true,
			ajax: {
				type: "POST",
				url: baseUrl,
				headers: {
					"X-CSRF-TOKEN": $('meta[name="key-token"]').attr("content")
				}
			},
			columns: [
				{ data: "DT_RowIndex", name: "DT_RowIndex" },
				{ data: "logo_img", name: "logo_img" },
				{ data: "bank", name: "bank" },
				{ data: "links", name: "links" },
				{ data: "status_badge", name: "status_badge" },
				{ data: "action", name: "action" }
			],
			lengthMenu: window.lengthMenu,
			pageLength: 10,
			dom: 'Blfrtip',
			buttons: window.ex_button
		});

		function resetBankForm() {
			var form = document.getElementById("addBankModule");
			if (form) {
				form.reset();
			}
			$("#bank_id").val("");
			$("#logo_preview").attr("src", "");
			$("#logo_preview_wrap").hide();
			$("#addNewPopupModalLabel span").last().text("Add New Bank");
		}

		$(document).on("click", "#addNewBankBtn", function () {
			resetBankForm();
		});

		$(document).on("click", ".editBankBtn", function () {
			var id = $(this).data("id");
			if (!id) {
				return;
			}
			loadLoader("on");
			$.ajax({
				type: "GET",
				url: baseUrl + "/" + id,
				dataType: "json",
				success: function (xhr) {
					loadLoader();
					var d = xhr.data || {};
					$("#bank_id").val(d.id || "");
					$("#label").val(d.label || "");
					$("#pl_link").val(d.pl_link || "");
					$("#bl_link").val(d.bl_link || "");
					$("#sort_order").val(d.sort_order != null ? d.sort_order : 0);
					$("#status").val(String(d.status));
					$("#logo").val("");
					if (d.logo_url) {
						$("#logo_preview").attr("src", d.logo_url);
						$("#logo_preview_wrap").show();
					} else {
						$("#logo_preview_wrap").hide();
					}
					$("#addNewPopupModalLabel span").last().text("Edit Bank");
					bootstrap.Offcanvas.getOrCreateInstance(document.getElementById("addNewPopupModal")).show();
				},
				error: function (xhr) {
					loadLoader();
					ajaxResponseFailure(xhr);
				}
			});
		});

		$("#addBankModule").validate({
			rules: {
				label: { required: true },
				pl_link: { required: true },
				bl_link: { required: true },
				sort_order: { required: true }
			},
			messages: {},
			submitHandler: function (form) {
				return true;
			}
		});

		$(document).on("submit", "#addBankModule", function (e) {
			e.preventDefault();
			if ($("#addBankModule").valid() === true && $(this).attr("action")) {
				loadLoader("on");
				$.ajax({
					type: "POST",
					url: $(this).attr("action"),
					data: new FormData(this),
					dataType: "json",
					contentType: false,
					cache: false,
					processData: false,
					success: function (xhr) {
						if (xhr.message) {
							Notify(xhr.message, "success");
							loadLoader();
						}
						ajax_datatables.ajax.reload(null, false);
						setTimeout(function () {
							$("button.btn-close").click();
						}, 100);
					},
					error: function (xhr) {
						ajaxResponseFailure(xhr);
					}
				});
			}
		});
	}

});
