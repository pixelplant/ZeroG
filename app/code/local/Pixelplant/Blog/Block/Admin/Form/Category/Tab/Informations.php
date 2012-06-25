<?php

/**
 * Blog categories Admin grid block
 */

namespace App\Code\Local\Pixelplant\Blog\Block\Admin\Form\Category\Tab
{
	class Informations extends \App\Code\Core\ZeroG\Admin\Block\Widget\Form\Tab
	{
		protected function _construct()
		{
			$fieldset = $this->_addFieldset('category_data', $this->__('Category data'));

			$fieldset->addElement('title', array(
				'type'  => 'text',
				'label' => $this->__('Title'),
				'index'  => 'title'
			));

			$fieldset = $this->_addFieldset('activation_settings', $this->__('Activation settings'));
			$fieldset
			->addElement('published', array(
				'type'   => 'select',
				'label'  => $this->__('Post state'),
				'index'  => 'published',
				'values' => array(
					array(
						'value'     => 1,
						'label'     => $this->__('Published'),
						),
					array(
						'value'     => 0,
						'label'     => $this->__('Not published'),
						),
					),
				'after_element_html' => $this->__('Only published posts appear in the frontend'),
				));
		}
	}
}