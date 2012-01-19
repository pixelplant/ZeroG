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

namespace App\Code\Local\Pixelplant\Debug\Controller
{
	use \App\Code\Core\ZeroG\Core\Controller\Front as Controller;

	class ResolveController extends Controller
	{
		public function indexAction()
		{
			$pathToResolve = $this->getRequest()->getParam('debug_caller');
			if ($pathToResolve == '')
			{
				$data['error'] = $this->__('Pixelplant Debug: No POST data specified');
			}
			else
			{
				if (strpos($pathToResolve, '/') !== FALSE)
				{
					$pathToResolve = substr($pathToResolve, 0, strpos($pathToResolve, '/'));
				}
				$moduleName = \Z::getConfig()->getCurrentModule($pathToResolve);
				$module = \Z::getConfig()->getModule($moduleName);
				$data['modelClass'] = $module->getClassName('Model');
				$data['blockClass'] = $module->getClassName('Block');
				$data['controllerClass'] = $module->getClassName('Controller');
			}

			if ($this->isXhr())
			{
				return $data;
			}
			else
			{
				$this->loadLayout();
				$this->getLayout()->getBlock('content')->setHtml(implode('<br/>',$data));
				$this->renderLayout();
			}
		}
	}
}


