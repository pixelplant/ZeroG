<?php

/**
 * Form for Blog/Category
 */

namespace App\Code\Local\Pixelplant\Blog\Block\Admin\Form
{
	class Category extends \App\Code\Core\ZeroG\Admin\Block\Widget\Form
	{

		protected function _prepareForm()
		{
			$this->addButton('back', array('type' => 'button', 'class'=> 'ui-icon-triangle-1-w', 'label' => $this->helper('admin')->__('Back')));
			parent::_prepareForm();
			$this->addButton('delete', array('type' => 'button', 'class'=> 'ui-icon-trash', 'label' => $this->helper('admin')->__('Delete')));

			$this->_id = 'edit_blog_category';
			
			$this->addTab('informations', 'blog/admin/form/category/tab/informations', array(
				'label' => $this->__('Informations'),
				'title' => 'Another title'));

			// Populate the variable 'blog_category' with the currently loaded
			// blog category
			if (\Z::registry('blog_category'))
			{
				$this->setValues(\Z::registry('blog_category')->getData());
			}
		}

		public function getHeaderText()
		{
			return $this->__('Edit blog category');
		}
	}
}