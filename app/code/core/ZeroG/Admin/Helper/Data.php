<?php

namespace App\Code\Core\ZeroG\Admin\Helper
{
	class Data extends \App\Code\Core\ZeroG\Core\Helper\Base
	{
		public function prepareFilterString($filterString)
		{
			$data = array();
			$filterString = base64_decode($filterString);
			parse_str($filterString, $data);
			array_walk_recursive($data, array($this, 'decodeFilter'));
			return $data;
		}

		public function decodeFilter(&$value)
		{
			$value = rawurldecode($value);
		}
	}
}
