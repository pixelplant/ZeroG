<?php

namespace App\Code\Core\ZeroG\Admin\Block
{

	/**
	 * Description of Template
	 *
	 * @author radu.mogos
	 */
	class Template extends \Sys\Layout\Block
	{
		public function getFormKey()
		{
			return \Z::getSingleton('core/session')->getFormKey();
		}
	}
}
