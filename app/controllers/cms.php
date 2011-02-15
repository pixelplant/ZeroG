<?php

namespace App\Controllers
{
	class Cms extends \Sys\Template\Controller
	{
		/*public function __construct()
		{
			//parent::__construct('main');
		}*/

		/**
		 * The default action called for this controller. Default settings are
		 * specified in app\config\settings.php
		 */
		public function index()
		{
		}

		public function blog()
		{
			$view = \Sys\ZeroG::getView('blog/single');
			$post = \Sys\ZeroG::getModel('ext/blog/record')->load(\Sys\ZeroG::getParam('id'));
			$view->set('post', $post);
			$this->setTemplate('content', $view->render());
		}

		public function test()
		{
			$this->getLayout()->getBlock('content')->setCode('very FUNNY!');
		}

		public function ajax()
		{
			/*if ($this->isXHR())
				echo 'yea BABY!';
			else
				$this->getLayout()->getBlock('content')->setCode('YUHUU baby!');*/
		}

		public function ajax2()
		{
			if ($this->isXHR())
			{
				echo 'FUCKING AMUSED';
			}
			else
				$this->getLayout()->getBlock('content')->setCode('Are you not amused!');
		}
	}
}