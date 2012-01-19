<?php

namespace App\Code\Local\Pixelplant\Blog\Block\Admin\Grid
{
	class Posts extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid
	{
		protected function _prepareColumns()
		{
			$this->getHeader()
				->addButton('new', array(
					'label'   => 'Adauga ceva nou',
					'onclick' => 'ceva',
					'class'   => 'ui-icon-circle-plus',
				))
				->addButton('amazon', array(
					'label'   => 'Fetch Amazon bullshit',
					'onclick' => 'blabla',
					'class'   => 'ui-icon-transfer-e-w',
				));

			$this->setId('blog_posts');

			$this->addColumn('post_id', array(
				'header'   => $this->__('Post id'),
				'index'    => 'post_id',
				'type'     => 'number',
				'sortable' => true,
				'width'    => '10%'
				));

			$this->addColumn('title', array(
				'header'   => $this->__('Overall post'),
				'index'    => 'title',
				'sortable' => true,
				//'renderer' => 'admin/widget/grid/column/renderer/base',
				//'width'    => '70%'
				));

			$this->addColumn('published', array(
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
				));

			$this->addColumn('col3', array(
				'header'   => $this->__('Created at'),
				'index'    => 'created_time',
				'type'     => 'date',
				'width'    => '10%'
				));

			$this->addColumn('col4', array(
				'header'   => $this->__('Last modified'),
				'index'    => 'updated_time',
				'type'     => 'date',
				'width'    => '10%'
				));

			parent::_prepareColumns();
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