<?php

/**
 * Description of front
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Core\Controller
{

	use \Sys\Template\Controller as Controller;
	
	class Front extends Controller
	{
		public function __construct()
		{
			// first initialize the session for the frontend
			//\Z::getSingleton('core/session/frontend');
			$this->getSession();
			// then process the template controller
			parent::__construct();
		}

		/**
		 * Get the singleton session in all extended controllers
		 * @return <type>
		 */
		public function getSession()
		{
			return \Z::getSingleton('core/session/frontend');
		}

		public function getRequest($key)
		{
			return \Z::getRequest()->getParam($key);
		}
	}
}

