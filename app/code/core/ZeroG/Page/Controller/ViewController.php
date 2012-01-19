<?php
/* 
 * Page_View controller
 */

/**
 * Description of view
 *
 * @author radu.mogos
 */

namespace App\Code\Core\ZeroG\Page\Controller
{
	use \App\Code\Core\ZeroG\Core\Controller\Front as Controller;
	
	class ViewController extends Controller
	{
		public function indexAction()
		{
			//$user = \Z::getSingleton('user/session');
			//var_dump($user);die();
			$this->loadLayout();
			$this->renderLayout();
		}

		public function cmsAction()
		{
			$view = \Z::getModel('core/website/view')->load(0);
			$view->getWebsiteGroup();
			$view->getWebsite();
			var_dump($view);
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
