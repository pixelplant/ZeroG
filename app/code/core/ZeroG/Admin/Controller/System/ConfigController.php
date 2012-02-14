<?php

namespace App\Code\Core\ZeroG\Admin\Controller\System
{
	class ConfigController extends \App\Code\Core\ZeroG\Core\Controller\Admin
	{
		public function indexAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}

		public function editAction()
		{
		}
	}
}
