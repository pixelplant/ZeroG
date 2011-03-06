<?php

namespace App\Controllers
{
	class Cms extends \Sys\Template\Controller
	{

		/**
		 * The default action called for this controller. Default settings are
		 * specified in app\config\settings.php
		 */
		public function index()
		{
			/*$user = \Z::getModel('profiles/user');
			$user->getUsername();
			$user->setUsername('test');
			$female = \Z::getModel('profiles/user');
			$female->setUsername('femeie');
			var_dump($user);
			var_dump($female);*/
			$this->loadLayout();
			$this->renderLayout();
		}

		public function test()
		{
			$this->getLayout()->getBlock('content')->setCode('very FUNNY!');
		}

		public function ajax()
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

		public function ajax2()
		{
			if ($this->isXHR())
			{
				echo 'FUCKING AMUSED';
			}
			else
			{
				//$this->getLayout()->removeBlock('content');
				$this->loadLayout();
				$this->getLayout()->getBlock('content')->setCode('Are you not amused!');
				$this->renderLayout();
			}
		}
	}
}