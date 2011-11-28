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
			//$post2 = \Z::getModel('blog/post')->load(3);
			$post2 = \Z::getModel('blog/post')->setTitle('Testare')->save()->load(4)->setText('Amramburica 222')->save();
			$posts = \Z::getModel('blog/post')->find(array('where' => 'id>0 AND id<4'));
			//var_dump($posts);
			//$bgame = \Z::getDatabaseConnection('bgame_setup');
			//$player = $bgame->load('players', 1);
			//var_dump($player);
			//die();
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
