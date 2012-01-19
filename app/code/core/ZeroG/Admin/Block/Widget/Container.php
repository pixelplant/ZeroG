<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget
{

	/**
	 * Description of Container Widget
	 *
	 * @author radu.mogos
	 */
	class Container extends \App\Code\Core\ZeroG\Admin\Block\Widget
	{
		protected $_buttons = array();

		protected $_title = 'Container title';

		/**
		 * Adds a button to the container
		 *
		 * @param <string> $name
		 * @param <array> $data
		 * @return <Container>
		 */
		protected function _addButton($name, $data)
		{
			$this->_buttons[$name] = $this->getLayout()->createBlock('admin/widget/button')->setData($data);
			return $this;
		}

		/**
		 * Public callback for _addButton
		 * 
		 * @param <string> $name
		 * @param <array> $data
		 * @return <Container>
		 */
		public function addButton($name, $data)
		{
			return $this->_addButton($name, $data);
		}

		/**
		 * Removes a button from the container
		 *
		 * @param <string> $name
		 * @return <Container>
		 */
		protected function _removeButton($name)
		{
			if (isset($this->_buttons[$name]))
				unset($this->_buttons[$name]);
			return $this;
		}

		/**
		 * Public callback for _removeButton
		 *
		 * @param <string> $name
		 * @return <Container>
		 */
		public function removeButton($name)
		{
			return $this->_removeButton($name);
		}

		/**
		 * Updates the $key _data property with $value
		 *
		 * @param <string> $name Button block name
		 * @param <string> $key Key name to update
		 * @param <string> $value The new value of the key
		 * @return <Container>
		 */
		protected function _updateButton($name, $key, $value)
		{
			$child = $this->getChild($name);
			if (!empty($key))
				$child->setData($key, $value);
			return $this;
		}

		/**
		 * Reference to the _updateButton callback
		 *
		 * @param <string> $name
		 * @param <string> $key
		 * @param <string> $value
		 * @return <Container>
		 */
		public function updateButton($name, $key, $value)
		{
			return $this->_updateButton();
		}

		/**
		 * Render all the container buttons
		 *
		 * @return <string>
		 */
		public function getButtonsHtml()
		{
			$html = '';
			foreach ($this->_buttons as $button)
			{
				$html .= $button->getContent();
			}
			return $html;
		}

		/**
		 * Get container title
		 *
		 * @return <string>
		 */
		public function getTitle()
		{
			return $this->_title;
		}

		/**
		 * Set container title
		 *
		 * @param <string> $title
		 * @return Container
		 */
		public function setTitle($title)
		{
			$this->_title = $title;
			return $this;
		}

	}
}
