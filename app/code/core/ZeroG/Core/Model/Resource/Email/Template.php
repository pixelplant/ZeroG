<?php

namespace App\Code\Core\ZeroG\Core\Model\Resource\Email
{
	/**
	 * Description of Resource
	 *
	 * @author radu.mogos
	 */
	class Template extends \Sys\Database\Resource
	{
		protected function _construct()
		{
			//parent::_construct();
			$this->_init('core/email_template', 'template_id');
		}
	}
}
