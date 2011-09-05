<?php

namespace App\Code\Local\Pixelplant\Blog\Controllers
{
	use \Sys\Template\Controller;

	class Index extends Controller
	{
		public function indexAction()
		{
			$this->loadLayout();
			//$this->getLayout()->getBlock('content')->setCode('gaga');
			//$this->getLayout()->getBlock('head')->setTitle('A test caca page');
			$this->renderLayout();
		}

		public function ajaxAction()
		{
			if ($this->isXHR())
			{
				//echo $this->getLayout()->getBlock('content')->render();
				echo 'hahaha';
			}
			else
			{
				$this->loadLayout();
				$this->getLayout()->getBlock('content')->setCode('YUHUU baby!');
				$this->renderLayout();
			}
		}
	}
}
