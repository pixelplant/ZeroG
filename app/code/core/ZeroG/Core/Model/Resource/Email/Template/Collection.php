<?php

namespace App\Code\Core\ZeroG\Core\Model\Resource\Email\Template
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
			$this->_init('core/email_template');
		}
	}
}
