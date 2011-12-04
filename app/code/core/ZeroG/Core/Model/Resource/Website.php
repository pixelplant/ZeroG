<?php

/**
 * Description of Resource
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Core\Model\Resource
{
	class Website extends \Sys\Database\Resource
	{
		protected function _construct()
		{
			$this->_init('core/website', 'website_id');
		}
	}
}
