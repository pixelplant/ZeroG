<?php

/**
 * Description of Resource
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Core\Model
{
	class Extension extends \Sys\Database\Model
	{
		protected function _construct()
		{
			parent::_construct();
			$this->_init('core/extension', 'code');
		}
	}
}
