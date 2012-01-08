<?php

namespace App\Code\Local\Pixelplant\Debug\Model
{
	class Logger extends \Sys\Model
	{
		const TYPE_MODEL      = 'model';
		const TYPE_BLOCK      = 'block';
		const TYPE_PROFILER   = 'profiler';
		const TYPE_REQUEST    = 'request';
		const TYPE_COLLECTION = 'collection';

		protected $_logData;

		protected function _construct()
		{
			$this->_logData = array();
			//$this->_logData[self::TYPE_REQUEST]['name'] = \Z::getRequest()->getParams();
		}
		
		public function log($type, $data)
		{
			if (isset($this->_logData[$type][$data['name']]))
			{
				$this->_logData[$type][$data['name']]['count']++;
			}
			else
			{
				$this->_logData[$type][$data['name']] = $data;
				$this->_logData[$type][$data['name']]['count'] = 1;
			}
		}
		
		public function getLogData()
		{
			// we log the request data at the very end...
			$this->_logData[self::TYPE_REQUEST]['name'] = \Z::getRequest()->getParams();
			return $this->_logData;
		}
	}
}