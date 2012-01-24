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

		/**
		 * Returns the desired request parameter
		 *
		 * @param <string> $name Variable name to return
		 * @param <mixed> $defaultValue Default value if variable is empty
		 * @return <mixed>
		 */
		public function getParam($name, $defaultValue = null)
		{
			if (isset($this->_params[$name]) && !empty($this->_params[$name]))
			{
				return $this->_params[$name];
			}
			else
				return $defaultValue;
		}

		/**
		 * Returns the current request parameters
		 *
		 * @return <array>
		 */
		public function getParams()
		{
			return $this->_params;
		}

		/**
		 * Get router instance
		 *
		 * @return <\Sys\Config\Router>
		 */
		public function getRouter()
		{
			return $this->_router;
		}
	}
}
