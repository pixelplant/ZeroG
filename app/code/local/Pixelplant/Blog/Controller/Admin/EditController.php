<?php

namespace App\Code\Local\Pixelplant\Blog\Controller\Admin
{
	use \App\Code\Core\ZeroG\Core\Controller\Admin as AdminController;

	class EditController extends AdminController
	{
		public function postAction()
		{
			$id = $this->getRequest()->getParam('id', 0);
			$model = \Z::getModel('blog/post')->load($id);

			\Z::register('blog_post', $model);

			$this->loadLayout();
			$this->renderLayout();
		}
	}
}
