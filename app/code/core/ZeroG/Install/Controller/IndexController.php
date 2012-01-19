<?php

/**
 * Description of front
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Install\Controller
{

	use \App\Code\Core\ZeroG\Core\Controller\Front as Controller;

	class IndexController extends Controller
	{
		public function indexAction()
		{
			$this->loadLayout();
			$scripts = \Z::getModel('install/setup')->runSetupScripts()
				->getExecutedFiles();
			$this->getLayout()
				->getBlock('content')
				->setHtml('Running install... Executed '.count($scripts).' install scripts');
			$this->renderLayout();
		}
	}
}

