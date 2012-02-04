<?php

/**
 * Blog categories Admin grid block
 */

namespace App\Code\Core\ZeroG\Admin\Block\System\Emails\Form\Tab
{
	class Informations extends \App\Code\Core\ZeroG\Admin\Block\Widget\Form\Tab
	{
		protected function _construct()
		{
			$fieldset = $this->_addFieldset('informations', $this->__('Template data'));

			$fieldset->addElement('template_code', array(
				'type'  => 'text',
				'label' => $this->__('Template name'),
				'index'  => 'template_code'
			))
			->addElement('template_subject', array(
				'type'  => 'text',
				'label' => $this->__('Subject'),
				'index'  => 'template_subject'
			))
			->addElement('template_text', array(
				'type'  => 'textarea',
				'label' => $this->__('Template content'),
				'index'  => 'template_text'
			));
			/*->addElement('comments', array(
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
				'after_element_html' => '<span class="hint">Only published posts appear in the frontend</span>',
				));*/
		}
	}
}