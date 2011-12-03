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
		
		public function __construct()
		{
			$this->init('core', 'frontend');
			parent::__construct();
		}

	}
}
