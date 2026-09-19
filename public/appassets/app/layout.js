

/************************ Loader On/Off **************************/
function loadLoader(action = "off") {
	const $loader = $('.loader-main-inner');
	if ($loader.length) {
		$loader[action === "on" ? "fadeIn" : "fadeOut"]('slow');
	}
	return true;
}
var loaderOn = document.querySelector(".loaderOn");
if (loaderOn !== null && typeof loaderOn !== 'undefined') {
	loaderOn.addEventListener("click", function() {
		loadLoader("on");
	});
}
document.addEventListener("DOMContentLoaded", () => loadLoader());
/********************** End Loader On/Off ************************/


/************************ Redirect Url **************************/
function redirectUrl(url = "", timeOut = 1400) {
	loadLoader();
	if(url != "") {
		setTimeout(function () {
			window.location.href = url;		
		}, timeOut);
	}
}
/********************** End Redirect Url ************************/


/************************** Key/Token ***************************/
$(document).ready(function() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="key-token"]').attr('content')
		}
	});
});
/*********************** End Key/Token **************************/


/************************* Notification **************************/
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
		showEasing: 'swing',
		hideEasing: 'linear',
		showMethod: 'fadeIn',
		hideMethod: 'fadeOut',
		newestOnTop: true,
		progressBar: true,
		debug: false,
		preventDuplicates: false,
		onclick: null,
		rtl: 'ltr'
	};

	//Type :- success,info,warning,error	
	//Using toastr[type] directly
	var $toast = toastr[type](message, "");

	// Returning false if $toast is undefined - false
	return $toast !== undefined;
}
/*********************** End Notification ************************/


/************************ AjaxCall Error *************************/
function ajaxResponseFailure(xhr) {
	if (xhr.status === 422) {
		var x_msg = '';
		$.each(xhr.responseJSON.errors, function(key, value) {
			x_msg += value.join(', ')+"<br>";
		});
		Notify(x_msg);
	}
	else {
		Notify("Try this action again after refreshing your credentials and page");
	}
	loadLoader();
}
/*********************** End AjaxCall Error **********************/


/*********************** Image URL Read ************************/
function onLoadImg(input, showId) {
	if (input.files && input.files[0]) {
		if(0 != showId.length) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$(showId).attr('src', e.target.result);
				//.width(150)
				//.height(200);
			};
			reader.readAsDataURL(input.files[0]);
		}
	}
}
/********************* End Image URL Read **********************/
