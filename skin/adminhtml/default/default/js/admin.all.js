(function($)
{
	var methods =
	{
		init : function( options )
		{
		},
		test: function() {}
	};

	$.fn.emptyPlugin = function($method)
	{
		if ( methods[method] )
		{
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		}
		else if ( typeof method === 'object' || ! method )
		{
			return methods.init.apply( this, arguments );
		}
		else
		{
			$.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
		}
	}
})(jQuery);

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
 * Applies theming styles on different tags
 */
function applyJqueryTheme()
{
	// style buttons
	$( "input:submit, a.button, button", ".widget_grid_buttons" ).each( function()
	{
		$(this).button({icons: { primary: $(this).attr('class')}});
	});
}

 $(document).ready(function(){
   initAdminMenu();
   applyJqueryTheme();
 });