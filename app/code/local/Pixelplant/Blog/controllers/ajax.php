<?php

namespace App\Code\Local\Pixelplant\Blog\Controllers
{
	use \Sys\Template\Controller;

	class Ajax extends Controller
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
