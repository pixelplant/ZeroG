<?php

namespace App\Code\Core\ZeroG\Admin\Block\Page
{

	/**
	 * Description of Admin Page Menu block
	 *
	 * @author radu.mogos
	 */
	class Menu extends \App\Code\Core\ZeroG\Admin\Block\Template
	{
		/**
		 * Current loaded menu items
		 *
		 * @var <array>
		 */
		protected $_menuItems;

		/**
		 * Active path in the menu
		 *
		 * @var <string>
		 */
		protected $_active = null;

		protected $_xmlPath;
		protected $_xmlOrig;

		/**
		 * Generates the admin menu block
		 */
		protected function _construct()
		{
			$this->_menuItems = $this->_buildMenu();
			$this->setTemplate('page/menu.phtml');
		}

		/**
		 * Returns the generated admin menu items
		 *
		 * @return <array>
		 */
		public function getMenuItems()
		{
			return $this->_menuItems;
		}

		/**
		 * Recursively build the admin menu items
		 *
		 * @param <array> $menuData Menu child
		 * @return <array>
		 */
		protected function _buildMenu($menuData = null)
		{
			if ($menuData == null)
			{
				$menuData = \Z::getConfig()->getNode('menu');
			}

			$defaultOrder = 0;
			$menuItems    = array();
			foreach ($menuData as $childName => $childData)
			{
				$menuItem                 = array();
				$menuItem['name']         = $childName;
				$menuItem['sort_order']   = (isset($childData['sort_order'])) ? (int)$childData['sort_order'] : $defaultOrder;
				$menuItem['url']          = (isset($childData['action'])) ? $this->getUrl($childData['action']) : '#';
				//$menuItem['label']      = $this->helper($menu['helper'])->__($menu['title']);
				if (isset($childData['helper']))
				{
					$menuItem['label']        = $this->helper($childData['helper'])->__($childData['title']);
				}
				else
				{
					$menuItem['label']        = $this->__($childData['title']);
				}
				if (isset($childData['children']))
				{
					//$this->_xmlOrig = $this->_xmlOrig.$childName.'/';
					$menuItem['children'] = $this->_buildMenu($childData['children']);
				}
				$menuItems[$childName]    = $menuItem;
			}

			uasort($menuItems, array($this, '_sortByOrder'));

			return $menuItems;
		}

		/**
		 * Sort all menu items by their 'sort_order' attribute
		 *
		 * @param <int> $left
		 * @param <int> $right
		 * @return <array>
		 */
		protected function _sortByOrder($left, $right)
		{
			return $left['sort_order'] < $right['sort_order'] ? -1 : ($left['sort_order'] > $right['sort_order'] ? 1 : 0);
		}

		public function setActive($path)
		{
			$this->_active = $path;
		}
	}
}
