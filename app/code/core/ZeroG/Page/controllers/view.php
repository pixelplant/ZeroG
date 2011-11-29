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
	}
}


