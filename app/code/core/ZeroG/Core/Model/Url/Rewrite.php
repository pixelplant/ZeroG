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
		protected function _constructor()
		{
			$this->_init('core_resource/url_website', 'website_id');
		}
	}
}
