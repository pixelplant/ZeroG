<?php

/**
 * Url Rewrite Resource model
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Core\Model\Resource\Url
{
	class Rewrite extends \Sys\Database\Resource
	{
		protected function _construct()
		{
			$this->_init('core/url_rewrite', 'url_rewrite_id');
		}

		public function loadByUrl($value)
		{
			return $this->loadByField('request_path', $value);
		}
	}
}
