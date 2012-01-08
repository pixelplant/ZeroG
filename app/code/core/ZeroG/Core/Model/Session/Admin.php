<?php

/**
 * Backend Session
 *
 * @author radu.mogos
 */

namespace App\Code\Core\ZeroG\Core\Model\Session
{	
	class Admin extends \Sys\Session
	{
		
		protected function _construct()
		{
			$this->init('core', 'admin');
		}

	}
}
