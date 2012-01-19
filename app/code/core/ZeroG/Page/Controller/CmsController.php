<?php
/* 
 * Page_Cms controller
 */

/**
 * Description of Cms Controller
 *
 * @author radu.mogos
 */

namespace App\Code\Core\ZeroG\Page\Controller
{
	use \App\Code\Core\ZeroG\Core\Controller\Front as Controller;
	
	class CmsController extends Controller
	{

		public function viewAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}

	}
}
