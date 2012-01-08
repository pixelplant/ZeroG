<?php

namespace App\Code\Core\ZeroG\Admin\Controller
{
	/**
	 * Description of Index
	 *
	 * @author radu.mogos
	 */
	class Index extends \App\Code\Core\ZeroG\Core\Controller\Front
	{
		public function indexAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}
	}
}
