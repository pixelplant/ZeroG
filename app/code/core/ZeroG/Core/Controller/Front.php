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
			
			// then process the template controller
			parent::__construct();
		}

		public function preDispatch()
		{
			$this->getSession();
		}

		/**
		 * Get the singleton session in all extended controllers
		 * @return <type>
		 */
		public function getSession()
		{
			return \Z::getSingleton('core/session/frontend');
		}
	}
}

