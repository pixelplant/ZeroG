<?php

/**
 * TODO: Define the SOAP implementation, after the XMLRPC one is completed
 */

namespace App\Code\Core\ZeroG\Api\Model\Server\Adapter
{

	/**
	 * Description of Soap
	 *
	 * @author radu.mogos
	 */
	class Soap extends \Sys\Model implements ApiAdapter
	{
		function run() {}

		function fault($errorCode, $message) {}
	}
}
