<?php

namespace App\Code\Core\ZeroG\Api\Model\Server\Adapter
{

	/**
	 * Description of XmlRpc
	 *
	 * @author radu.mogos
	 */
	class Xmlrpc extends \Sys\Model implements ApiAdapter
	{
		private $_serverHandler;

		private $_xml;

		protected function _construct()
		{
			// Read POST data
			$this->_xml = file_get_contents('php://input');
			if (!$this->_xml)
				throw new \Sys\Exception('The XMLRPC stream is missing');
			$this->_serverHandler = new \Sys\XmlRpc\Server();
		}

		public function run()
		{
			//$this->_serverHandler->registerMethod('test.testFunction',
			//	array('model' => 'api/test', 'method' => 'testFunction'));
			$responseData = $this->_executeXml();
			if ($responseData != NULL)
			{
				$xmlResponse = $this->_writeXmlResponse($responseData);
				header('Content-Type: text/xml; charset=utf-8\r\n');
				header('Content-Length: '.strlen($xmlResponse).'\r\n');
				echo $xmlResponse;
			}
		}

		public function fault($errorCode, $message)
		{
		}

		protected function _executeXml()
		{
			try
			{
				$data = new \SimpleXMLElement($this->_xml);
			}
			catch (\Exception $e)
			{
				throw new \Sys\Exception('The XML parsed to the XMLRPC request is not valid');
			}
			$method = (string)$data->methodName;
			/*if ($this->_serverHandler->hasRegisteredMethod($method))
			{
				$methodData = $this->_serverHandler->getMethodData($method);
				$actualMethod = $methodData['method'];
				return \Z::getModel($methodData['model'])->$actualMethod();
			}*/
			// A method call looks like this: 'blog/post.get' to call the model
			// Blog/Post/Api and the model method called 'get()'
			$parts = explode('.', $method);
			if (sizeof($parts) != 2)
				throw new \Sys\Exception('The XMLRPC call must define the model and the method in the form ZeroGModelName.Method (eg: blog/post.get)');
			$class = \Z::getModel($parts[0].'/api');
			$classMethod = $parts[1];
			if (is_callable(array($class, $classMethod)))
			{
				return $class->$classMethod();
			}
			return NULL;
		}

		protected function _writeXmlResponse($data)
		{
			$xml = '<?xml version="1.0" encoding="UTF-8"?><methodResponse><params>';
			$xml.= $this->_generateParameters($data);
			$xml.= '</params></methodResponse>';
			return $xml;
		}

		protected function _generateParameters($data)
		{
			$params = '';
			if ($data)
				foreach ($data as $param)
				{
					$paramData = htmlspecialchars($param);
					$params .= '<param><value><string>'.$paramData.'</string></value></param>';
				}
			return $params;
		}
	}
}
