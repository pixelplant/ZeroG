<?php

/**
 * Description of Admin
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Core\Controller
{

	use \Sys\Template\Controller as Controller;
	
	class Admin extends Controller
	{
		public function __construct()
		{	
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
			return \Z::getSingleton('core/session/admin');
		}

		protected function _addBreadcrumb($label, $url = null)
		{
			$this->getLayout()->getBlock('breadcrumbs')->addLink($label, $url);
			return $this;
		}
	}
}

