<?php

/**
 * Website Group model
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Core\Model\Website
{
	class Group extends \Sys\Database\Model
	{
		protected $_eventPrefix = 'website_group';
		
		protected function _construct()
		{
			parent::_construct();
			$this->_init('core/website_group', 'group_id');
		}
	}
}
