<?php

/**
 * Description of Cms Resource
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Page\Model\Resource
{
	class Cms extends \Sys\Database\Resource
	{
		protected function _construct()
		{
			$this->_init('page/cms', 'page_id');
		}
	}
}
