<?php

namespace App\Code\Core\ZeroG\Core\Model\Resource\Website\Group
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
			$this->_init('core/website_group');
		}
	}
}
