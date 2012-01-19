<?php

/**
 * Config data model
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Core\Model\Config
{
	class Data extends \Sys\Database\Model
	{
		protected $_eventPrefix = 'config_data';

		protected function _construct()
		{
			//parent::_construct();
			$this->_init('core/config_data');
		}
	}
}
