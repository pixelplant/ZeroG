<?php

namespace App\Code\Local\Pixelplant\Debug\Model
{
	class Logger extends \Sys\Model
	{
		const TYPE_MODEL = 'model';
		const TYPE_BLOCK = 'block';

		protected $_logData;

		protected function _construct()
		{
			$this->_logData = array();
		}
		
		public function log ($type, $data)
		{
			if (isset($this->_logData[$type][$data['name']]))
			{
				$this->_logData[$type][$data['name']]['count']++;
			}
			else
			{
				$this->_logData[$type][$data['name']] = $data;
				$this->_logData[$type][$data['name']]['count'] = 0;
			}
		}
		
		public function getLogData()
		{
			return $this->_logData;
		}
	}
}