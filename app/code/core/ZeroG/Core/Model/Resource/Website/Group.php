<?php

/**
 * Website view resource
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Core\Model\Resource\Website
{
	class Group extends \Sys\Database\Resource
	{
		protected function _construct()
		{
			$this->_init('core/website_group', 'group_id');
		}
	}
}
