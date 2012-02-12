<?php

/**
 * Website model
 * A website belongs to a website group and can have many (or at least 1)
 * website view
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Core\Model
{
	class Website extends \Sys\Database\Model
	{
		protected $_eventPrefix = 'website';

		protected $_website_views;

		protected $_website_views_ids;

		protected $_website_groups;

		protected $_website_groups_ids;

		protected $_default_website_group;

		protected function _construct()
		{
			parent::_construct();
			$this->_init('core/website', 'website_id');
		}

		public function loadDefault()
		{
			$this->_getResource()->loadByField($this, 'is_default', 1);
			return $this;
		}

		public function loadByCode($code)
		{
			$this->_getResource()->loadByField($this, 'code', $code);
			return $this;
		}

		public function setWebsiteGroups($website_groups)
		{
			$this->_website_groups = $groups;
			foreach ($groups as $group)
			{
				$this->_website_groups[$group->getId()] = $group;
				$this->_website_groups_ids[$group->getId()] = $group->getId();
				if ($this->getDefaultWebsiteGroupId() == $group->getId())
				{
					$this->_default_website_group_id = $group->getId();
				}
			}
			return $this;
		}

		public function getDefaultWebsiteGroup()
		{
			if ($this->_default_website_group == null)
			{
				$this->_default_website_group = \Z::getModel('core/website/group')->load($this->getParam('default_website_group_id'));
			}
			return $this->_default_website_group;
		}
	}
}
