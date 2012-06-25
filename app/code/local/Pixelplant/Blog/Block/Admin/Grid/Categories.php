<?php

/**
 * Blog categories Admin grid block
 */

namespace App\Code\Local\Pixelplant\Blog\Block\Admin\Grid
{
	class Categories extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid
	{
		protected function _prepareColumns()
		{
			$this->setActionUrl('blog_admin/admin_list/categories');
			$this->setAjaxActionUrl('blog_admin/admin_list/categories_grid');

			$this->getHeader()
				->addButton('new', array(
					'label'   => $this->__('New category'),
					'onclick' => 'ceva',
					'class'   => 'ui-icon-circle-plus',
				))
				->setTitle($this->__('Blog categories'));

			// grid html id, used for the grid, forms, etc...
			$this->setId('blog_categories');

			$this->addColumn('cat_id', array(
				'header'   => $this->__('Category id'),
				'index'    => 'cat_id',
				'type'     => 'number',
				'sortable' => true,
				'width'    => '160px'
				));

			$this->addColumn('title', array(
				'header'   => $this->__('Title'),
				'index'    => 'title',
				'type'     => 'text',
				'sortable' => true,
				));

			$this->addColumn('action', array(
				'header'   => $this->__('Actions'),
				'filter'   => false,
				'sortable' => false,
				'type'     => 'action',
				//'index'    => 'stores',
				'actions'  => array(
					array(
						'caption' => $this->__('Edit'),
						'url'     => $this->getUrl('*/admin_edit/category'),
						'field'   => 'id',
						'value'   => 'cat_id'
					),
					array(
						'caption' => $this->__('Delete'),
						'url'     => $this->getUrl('*/admin_edit/delete'),
						'field'   => 'id',
						'value'   => 'cat_id'
					)),
				'width'    => '60px'
				));

			/*$this->addColumn('published', array(
				'header'   => $this->__('Published'),
				'index'    => 'published',
				'type'     => 'checkbox',
				'sortable' => true,
				'width'    => '10%'
				));

			$this->addColumn('site', array(
				'header'   => $this->__('Site view'),
				'index'    => 'site',
				'type'     => 'site',
				'width'    => '10%'
				));*/

			parent::_prepareColumns();
			return $this;
		}

		protected function _prepareCollection()
		{
			$this->setCollection(\Z::getModel('blog/category')->getCollection());
			parent::_prepareCollection();
			return $this;
		}
	}
}