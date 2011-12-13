<?php

namespace App\Code\Core\ZeroG\Core\Model\Resource\Extension
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
			$this->_init('core/extension');
		}

		public function isInstalled($code)
		{
			foreach ($this->_items as $item)
			{
				if ($item->getCode() == $code)
					return $item;
			}
			return FALSE;
		}
	}
}
