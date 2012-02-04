<?php

namespace App\Code\Core\ZeroG\Admin\Controller\System
{
	/**
	 * Description of Emails controller
	 *
	 * @author radu.mogos
	 */
	class EmailsController extends \App\Code\Core\ZeroG\Core\Controller\Admin
	{
		protected function _initController()
		{
			$this->getLayout()->getBlock('head')->setTitle('Edit email templates');
		}

		public function indexAction()
		{
			$this->loadLayout();
			$this->_initController();
			$this->renderLayout();
		}

		public function editAction()
		{
			$id = (int)$this->getRequest()->getParam('id');
			\Z::register('email_template', \Z::getModel('core/email/template')->load($id));

			$this->loadLayout();
			$this->renderLayout();
		}
	}
}
