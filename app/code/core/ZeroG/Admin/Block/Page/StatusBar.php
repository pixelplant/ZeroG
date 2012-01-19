<?php

namespace App\Code\Core\ZeroG\Admin\Block\Page
{

	/**
	 * Description of StatusBar block
	 *
	 * @author radu.mogos
	 */
	class StatusBar extends \App\Code\Core\ZeroG\Admin\Block\Template
	{
		/**
		 * Generates the admin menu block
		 */
		protected function _beforeToHtml()
		{
			//$this->setTemplate('page/menu.phtml');
			$this->getLeft()->addChild($this->createItem());
		}

		public function createItem()
		{
			return $this->getLayout()
					->createBlock('admin/page/statusBar/item')
					->setTitle('Notificari')
					->setLabel('2')
					->setAction('page/index/testme')
					->setStatusBar($this);
		}

		public function getLeft()
		{
			return $this->_children['status_bar.left'];
		}

		public function getRight()
		{
			return $this->_children['status_bar.right'];
		}
	}
}
