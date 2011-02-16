<?php

/**
 * The HTML Helper class 
 */

namespace Sys\Helper
{
	class Html
	{
		protected static $javascript = NULL;

		/**
		 * Generate an <a href> tag
		 * @param <string> $path The url it links to
		 * @param <string> $text The title shown for the link
		 * @param <string> $attributes Additional attributes for the <a> tag
		 * @return <string> Html conten for the processed <a> tag
		 */
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
			return sprintf('<script type="text/javascript" src="%s"></script>'.chr(10), \App\Config\System::BASE_URL.$file);
		}

		public static function addCss()
		{
			$filename = md5(implode("", func_get_args()));
			$file = "var/cache/css/css_".$filename.".css";
			if (\App\Config\System::DEVELOPER_MODE === TRUE)
			{
				$string = '';
				for ($i = 0; $i < func_num_args(); $i++)
				{
					$file = func_get_arg($i);
					$string .= sprintf('<link rel="stylesheet" type="text/css" href="%s" />', \App\Config\System::BASE_URL.$file);
				}
				return $string;
			}
			else
			{
				if (!file_exists($file))
				{
					$cssCode = '';
					for ($i = 0; $i < func_num_args(); $i++)
					{
						$cssOriginal = file_get_contents(func_get_arg($i));
						// remove ALL whitespaces from string. Not ok since we also remove spaces
						//$cssCode .= preg_replace('/\s+/', '', $cssOriginal);
					
						// remove all control characters from the string, except for spaces
						// whitespace chars - http://en.wikipedia.org/wiki/Whitespace_character#Programming_Languages
						// solution - http://stackoverflow.com/questions/1497885/remove-control-characters-from-php-string
						//$cssMinified = preg_replace('/[[:cntrl:]]/', '', $cssOriginal);
						$cssMinified = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $cssOriginal);
						// what we can do though after this is to remove the spaces that come after a comma
						// this would be valid and our css would work just fine...
						// then remove all spaces before the ":" character
						// for simple characters replacement, using non regex expressions seems to be faster
						// http://stackoverflow.com/questions/4471470/how-to-use-preg-replace-to-replace-comma-and-period-in-a-string
						$cssMinified = str_replace(array(', ', ': '), array(',',':') , $cssMinified);
						// the last step would be to remove the comments from the css files
						// if any are present
						$cssMinified = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $cssMinified);

						// now, what we also need to do is find all the url() background attributes in the
						// css and replace their relative/absolute path with our cached absolute path
						$cssMinified = str_replace('url(../', 'url('.\App\Config\System::BASE_URL.'public/', $cssMinified);
						/*$urlMatches = preg_match_all('/url\([^)]+\)/', $cssMinified, $matches);
						if ($urlMatches > 0)
						{
							$urlsFrom = array();
							$urlsTo = array();
							foreach ($matches[0] as $match)
							{
								$urlsFrom[] = $match;
								$urlsTo = \App\Config\System::MEDIA_URL.
							}
						}*/

						$cssCode .= $cssMinified;
					}
					file_put_contents($file, $cssCode);
				}
				return sprintf('<link rel="stylesheet" type="text/css" href="%s" />', \App\Config\System::BASE_URL.$file);
			}
		}
	}
}