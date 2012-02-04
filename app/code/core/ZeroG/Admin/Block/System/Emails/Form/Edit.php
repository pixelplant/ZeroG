<?php

/**
 * Blog categories Admin grid block
 */

namespace App\Code\Core\ZeroG\Admin\Block\System\Emails\Form
{
	class Edit extends \App\Code\Core\ZeroG\Admin\Block\Widget\Form
	{
		protected function _prepareForm()
		{
			$this->_id = 'edit_email_template';

			$this->addTab('informations', 'admin/system/emails/form/tab/informations', array(
				'label' => $this->__('Informations'),
				'title' => 'Another title'));

			// Populate the variable 'blog_post' with the currently loaded
			// blog post
			if (\Z::registry('email_template'))
			{
				$this->setValues(\Z::registry('email_template')->getData());
			}
		}

		public function getHeaderText()
		{
			return $this->__('Edit email template');
		}
	}
}