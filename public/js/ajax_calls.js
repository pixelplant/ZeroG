$(document).ready(function() {
	var ajax_load = "<img src='public/images/ajax-loader.gif' />";
	$('#ajax1').click(function(event) {
		$('#test').html(ajax_load).load('http://local/zerog/cms/ajax');
		event.preventDefault();
		/*$.ajax({
			url: 'http://local/zerog/cms/ajax',
			success: function(data, text, xhr) {
				$('#test').fadeOut().html(data).fadeIn();
			}
		});*/
	});
});