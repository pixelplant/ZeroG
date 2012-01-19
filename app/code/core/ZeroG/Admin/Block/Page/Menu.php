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
		protected $_menuItems;

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
				$menuItem['label']        = $childData['title'];
				if (isset($childData['children']))
				{
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
	}
}
