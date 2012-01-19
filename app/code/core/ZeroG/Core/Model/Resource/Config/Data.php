<?php

/**
 * Website view resource
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Core\Model\Resource\Config
{
	class Data extends \Sys\Database\Resource
	{
		protected function _construct()
		{
			$this->_init('core/config_data', 'config_id');
		}
	}
}
