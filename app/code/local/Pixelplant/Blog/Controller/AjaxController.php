<?php

namespace App\Code\Local\Pixelplant\Blog\Controller
{
	use \App\Code\Core\ZeroG\Core\Controller\Front as Controller;

	class AjaxController extends Controller
	{
		public function indexAction()
		{
			if ($this->isXHR())
			{
				//echo $this->getLayout()->getBlock('content')->render();
				echo 'hahaha';
			}
			else
			{
				$this->loadLayout();
				//$this->getLayout()->getBlock('content')->setCode('gaga');
				//$this->getLayout()->getBlock('head')->setTitle('A test caca page');
				$this->renderLayout();
			}
		}
	}
}
