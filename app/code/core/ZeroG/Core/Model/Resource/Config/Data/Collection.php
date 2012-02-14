<?php

namespace App\Code\Core\ZeroG\Core\Model\Resource\Config\Data
{

	/**
	 * Description of Collection
	 *
	 * @author radu.mogos
	 */
	class Collection extends \Sys\Database\Model\Collection
	{
		protected function _construct()
		{
			$this->_init('core/config_data');
		}

		public function toFlatArray()
		{
			$flatArray = array();
			foreach ($this->getItems() as $item)
			{
				$flatArray['config/global/'.$item->getPath()] = $item->getValue();
			}
			return $flatArray;
		}
	}
}
