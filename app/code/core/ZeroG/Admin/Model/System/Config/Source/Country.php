<?php

namespace App\Code\Core\ZeroG\Admin\Model\System\Config\Source
{
	class Country
	{
		public function toOptionArray()
		{
			return array(
				array('value' => 'RO', 'label' => \Z::getHelper('admin')->__('Romania')),
				array('value' => 'FR', 'label' => \Z::getHelper('admin')->__('France')),
			);
		}
	}
}