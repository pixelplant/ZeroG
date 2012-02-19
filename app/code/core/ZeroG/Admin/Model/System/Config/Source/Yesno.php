<?php

namespace App\Code\Core\ZeroG\Admin\Model\System\Config\Source
{
	class Yesno
	{
		public function toOptionArray()
		{
			return array(
				array('value' => 1, 'label' => \Z::getHelper('admin')->__('Yes')),
				array('value' => 0, 'label' => \Z::getHelper('admin')->__('No')),
			);
		}
	}
}