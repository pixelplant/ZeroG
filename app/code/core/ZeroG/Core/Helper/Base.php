<?php

namespace App\Code\Core\ZeroG\Core\Helper
{
	abstract class Base
	{
		protected $_module;

		public function __($text)
		{
			return $this->getModule()->__($text);
		}

		public function getModule()
		{
			if (!$this->_module)
			{
			}
			return $this->_module;
		}
	}
}
