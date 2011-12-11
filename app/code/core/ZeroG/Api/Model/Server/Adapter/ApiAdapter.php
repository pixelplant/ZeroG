<?php

namespace App\Code\Core\ZeroG\Api\Model\Server\Adapter
{
	interface ApiAdapter
	{
		function run();

		function fault($errorCode, $message);
	}
}
