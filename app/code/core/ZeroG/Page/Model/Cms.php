<?php

/**
 * Cms page model
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Page\Model
{
	class Cms extends \Sys\Database\Model
	{
		protected $_eventPrefix = 'cms';

		protected function _construct()
		{
			parent::_construct();
			$this->_init('page/cms', 'page_id');
		}
	}
}
