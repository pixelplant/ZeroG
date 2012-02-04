<?php

namespace App\Code\Core\ZeroG\Admin\Block\System\Emails
{
	class Grid extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid
	{
		protected function _prepareColumns()
		{
			$this->setActionUrl('*/*/emails_grid');

			$this->getHeader()
				->addButton('new', array(
					'label'   => $this->__('New email template'),
					'onclick' => 'ceva',
					'class'   => 'ui-icon-circle-plus',
				))
				->setTitle($this->__('Email templates'));

			// grid id, you can add any text you want
			$this->setId('email_templates');

			$this->addColumn('template_id', array(
				'header'   => $this->__('Template id'),
				'index'    => 'template_id',
				'type'     => 'number',
				'sortable' => true,
				'width'    => '160px'
				));

			$this->addColumn('template_code', array(
				'header'   => $this->__('Template name'),
				'index'    => 'template_code',
				'type'     => 'text',
				'sortable' => true,
				//'renderer' => 'admin/widget/grid/column/renderer/base',
				//'width'    => '70%'
				));

			$this->addColumn('template_subject', array(
				'header'   => $this->__('Template subject'),
				'index'    => 'template_subject',
				'type'     => 'text',
				'sortable' => true,
				//'renderer' => 'admin/widget/grid/column/renderer/base',
				//'width'    => '70%'
				));

			$this->addColumn('template_sender_email', array(
				'header'   => $this->__('Sender email'),
				'index'    => 'template_sender_email',
				'type'     => 'text',
				'sortable' => true
				));

			/*$this->addColumn('site', array(
				'header'   => $this->__('Site view'),
				'index'    => 'site',
				'type'     => 'site',
				'width'    => '10%'
				));*/

			$this->addColumn('template_sender_name', array(
				'header'   => $this->__('Sender name'),
				'index'    => 'template_sender_name',
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
						'url'     => $this->getUrl('*/*/edit'),
						'field'   => 'id',
						'value'   => 'template_id'
					)),
				'width'    => '60px'
				));

			parent::_prepareColumns();
			return $this;
		}



		protected function _prepareCollection()
		{
			$this->setCollection(\Z::getModel('core/email_template')->getCollection());
			parent::_prepareCollection();
			return $this;
		}
	}
}