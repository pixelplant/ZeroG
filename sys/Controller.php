<?php
/**
 * The base controller class for zerog
 * User: radu.mogos
 * Date: 23.10.2010
 * Time: 22:16:30
 */

namespace Sys
{
	class Controller
	{
		public function __construct() {}

		/**
		 * Redirect to another page 
		 * @param <string> $path router/controller/action
		 */
		public function redirect($path, $code = 302)
		{
			header('Location: '.$this->getUrl($path), TRUE, $code);
		}

		/**
		 * Return the absolute url + the path appended to it
		 * @param <string> $path
		 * @return <string>
		 */
		public function getUrl($path)
		{
			return \Z::getHelper('Sys\Helper\Html')->url($path);
		}
	}
}
