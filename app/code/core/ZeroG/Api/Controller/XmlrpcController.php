<?php

namespace App\Code\Core\ZeroG\Api\Controller
{
	/**
	 * Description of Xmlrpc
	 *
	 * @author radu.mogos
	 */
	class XmlrpcController extends \App\Code\Core\ZeroG\Core\Controller\Front
	{
		public function indexAction()
		{
			$this->_getServer()->init('xmlrpc')->run();
			//echo '<xml>test</xml>';
		}

		private function _getServer()
		{
			return \Z::getSingleton('api/server');
		}

		public function clientAction()
		{
			$client   = new \Sys\XmlRpc\Client('http://localhost/zerog/api/xmlrpc');
			$response = $client->call('blog.get', array('post_id' => 3));
			echo $response;
		}
	}
}
