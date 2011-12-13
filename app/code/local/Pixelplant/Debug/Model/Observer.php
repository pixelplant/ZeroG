<?php

namespace App\Code\Local\Pixelplant\Debug\Model
{
	class Observer
	{
		public function modelLoadAfter($event)
		{
			$logger = $this->_getLogger();
			$data['name'] = $event->getObject()->getClassName();
			$logger->log($logger::TYPE_MODEL, $data);
		}
		
		public function layoutRenderBefore($event)
		{
			$debugBlock = \Z::getBlock('debug/logger')->setName('logger')
								->setTemplate('debug/logger.phtml');
			$event->getObject()->getBlock('content')->addChild($debugBlock);
		}
		
		protected function _getLogger()
		{
			return \Z::getSingleton('debug/log');
		}
	}
}