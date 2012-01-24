/**
 * Called before an ajax request is executed
 **/
function showAjaxRequest(formData, jqForm, options)
{
	//$('#ajax_worker').show();
}

function hideAjaxRequest(responseText, statusText, xhr, $form)
{
	//$('#ajax_worker').hide();
	//$form.trigger('create');
}

/**
 * Initializes the backend popup menu
 */
function initAdminMenu()
{
	//$("#menu li ul").hide();

	$("li.has-menu").hover(
		function ()
		{
			$(this).children("a").addClass("active");
			$(this).children(".menu").show();
        },
		function ()
		{
			$(this).children("a").removeClass("active");
			$(this).children(".menu").hide();
		}
	);
}

/**
 * Called when someone clicks on a statusbar item that has a popup
 */
function statusBarPopup(targetUrl, target)
{
	if (targetUrl == '')
		return;
	var popupBar  = $('#status-bar-popup-'+target);
	var barButton = $('#status-bar-'+target);
	if (popupBar.is(":visible"))
	{
		popupBar.hide();
		barButton.removeClass('selected');
		return;
	}
	//if (targetElement.visible())
	//	return;
	$.ajax({
		url: targetUrl,
		success: function(data)
		{
			barButton.addClass('selected');
			popupBar.show();
			popupBar.html(data);
		},
		statusCode: {
			404: function() {
				alert('There was an error processing the Ajax data for the statusbar popup');
			}
		}
	});
}

/**
 * Initialization...
 */
 $(document).ready(function(){

 });