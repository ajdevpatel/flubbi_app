Notify = function(text, style, callback=null, close_callback=null) {
	var time = '7000';
	var $container = $('#mydev_notify');
    var icon = '<i class="fa fa-info-circle "></i>';
    
	if (typeof style == 'undefined' ) style = 'warning'
	
	var close = '<a href="javascript:void(0);">×</a>';
    if(style == "info"){
        var icon = '<i class="fadeIn animated bx bx-info-circle"></i>';
		//var html = $('<div class="alert alert-info hide">' + icon +  " " + text + '</div>');
    }else if(style == "warning"){
		var icon = '<i class="fadeIn animated bx bx-alarm-exclamation"></i>';
       //var html = $('<div class="alert alert-warning hide">' + icon +  " " + text + '</div>');
    }else if(style == "success"){
        var icon = '<i class="fadeIn animated bx bx-check-circle"></i>';
		//var html = $('<div class="alert alert-success hide">' + icon +  " " + text + '</div>');
    }else if(style == "error"){
		var icon = '<i class="fadeIn animated bx bx-error"></i>';
        //var html = $('<div class="alert alert-danger hide">' + icon +  " " + text + '</div>');
    }
	var html = $('<div class="mydev_alert mydev_'+style+'"><div class="mydev_icon">'+ icon +'</div><div class="mydev_text">'+ text +'</div><div class="mydev_close">'+close+'</div></div>');
  
	/*$('<a>',{
		text: '×',
		class: 'button close',
		style: 'padding-left: 10px;',
		href: '#',
		click: function(e){
			e.preventDefault()
			close_callback && close_callback()
			remove_notice()
		}
	}).prependTo(html)*/

	$container.prepend(html)
	html.removeClass('hide').hide().fadeIn('slow')

	function remove_notice() {
		html.stop().fadeOut('slow').remove()
	}
	
	var timer =  setInterval(remove_notice, time);

	$(html).hover(function(){
		clearInterval(timer);
	}, function(){
		timer = setInterval(remove_notice, time);
	});
	
	html.on('click', function () {
		clearInterval(timer);
		callback && callback();
		remove_notice();
	});

	$('#mydev_notify .mydev_alert .mydev_close a').on('click', function () {
		$(this).stop().parent('.mydev_close').parent('.mydev_alert').fadeOut('slow').remove();
	});
}
$(document).ready(function(){
	$( "body" ).append( '<div id="mydev_notify"></div>' );
	/*Notify("Success! Hammer time", 'success');
	Notify("Warning! Hammer time", 'warning');
	Notify("Info! Hammer time", 'info');
	Notify("Error! Hammer time", 'error');*/
});