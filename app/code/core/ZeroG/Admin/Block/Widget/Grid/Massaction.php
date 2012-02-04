<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid
{

	/**
	 * Massaction grid block
	 *
	 * @author radu.mogos
	 */
	class Massaction extends \App\Code\Core\ZeroG\Admin\Block\Widget
	{
		protected $_items = array();

		protected function _construct()
		{
			$this->setTemplate('widget/grid/massaction.phtml');
		}

		public function isAvailable()
		{
			return ($this->getCount() > 0 && $this->getParent()->getMassactionField());
		}

		public function addItem($id, $itemData)
		{
			$this->_items[$id] = $this->getLayout()
					->createBlock('admin/widget/grid/massaction/item')
					->setData($itemData)
					->setId($id)
					->setMassaction($this);

			return $this;
		}

		public function getItem($id)
		{
			if (isset($this->_items[$id]))
			{
				return $this->_items[$id];
			}
			return null;
		}

		public function getItems()
		{
			return $this->_items;
		}

		public function getCount()
		{
			return sizeof($this->_items);
		}

		public function getHtmlId()
		{
			return $this->getParent()->getHtmlId().'_massaction';
		}

		public function getFormFieldName()
		{
			return ($this->getData('form_field_name') ? $this->getData('form_field_name') : 'massaction');
		}
	}
}
