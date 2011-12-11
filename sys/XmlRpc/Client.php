<?php

namespace Sys\XmlRpc
{

	/**
	 * Simple XmlRpc client implementation
	 *
	 * @author radu.mogos
	 */

	class Client
	{
		protected $_server;

		protected $_currentMethod;

		protected $_currentParams;

		protected $_context;

		public function __construct($server)
		{
			$this->_server = $server;
		}

		public function call($method, $params)
		{
			$this->_currentParams = $params;
			$this->_currentMethod = $method;
			$xmlRequest = $this->_generateRequest();
			$this->_context = stream_context_create(
				array(
					'http' => array(
						'method'  => "POST",
						'header'  => "Content-Type: text/xml; charset=utf-8\r\n",
						'content' => $xmlRequest,
						'timeout' => 15
					)
				)
			);
			$xmlResponse = file_get_contents($this->_server, false, $this->_context);
			return $xmlResponse;
		}

		protected function _generateRequest()
		{
			$method = htmlspecialchars($this->_currentMethod);
			$xml = '<?xml version="1.0" encoding="UTF-8"?><methodCall><methodName>'.$method.'</methodName><params>';
			$xml.= $this->_generateParameters();
			$xml.= '</params></methodCall>';
			return $xml;
		}

		protected function _generateParameters()
		{
			$params = '';
			if ($this->_currentParams)
				foreach ($this->_currentParams as $param)
				{
					$paramData = htmlspecialchars($param);
					$params .= '<param><value><string>'.$paramData.'</string></value></param>';
				}
			return $params;
		}
	}
}
