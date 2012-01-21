<?php

namespace App\Code\Local\Pixelplant\Blog\Controller\Admin
{
	use \App\Code\Core\ZeroG\Core\Controller\Admin as AdminController;

	class ListController extends AdminController
	{
		public function indexAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}

		public function gridAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}
	}
}
