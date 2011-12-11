<?php

namespace Sys\XmlRpc
{

	/**
	 * Description of Server
	 *
	 * @author radu.mogos
	 */
	class Server
	{
		/**
		 * List of server XMLRPC registered methods
		 * 
		 * @var <array>
		 */
		protected $_registeredMethods;

		/**
		 * PHP to XMLRPC type mapping
		 *
		 * @var <array>
		 */
		protected $_typeMap = array(
			'i4'               => 'i4',
			'int'              => 'int',
			'integer'          => 'int',
			'double'           => 'double',
			'float'            => 'double',
			'real'             => 'double',
			'boolean'          => 'boolean',
			'bool'             => 'boolean',
			'true'             => 'boolean',
			'false'            => 'boolean',
			'string'           => 'string',
			'str'              => 'string',
			'base64'           => 'base64',
			'dateTime.iso8601' => 'dateTime.iso8601',
			'date'             => 'dateTime.iso8601',
			'time'             => 'dateTime.iso8601',
			'time'             => 'dateTime.iso8601',
			'array'            => 'array',
			'struct'           => 'struct',
			'null'             => 'nil',
			'nil'              => 'nil',
			'void'             => 'void',
			'mixed'            => 'struct'
		);

		public function __construct()
		{
			$this->_registeredMethods = array();
		}

		public function registerMethod($method, $caller)
		{
			if (!isset($this->_registeredMethods[$method]))
			{
				$this->_registeredMethods[$method] = $caller;
			}
		}

		public function hasRegisteredMethod($method)
		{
			return (isset($this->_registeredMethods[$method]));
		}

		public function getMethodData($method)
		{
			return $this->_registeredMethods[$method];
		}
	}
}
