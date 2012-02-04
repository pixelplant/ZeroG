<?php

namespace App\Code\Core\ZeroG\Admin\Block
{

	/**
	 * Description of Widget
	 *
	 * @author radu.mogos
	 */
	class Widget extends \App\Code\Core\ZeroG\Admin\Block\Template
	{
		public function getButtonHtml($label, $onclick, $class = '', $id = null)
		{
			return $this->getLayout()->createBlock('admin/widget/button')
				->setData(
					array(
						'label'   => $label,
						'onclick' => $onclick,
						'type'    => 'button',
						'class'   => $class,
						'id'      => $id))
					->getContent();
		}
	}
}
