<?php

namespace App\Models\Blocks\Page\Html
{
	class Head extends \Sys\Layout\Block
	{
		protected $jsFiles = array();

		protected $cssFiles = array();

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

		public function getJsCode()
		{
			$code = '';
			foreach ($this->jsFiles as $js)
			{
				$code .= sprintf('<script type="text/javascript" src="%s"></script>', \App\Config\System::BASE_URL.$js);
			}
			return $code;
		}

		public function getCssCode()
		{
			$code = '';
			foreach ($this->cssFiles as $css)
			{
				$code .= sprintf('<link rel="stylesheet" type="text/css" href="%s" />', \App\Config\System::BASE_URL.$css);
			}
			return $code;
		}
	}
}