<?php

/**
 * Description of Resource
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Core\Model\Email
{
	class Template extends \Sys\Database\Model
	{
		protected function _construct()
		{
			parent::_construct();
			$this->_init('core/email_template', 'template_id');
		}
	}
}
