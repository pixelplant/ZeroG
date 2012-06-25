<?php

/**
 * Blog categories Admin grid block
 */

namespace App\Code\Local\Pixelplant\Blog\Block\Admin\Form\Post\Tab
{
	class Metadata extends \App\Code\Core\ZeroG\Admin\Block\Widget\Form\Tab
	{
		protected function _construct()
		{
			$fieldset = $this->_addFieldset('fieldset1', $this->__('Metadata'));

			$fieldset->addElement('meta_keywords', array(
				'type'  => 'text',
				'label' => $this->__('Meta keywords'),
				'index'  => 'meta_keywords'
			))
			->addElement('meta_description', array(
				'type'  => 'textarea',
				'label' => $this->__('Meta description'),
				'index'  => 'meta_description'
			));
		}
	}
}