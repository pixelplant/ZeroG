<?php

/**
 * Blog categories Admin grid block
 */

namespace App\Code\Local\Pixelplant\Blog\Block\Admin\Form
{
	class Post extends \App\Code\Core\ZeroG\Admin\Block\Widget\Form
	{
		protected function _prepareForm()
		{
			$this->_id = 'edit_blog_post';
			
			$this->addTab('tab2', 'blog/admin/form/tab/informations', array(
				'label' => $this->__('Informations'),
				'title' => 'Another title'))
			->addTab('tab1', 'admin/widget/form/tab', array(
				'label'   => $this->__('Advanced options'),
				'title'   => $this->__('First title'),
				//'content' => $this->getLayout()->createBlock('admin/widget/form/tab')->render()
			));

			if (\Z::registry('blog_post'))
			{
				$this->setValues(\Z::registry('blog_post')->getData());
			}
		}

		public function getHeaderText()
		{
			return $this->__('Edit blog post');
		}
	}
}