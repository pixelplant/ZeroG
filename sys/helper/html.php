<?php

/**
 * The HTML Helper class 
 */

namespace Sys\Helper
{
	
	class Html
	{

		/**
		 * Return the anchor tag + url and attributes
		 * @param <string> $path
		 * @param <string> $text
		 * @param <string> $attributes
		 * @return <string>
		 */
		public function directLink($path, $text, $attributes = '')
		{
			$path = htmlspecialchars($path);
			// We generate the url, based on wether we want url rewrites or not
			if (\Z::getConfig('config/global/default/url/rewrite') == 'true')
				return sprintf('<a href="%s" %s>%s</a>', \Z::getConfig('config/global/default/base/url').$path, $attributes, $text);
			else
				return sprintf('<a href="%sindex.php?path=%s" %s>%s</a>', \Z::getConfig('config/global/default/base/url'), $path, $attributes, $text);
		}

		/**
		 * Return only the link's url
		 * @param <string> $path
		 * @return <string>
		 */
		public function url($path)
		{
			$path = htmlspecialchars($path);
			// We generate the url, based on wether we want url rewrites or not
			if (\Z::getConfig('config/global/default/url/rewrite') == 'true')
			{
				return \Z::getConfig('config/global/default/base/url').$path;
			}
			else
				return sprintf('%sindex.php?path=%s', \Z::getConfig('config/global/default/base/url'), $path);
		}

		public function skinUrl($resource)
		{
			$resource = htmlspecialchars($resource);
			$skin = \Z::getConfig('config/global/default/base/url').'skin/frontend/'.\Z::getConfig('config/global/default/package').'/'.\Z::getConfig('config/global/default/skin').'/'.$resource;
			return $skin;
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

			$skin = \Z::getConfig('config/global/default/base/url').'skin/frontend/'.\Z::getConfig('config/global/default/package').'/'.\Z::getConfig('config/global/default/skin').'/';

			if (\Z::getConfig('config/global/default/developer/mode') == 'true')
			{
				$string = '';
				foreach ($jsFiles as $file)
				{
					$string .= sprintf('<script src="%s" type="text/javascript"></script>',
											$skin.$file);
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
						$jsFile = 'skin/frontend/'.\Z::getConfig('config/global/default/package').'/'.\Z::getConfig('config/global/default/skin').'/'.$jsFile;
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
				return sprintf('<script src="%s" type="text/javascript"></script>', \Z::getConfig('config/global/default/base/url').$file);
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

			$skin = \Z::getConfig('config/global/default/base/url').'skin/frontend/'.\Z::getConfig('config/global/default/package').'/'.\Z::getConfig('config/global/default/skin').'/';

			if (\Z::getConfig('config/global/default/developer/mode') == 'true')
			{
				$string = '';
				/*for ($i = 0; $i < $cssFiles; $i++)
				{
					$file = $cssFiles[$i];
					$string .= sprintf('<link rel="stylesheet" type="text/css" href="%s" />', \App\Config\System::BASE_URL.$file);
				}*/
				foreach ($cssFiles as $file)
				{
					$string .= sprintf('<link rel="stylesheet" type="text/css" href="%s" />', $skin.$file);
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
						$cssFile = 'skin/frontend/'.\Z::getConfig('config/global/default/package').'/'.\Z::getConfig('config/global/default/skin').'/'.$cssFile;
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
						$cssMinified = str_replace('url(../', 'url('.\Z::getConfig('config/global/default/base/url').'public/', $cssMinified);
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
				return sprintf('<link rel="stylesheet" type="text/css" href="%s" />', \Z::getConfig('config/global/default/base/url').$file);
			}
		}
	}
}