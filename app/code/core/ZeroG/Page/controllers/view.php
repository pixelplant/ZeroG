<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of view
 *
 * @author radu.mogos
 */

namespace App\Code\Core\ZeroG\Page\Controllers
{
	use \Sys\Template\Controller;
	
	class View extends Controller
	{
		public function indexAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}

		public function extensionsAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}

		public function documentationAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}

		public function downloadAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}
	}
}


