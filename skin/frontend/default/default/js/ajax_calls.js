$(document).ready(function()
{
	var ajax_load = "<img src='public/images/ajax-loader.gif' />";
	$('#ajax1').click(function(event)
	{
		$('#test').html(ajax_load).load('http://local/zerog/index.php?path=blog/ajax/index');
		event.preventDefault();
	});
});