<?php

namespace App\Code\Core\ZeroG\Page\Block
{
	
	class Cms extends \Sys\Layout\Block
	{
		public function getPage()
		{
			$pageId = $this->getRequest()->getParam('id');
			$page = \Z::getModel('page/cms')->load($pageId);

			$this->getLayout()->getBlock('head')->setTitle($page->getTitle());

			return $page;
		}
	}
}
