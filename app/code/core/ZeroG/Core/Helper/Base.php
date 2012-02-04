<?php

namespace App\Code\Core\ZeroG\Core\Helper
{
	abstract class Base
	{
		protected $_module;

		public function __construct($module)
		{
			$this->_module = $module;
		}

		public function __($text, $arguments = array())
		{
			return \Z::getLocale()->__($text, $this->getModule()->getName(), $arguments);
		}

		public function getModule()
		{
			return $this->_module;
		}
	}
}
