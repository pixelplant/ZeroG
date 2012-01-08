<?php

/**
 * Frontend session
 *
 * @author radu.mogos
 */

namespace App\Code\Core\ZeroG\Core\Model\Session
{	
	class Frontend extends \Sys\Session
	{
		
		protected function _construct()
		{
			$this->init('core', 'frontend');
		}

	}
}
