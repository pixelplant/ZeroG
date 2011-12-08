<?php

/**
 * Website model
 * A website belongs to a website group and can have many (or at least 1)
 * website view
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Core\Model
{
	class Website extends \Sys\Database\Model
	{
		protected $_eventPrefix = 'website';

		protected function _construct()
		{
			parent::_construct();
			$this->_init('core/website', 'website_id');
		}
	}
}
