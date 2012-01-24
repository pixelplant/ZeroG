<?php

namespace App\Code\Local\Pixelplant\Blog\Controller\Admin
{
	use \App\Code\Core\ZeroG\Core\Controller\Admin as AdminController;

	class ListController extends AdminController
	{
		protected function _init()
		{
			$this->_addBreadcrumb('Home', 'page/view');
		}

		public function postsAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}

		public function categoriesAction()
		{
			$this->loadLayout();
			//$this->_init();
			//$this->_addBreadcrumb('Blog Categories');
			$this->renderLayout();
		}

		public function posts_gridAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}

		public function categories_gridAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}
	}
}
