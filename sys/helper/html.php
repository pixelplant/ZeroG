<?php

/**
 * The HTML Helper class 
 */

namespace Sys\Helper
{
	
	class Html
	{

		public function directLink($path, $text, $attributes = '')
		{
			// We generate the url, based on wether we want url rewrites or not
			if (\Z::getConfig('url/rewrite') === TRUE)
				return sprintf('<a href="%s" %s>%s</a>', \Z::getConfig('base/url').$path, $attributes, $text);
			else
				return sprintf('<a href="%sindex.php?path=%s" %s>%s</a>', \Z::getConfig('base/url'), $path, $attributes, $text);
		}

		/**
		 * Generate an <a href> tag
		 * @param <string> $path The url it links to
		 * @param <string> $text The title shown for the link
		 * @param <string> $attributes Additional attributes for the <a> tag
		 * @return <string> Html conten for the processed <a> tag
		 */
		public function link($path, $text, $attributes = '')
		{
			/**
			 * First we must add the context parameters, which must always be
			 * prepended to the current uri
			 * For example: if the "locale" variable is set to ro_RO, and the
			 * context of ro_RO is set to "ro", then "ro/" will be prepended to
			 * any generated url
			 */
			foreach (\Z::getContextParams() as $from => $to)
			{
				if (array_key_exists(\Z::getParam($from), $to))
					$path = $to[\Z::getParam($from)].'/'.$path;
			}
			return $this->directLink($path, $text, $attributes);
		}

		/**
		 * Simple input tag helper
		*/
		public function input($name, $text, $attributes = '')
		{
			return sprintf('<input type="text" name="%s" id="%s" value="%s" %s/>', $name, $name, $text, $attributes);
		}

		public function addJs($jsFiles = array())
		{
			if (sizeof($jsFiles) == 0)
				return;
			$filename = md5(implode("", $jsFiles));
			$file = "var/cache/js/js_".$filename.".js";

			if (\Z::getConfig('developer/mode') === TRUE)
			{
				$string = '';
				foreach ($jsFiles as $file)
				{
					$string .= sprintf('<script src="%s" type="text/javascript"></script>', \Z::getConfig('base/url').$file);
				}
				return $string;
			}
			else
			{
				if (!file_exists($file))
				{
					$code = '';
					foreach ($jsFiles as $jsFile)
					{
						$codeOriginal = file_get_contents($jsFile);
						// remove ALL whitespaces from string. Not ok since we also remove spaces
						//$cssCode .= preg_replace('/\s+/', '', $cssOriginal);

						// remove all control characters from the string, except for spaces
						// whitespace chars - http://en.wikipedia.org/wiki/Whitespace_character#Programming_Languages
						// solution - http://stackoverflow.com/questions/1497885/remove-control-characters-from-php-string
						//$cssMinified = preg_replace('/[[:cntrl:]]/', '', $cssOriginal);
						//$codeMinified = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $codeOriginal);

						// the last step would be to remove the comments from the css files
						// if any are present
						//$codeMinified = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $codeOriginal);

						//$code .= $codeMinified;
						$code .= $codeOriginal;
					}
					file_put_contents($file, $code);
				}
				return sprintf('<script src="%s" type="text/javascript"></script>', \Z::getConfig('base/url').$file);
			}
		}

		/**
		 * Adds the css files used by the site. If developer mode is off,
		 * it concatenates all the css files in 1 big css file and then minifies
		 * the content of this css
		 *
		 * @param <array> The list of css files to add
		 */
		public function addCss($cssFiles = array())
		{
			if (sizeof($cssFiles) == 0)
				return;
			$filename = md5(implode("", $cssFiles));
			$file = "var/cache/css/css_".$filename.".css";
			if (\Z::getConfig('developer/mode') === TRUE)
			{
				$string = '';
				/*for ($i = 0; $i < $cssFiles; $i++)
				{
					$file = $cssFiles[$i];
					$string .= sprintf('<link rel="stylesheet" type="text/css" href="%s" />', \App\Config\System::BASE_URL.$file);
				}*/
				foreach ($cssFiles as $file)
				{
					$string .= sprintf('<link rel="stylesheet" type="text/css" href="%s" />', \Z::getConfig('base/url').$file);
				}
				return $string;
			}
			else
			{
				if (!file_exists($file))
				{
					$cssCode = '';
					foreach ($cssFiles as $cssFile)
					{
						$cssOriginal = file_get_contents($cssFile);
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
						$cssMinified = str_replace('url(../', 'url('.\Z::getConfig('base/url').'public/', $cssMinified);
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
				return sprintf('<link rel="stylesheet" type="text/css" href="%s" />', \Z::getConfig('base/url').$file);
			}
		}
	}
}