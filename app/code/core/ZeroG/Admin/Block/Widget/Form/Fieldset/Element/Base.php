<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Form\Fieldset\Element
{

	/**
	 * Description of Form Fieldset Element Base template
	 *
	 * @author radu.mogos
	 */
	class Base extends \App\Code\Core\ZeroG\Admin\Block\Template
	{
		protected function _construct()
		{
			parent::_construct();
			//$this->_id = 'default';
		}

		public function render()
		{
			return 'empty element';
		}
	}
}
