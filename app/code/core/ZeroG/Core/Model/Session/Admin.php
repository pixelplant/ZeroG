<?php

/**
 * Backend Session
 *
 * @author radu.mogos
 */

namespace App\Code\Core\ZeroG\Core\Model\Session
{	
	class Admin extends \App\Code\Core\ZeroG\Core\Model\Session
	{
		
		protected function _construct()
		{
			$this->init('core', 'admin');
		}

	}
}
