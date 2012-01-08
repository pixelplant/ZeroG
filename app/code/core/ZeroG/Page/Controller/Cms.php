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
	
	class Cms extends Controller
	{

		public function viewAction()
		{
			$this->loadLayout();
			$this->renderLayout();

			/*$view = \Z::getModel('core/website/view')->load(0);
			$view->getWebsiteGroup();
			$view->getWebsite();
			var_dump($view);*/
		}

		public function noRouteAction()
		{
			$this->loadLayout();
			$this->getLayout()->getBlock('content')->setTemplate(null);
			$this->getLayout()->getBlock('content')->setHtml('<h1>Page not found</h1>');
			$this->renderLayout();
		}

	}
}
