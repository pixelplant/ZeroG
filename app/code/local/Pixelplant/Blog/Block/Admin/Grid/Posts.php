<?php

namespace App\Code\Local\Pixelplant\Blog\Block\Admin\Grid
{
	class Posts extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid
	{
		protected function _prepareColumns()
		{
			$this->setId('blog_posts');

			$this->addColumn('col1', array(
				'header'   => $this->__('Post id'),
				'index'    => 'post_id',
				'type'     => 'number',
				'width'    => '10%'
				));

			$this->addColumn('col2', array(
				'header'   => $this->__('Overall post'),
				'renderer' => 'admin/widget/grid/column/renderer/base',
				//'width'    => '70%'
				));

			$this->addColumn('published', array(
				'header'   => $this->__('Published'),
				'index'    => 'published',
				'type'     => 'checkbox',
				'width'    => '10%'
				));

			$this->addColumn('col3', array(
				'header'   => $this->__('Created at'),
				'index'    => 'created_time',
				'type'     => 'date',
				'width'    => '10%'
				));

			$this->addColumn('test', array(
				'header'   => $this->__('Options'),
				'index'    => 'test',
				'type'     => 'select',
				'width'    => '10%',
				'options'  => array(
					'v1' => $this->__('First option'),
					'v2' => $this->__('Second option')),
				));

			$this->addColumn('col4', array(
				'header'   => $this->__('Last modified'),
				'index'    => 'updated_time',
				'width'    => '10%'
				));

			parent::_prepareColumns();
			return $this;
		}

		protected function _prepareCollection()
		{
			$this->setCollection(\Z::getModel('blog/post')->getCollection()->load());
			parent::_prepareCollection();
			return $this;
		}
	}
}