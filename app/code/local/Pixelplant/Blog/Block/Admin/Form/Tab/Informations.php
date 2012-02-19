<?php

/**
 * Blog categories Admin grid block
 */

namespace App\Code\Local\Pixelplant\Blog\Block\Admin\Form\Tab
{
	class Informations extends \App\Code\Core\ZeroG\Admin\Block\Widget\Form\Tab
	{
		protected function _construct()
		{
			$fieldset = $this->_addFieldset('post_data', $this->__('Post data'));

			$fieldset->addElement('title', array(
				'type'  => 'text',
				'label' => $this->__('Title'),
				'index'  => 'title'
			))
			->addElement('post_content', array(
				'type'  => 'editor',
				'label' => $this->__('Post content'),
				'index'  => 'post_content',
			));

			$fieldset = $this->_addFieldset('activation_settings', $this->__('Activation settings'));
			$fieldset
			->addElement('comments', array(
				'type'   => 'select',
				'label'  => $this->__('Enable comments'),
				'index'  => 'comments',
				'values' => array(
					array(
						'value'     => 0,
						'label'     => $this->__('Disabled'),
						),
					array(
						'value'     => 1,
						'label'     => $this->__('Enabled'),
						),
					),
				'after_element_html' => $this->__('Disabling will close the post to new comments'),
				))
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