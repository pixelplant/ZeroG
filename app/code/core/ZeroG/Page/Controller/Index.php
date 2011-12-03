<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of index
 *
 * @author radu.mogos
 */

namespace App\Code\Core\ZeroG\Page\Controller
{
	use \App\Code\Core\ZeroG\Core\Controller\Front as Controller;
	
	class Index extends Controller
	{
		public function indexAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}
	}
}


