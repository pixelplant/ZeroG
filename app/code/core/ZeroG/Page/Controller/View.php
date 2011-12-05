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

namespace App\Code\Core\ZeroG\Page\Controller
{
	use \App\Code\Core\ZeroG\Core\Controller\Front as Controller;
	
	class View extends Controller
	{
		public function indexAction()
		{
			//$user = \Z::getSingleton('user/session');
			//var_dump($user);die();
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

		public function cmsAction()
		{
			$this->loadLayout();
			//die($this->getRequest('id'));
			$this->renderLayout();
		}
	}
}


