<?php

namespace Sys
{

/**
 * Request class for ZeroG
 *
 * @author radu.mogos
 */
	class Request
	{
		protected $_params;

		protected $_router;

		public function __construct()
		{
			//$this->params = null;
			$this->_router = new \Sys\Config\Router();
			$this->_router->execute();
			$this->_params = $this->_router->getParams('request');
		}
		
		public function getParam($name, $defaultValue = null)
		{
			if (isset($this->_params[$name]))
			{
				return $this->_params[$name];
			}
			else
				return $defaultValue;
		}

		public function getParams()
		{
			return $this->_params;
		}
	}
}
