<?php

namespace App\Controllers
{
	class Cms extends \Sys\Template\Controller
	{
		public function __construct()
		{
			parent::__construct('main');
		}

		/**
		 * The default action called for this controller. Default settings are
		 * specified in app\config\settings.php
		 */
		public function index()
		{
			//$test = \Sys\ZeroG::getModel('profiles/user');
			//$test->setUsername('Gringo deluxe');
			//$test->setPassword('123456');
			$view = \Sys\ZeroG::getView('register/new_user');
			//$view->set('username', $test->getUsername());
			//$view->set('password', $test->getPassword());
			//$view2 = \Sys\ZeroG::getView('register/user_details');
			//$view->set('block', $view2->render());
			$this->setTemplate('content',$view->render());
			//echo (xdebug_memory_usage()/1024).'|';
			//echo xdebug_time_index();
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
			$view = \Sys\ZeroG::getView('register/user_details');
			$this->setTemplate('content', $view->render());
		}

		public function ajax()
		{
			if ($this->isXHR())
				echo 'yea BABY!';
			else
				$this->setTemplate('content', 'YUHUU baby!');
		}

		public function ajax2()
		{
			if ($this->isXHR())
			{
				echo 'FUCKING AMUSED';
			}
			else
				$this->setTemplate('content', 'ARE YOU NOT FUCKING AMUSED?');
		}
	}
}