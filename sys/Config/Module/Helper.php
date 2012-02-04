<?php

namespace Sys\Config\Module
{
	class Helper
	{
		protected $_module;

		protected $_class;

		public function __construct($module)
		{
			$this->_module = $module;
			$this->_class = $this->_module->getClassName('Helper');
		}

		public function getModule()
		{
			return $this->_module;
		}

		public function getClass()
		{
			return $this->_class;
		}
	}
}