<?php

namespace App\Code\Local\Pixelplant\Blog\Block\Admin\Grid
{
	class Posts extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid
	{
		protected function _prepareColumns()
		{
			$this->setActionUrl('blog_admin/admin_list/posts');
			$this->setAjaxActionUrl('blog_admin/admin_list/posts_grid');
			$this->setSaveParametersInSession(true);

			$this->getHeader()
				->addButton('new', array(
					'label'   => $this->__('New blog post'),
					'onclick' => 'ceva',
					'class'   => 'ui-icon-circle-plus',
				))
				->setTitle($this->__('Blog posts'));

			$this->setId('blog_posts');

			$this->addColumn('post_id', array(
				'header'   => $this->__('Post id'),
				'index'    => 'post_id',
				'type'     => 'number',
				'sortable' => true,
				'width'    => '160px'
				));

			$this->addColumn('title', array(
				'header'   => $this->__('Title'),
				'index'    => 'title',
				'type'     => 'text',
				'sortable' => true,
				//'renderer' => 'admin/widget/grid/column/renderer/base',
				//'width'    => '70%'
				));

			$this->addColumn('comments', array(
				'header'   => $this->__('Comments state'),
				'index'    => 'comments',
				'type'     => 'select',
				'sortable' => true,
				'options'  => array(
					1 => $this->__('Enabled'),
					0 => $this->__('Disabled'),
					),
				'width'    => '10%'
				));

			$this->addColumn('published', array(
				'header'   => $this->__('Post state'),
				'index'    => 'published',
				'type'     => 'select',
				'sortable' => true,
				'options'  => array(
					1 => $this->__('Published'),
					0 => $this->__('Not published'),
					),
				'width'    => '10%'
				));

			/*$this->addColumn('site', array(
				'header'   => $this->__('Site view'),
				'index'    => 'site',
				'type'     => 'site',
				'width'    => '10%'
				));*/

			$this->addColumn('created_time', array(
				'header'   => $this->__('Created at'),
				'index'    => 'created_time',
				'type'     => 'date',
				'sortable' => true,
				'width'    => '160px'
				));

			$this->addColumn('updated_time', array(
				'header'   => $this->__('Last modified'),
				'index'    => 'updated_time',
				'type'     => 'date',
				'width'    => '160px'
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
						'url'     => $this->getUrl('*/admin_edit/post'),
						'field'   => 'id',
						'value'   => 'post_id'
					)),
				'width'    => '60px'
				));

			parent::_prepareColumns();
			return $this;
		}

		protected function _prepareMassaction()
		{
			$this->setMassactionField('post_ids');
			$this->getMassactionBlock()
					->addItem('publish_selected', array(
							'label' => $this->__('Publish'),
							'url'   => $this->getUrl('*/*/publish')
						))
					->addItem('unpublish_selected', array(
							'label' => $this->__('Unpublish'),
							'url'   => $this->getUrl('*/*/unpublish')
						));
			return $this;
		}

		protected function _prepareCollection()
		{
			$this->setCollection(\Z::getModel('blog/post')->getCollection());
			parent::_prepareCollection();
			return $this;
		}
	}
}