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

		public function redirect($path)
		{
			$redirectTo = \Z::getConfig('config/global/default/base/url').$path;
			header('Location: '.$redirectTo);
		}
	}
}
