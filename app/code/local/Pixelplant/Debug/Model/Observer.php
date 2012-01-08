<?php

namespace App\Code\Local\Pixelplant\Debug\Model
{
	class Observer
	{
		public function modelLoadBefore($event)
		{
			\Z::getProfiler()->start('model/load');
		}

		public function modelLoadAfter($event)
		{
			\Z::getProfiler()->stop('model/load');
			$logger = $this->_getLogger();
			$data['name']   = $event->getObject()->getResourceName();
			$data['class']  = $event->getObject()->getClassName();
			$timer          = \Z::getProfiler()->getStatistics('model/load');
			$data['time']   = $timer['cpu'];
			$data['memory'] = $timer['memory'];
			$logger->log($logger::TYPE_MODEL, $data);
		}

		public function collectionLoadBefore($event)
		{
			\Z::getProfiler()->start('collection/load');
		}

		public function collectionLoadAfter($event)
		{
			\Z::getProfiler()->stop('collection/load');
			$logger         = $this->_getLogger();
			$data['name']   = $event->getObject()->getResourceName();
			$data['class']  = $event->getObject()->getClassName();
			$timer          = \Z::getProfiler()->getStatistics('collection/load');
			$data['time']   = $timer['cpu'];
			$data['memory'] = $timer['memory'];
			$logger->log($logger::TYPE_COLLECTION, $data);
		}

		public function blockRenderBefore($event)
		{
			$logger = $this->_getLogger();
			$data['name'] = $event->getObject()->getName();
			$data['class'] = get_class($event->getObject());
			$data['template'] = $event->getObject()->getTemplate();
			$logger->log($logger::TYPE_BLOCK, $data);
		}
		
		public function layoutRenderBefore($event)
		{
			$debugBlock = \Z::getLayout()->createBlock('debug/logger', 'logger')->setTemplate('debug/logger.phtml');
			$event->getObject()->getBlock('content')->addChild($debugBlock);
		}
		
		protected function _getLogger()
		{
			return \Z::getSingleton('debug/logger');
		}
	}
}