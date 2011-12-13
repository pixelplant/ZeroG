<?php

namespace App\Code\Core\ZeroG\Core\Model\Resource
{
	/**
	 * Description of Resource
	 *
	 * @author radu.mogos
	 */
	class Extension extends \Sys\Database\Resource
	{
		protected function _construct()
		{
			//parent::_construct();
			$this->_init('core/extension', 'code');
		}
	}
}
