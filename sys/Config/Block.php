<?php

namespace Sys\Config
{
	/**
	 * Description of Block
	 *
	 * @author radu.mogos
	 */
	class Block
	{
		protected $_identifier;

		protected $_module;

		protected $_class;

		public function __construct($identifier, $module, $class)
		{
			$this->_identifier = $identifier;
			$this->_module = $module;
			$this->_class = $class;
		}

		public function getModule()
		{
			return $this->_module;
		}

		public function getIdentifier()
		{
			return $this->_idenfitier;
		}

		public function getClass($appendClass = '')
		{
			return $this->_class.$appendClass;
		}
	}
}
