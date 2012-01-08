<?php

namespace App\Code\Core\ZeroG\Core\Model\Url
{

/**
 * Description of Rewrite
 *
 * @author radu.mogos
 */
	class Rewrite extends \Sys\Database\Model
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
