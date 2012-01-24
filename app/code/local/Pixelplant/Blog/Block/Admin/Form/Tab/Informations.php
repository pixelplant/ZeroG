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
			$fieldset = $this->_addFieldset('fieldset1', 'Test title');

			$fieldset->addElement('title', array(
				'type'  => 'text',
				'label' => $this->__('Title'),
				'index'  => 'title'
			))
			->addElement('post_content', array(
				'type'  => 'textarea',
				'label' => $this->__('Post content'),
				'index'  => 'post_content',
				'after_element_html' => 'Content of your post'
			))
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
				'after_element_html' => '<span class="hint">Disabling will close the post to new comments</span>',
				));
		}
	}
}