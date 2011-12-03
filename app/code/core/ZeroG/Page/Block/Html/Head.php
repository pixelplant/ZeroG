<?php

/**
 * All of these actions should be normally called only on the "head" block
 * 
 * Actions callable in xml files
 * setTitle($text) - sets current page title
 * getTitle() - gets current page title
 * addJs($file) - adds a js script to the current page
 * addCss($file) - adds a css script to the current page
 * removeJs($file) - remove js file
 * removeCss($file) - remove css file
 *
 * Example:
 * <reference name="head">
 *   <action method="setTitle"><title>My page title</title></action>
 * </reference>
 */

namespace App\Code\Core\ZeroG\Page\Block\Html
{
	/**
	 * Block used by the Page head (located in the <head> tag)
	 * Sets/gets the CSS code, JS code and the title tag
	 *
	 * @author Radu Mogos <radu.mogos@pixelplant.ro>
	 * @package App
	 */
	class Head extends \Sys\Layout\Block
	{
		/**
		 * Holds the list of JS files used by the current page
		 *
		 * @var <array>
		 */
		protected $jsFiles = array();

		/**
		 * Holds the list of CSS files used by the curent page
		 *
		 * @var <array>
		 */
		protected $cssFiles = array();

		/**
		 * Holds the current page title, used for the <title> tag
		 *
		 * @var <string>
		 */
		protected $title = '';

		/**
		 * Add a js file to the head tag
		 * @param <string> $js The path and filename of the js script to include
		 */
		public function addJs($js)
		{
			$this->jsFiles[$js] = $js;
		}

		/**
		 * Add a css file to the head tag
		 * @param <string> $css The path and filename of the css script to include
		 */
		public function addCss($css)
		{
			$this->cssFiles[$css] = $css;
		}

		/**
		 * Remove a js file from the head tag
		 * @param <type> $js The path and filename of the js file to remove
		 */
		public function removeJs($js)
		{
			unset($this->jsFiles[$js]);
		}

		/**
		 * Remove a css file from the head tag
		 * @param <type> $css The path and filename of the css file to remove
		 */
		public function removeCss($css)
		{
			unset($this->cssFiles[$css]);
		}

		/**
		 * Return all the js files used by this page
		 *
		 * @return <string>
		 */
		public function getJs()
		{
			/*$code = '';
			foreach ($this->jsFiles as $js)
			{
				$code .= sprintf('<script type="text/javascript" src="%s"></script>', \App\Config\System::BASE_URL.$js);
			}
			return $code;*/
			return \Z::getHelper('Sys\Helper\Html')->addJs($this->jsFiles);
		}

		/**
		 * Return all the css files used by this page
		 *
		 * @return <string>
		 */
		public function getCss()
		{
			/*$code = '';
			foreach ($this->cssFiles as $css)
			{
				$code .= sprintf('<link rel="stylesheet" type="text/css" href="%s" />', \App\Config\System::BASE_URL.$css);
			}
			return $code;*/
			return \Z::getHelper('Sys\Helper\Html')->addCss($this->cssFiles);
		}

		public function setTitle($value)
		{
			$this->title = htmlspecialchars($value);
		}

		public function getTitle()
		{
			return $this->title;
		}
	}
}