<?php

/**
 * The HTML Helper class 
 */

namespace Sys\Helper
{
	class Html
	{
		protected static $javascript = NULL;

		public static function link($path, $text, $attributes = '')
		{
			if (\App\Config\System::URL_REWRITE === TRUE)
				return sprintf('<a href="%s" %s>%s</a>', \App\Config\System::BASE_URL.$path, $attributes, $text);
			else
			{
				$data = explode('/', $path);
				$url = '';
				if (sizeof($data) > 2)
				{
					$index = 0;
					foreach ($data as $field)
					{
						$index++;
						if ($index < 3)
							continue;
						if ($index % 2 != 0)
							$url .= '&'.$field.'=';
						else
							$url .= $field;
					}
				}
				return sprintf('<a href="%sindex.php?controller=%s&action=%s%s" %s>%s</a>', \App\Config\System::BASE_URL, $data[0], $data[1], $url, $attributes, $text);
				//return sprintf('<a href="index.php?controller=%s&action=%s%s" %s>%s</a>', $data[0], $data[1], $url, $attributes, $text);
			}
		}

		public static function ajaxlink($path, $text, $callback = array('success' => '', 'error' => ''))
		{
			$function = self::processAjaxCall($path, $callback);
			return self::link($path, $text, 'onclick="javascript:'.$function.'; return false;"');
			//return sprintf('%s<a href="#" onclick="javascript:%s; return false;">%s</a>', $code, $function, $text);
		}

		private static function processAjaxCall($path, $callback)
		{
			$onError = '';
			$onSuccess = '';
			$path = \App\Config\System::BASE_URL.$path;
			$function = 'f'.md5($path).'()';
			if (isset($callback['success']))
			{
				$success = $callback['success'];
				$onSuccess = <<< ONS
			success: function(data, text, xhr) {
				$success
			},

ONS;
			}
			if (isset($callback['error']))
			{
				// get the error action
				$error = $callback['error'];
				$onError = <<< ONE
			error: function(xhr, text, exception) {
				$error
			},

ONE;
			}
			$code = <<<HER
	//<script type="text/javascript">
	function $function
	{
		$.ajax({
			url: '$path',
$onSuccess
$onError
		});
	}
	//</script>

HER;
			self::$javascript .= $code;
			return $function;
		}

		public static function input($name, $text, $attributes = '')
		{
			return sprintf('<input type="text" name="%s" id="%s" value="%s" %s/>', $name, $name, $text, $attributes);
		}

		/**
		 * Caches all javascript AJAX calls in a cache file, in var/cache
		 * @return <string> the html script insertion code required for the HEAD tag
		 */
		public static function getAjaxCalls()
		{
			// if no javascript code is set on this page, then do not cache any data
			if (self::$javascript === NULL)
				return;
			$file = "var/cache/ajax/ajax_".md5(self::$javascript).".js";
			if (!file_exists($file))
			{
				$handle = fopen($file, "w");
				fwrite($handle, self::$javascript);
				fclose($handle);
			}
			return sprintf('<script type="text/javascript" src="%s"></script>'.chr(10), \App\Config\System::MEDIA_URL.$file);
		}
	}
}