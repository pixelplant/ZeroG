<?php

/**
 * Description of Website
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Core\Model
{
	class Website extends \Sys\Database\Model
	{
		protected function _construct()
		{
			parent::_construct();
			$this->_init('core/website', 'website_id');
		}
	}
}
