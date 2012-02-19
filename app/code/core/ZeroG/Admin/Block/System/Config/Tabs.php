<?php

namespace App\Code\Core\ZeroG\Admin\Block\System\Config
{
	class Tabs extends \App\Code\Core\ZeroG\Admin\Block\Template
	{
		protected $_sections = array();

		protected $_groups = array();

		public function getTabs()
		{
			$this->loadSections();
			$_currentTab = $this->getRequest()->getParam('section');
			$tabs = new \Sys\Model\Collection();
			// read the config/tabs node from system.xml files or other xml files
			$xmlTabs = \Z::getConfig()->getNode('tabs');
			foreach ($xmlTabs as $_name => $_xmlTab)
			{
				$tab = new \Sys\Model();
				$tab->setData('label', $this->helper($_xmlTab['helper'])->__($_xmlTab['label']));
				$tab->setData('sort_order', $_xmlTab['sort_order']);
				//$tab->setData('url', $this->getUrl('*/*/edit/', array('section' => $_name)));
				$tab->setData('sections', $this->getSections($_name));
				$tab->setData('name', $_name);
				if ($_currentTab == $_name)
				{
					$tab->setData('is_current', true);
				}
				else
				{
					$tab->setData('is_current', false);
				}
				$tabs->addItem($tab);
			}
			return $tabs;
		}

		public function loadSections()
		{
			if ($this->_sections == null)
			{
				$sections = \Z::getConfig()->getNode('sections');
				foreach ($sections as $_name => $_section)
				{
					$section = new \Sys\Model();
					$section->setData('label', $this->helper($_section['helper'])->__($_section['label']));
					$section->setData('url', $this->getUrl('*/*/edit/', array('section' => $_name)));
					$section->setData('name', $_name);
					$this->_sections[$_section['tab']][] = $section;
				}
			}
		}

		public function loadGroups($sectionIdentifier)
		{
			if ($this->_groups == null)
			{
				$groupsXml = \Z::getConfig()->getNode('sections');
				if (!isset($groupsXml[$sectionIdentifier]['groups']))
				{
					return false;
				}
				$groups = $groupsXml[$sectionIdentifier]['groups'];
				foreach ($groups as $_groupName => $_group)
				{
					$this->_groups[$sectionIdentifier][$_groupName] = $this->getLayout()
							->createBlock('admin/widget/form/fieldset')
							->setData('label', $this->helper($_group['helper'])->__($_group['label']));
					$this->setGroupFields($this->_groups[$sectionIdentifier][$_groupName], $_group['fields']);
					//$group = new \Sys\Model();
					//$group->setData('label', $this->helper($_group['helper'])->__($_group['label']));
					//$group->setData('fields', $this->getFields($_group['fields']));
					//$this->_groups[$sectionIdentifier][$_groupName] = $group;
				}
			}
		}

		public function getFields($xmlFields)
		{
			$fields = array();
			foreach ($xmlFields as $_name => $_field)
			{
				$field = new \Sys\Model();
				$field->setData($_field);
				$field->setIndex($_name);
				$fields[] = $field;
			}
			return $fields;
		}

		public function setGroupFields($group, $xmlFields)
		{
			foreach ($xmlFields as $_name => $_fieldData)
			{
				$visible = false;
				if ($_fieldData['show_in_default'] == '1')
				{
					$visible = true;
				}
				if ($_fieldData['show_in_website'] == '1' && ($this->getRequest()->getParam('scope') == 'website'))
				{
					$visible = true;
				}
				if ($_fieldData['show_in_website_view'] == '1' && ($this->getRequest()->getParam('scope') == 'website_view'))
				{
					$visible = true;
				}
				if ($visible)
				{
					$_fieldData['label'] = $this->helper($_fieldData['helper'])->__($_fieldData['label']);
					$field = $group->addElement($_name, $_fieldData);
				}
			}
			return $group;
		}

		public function getSections($tabIdentifier)
		{
			if (isset($this->_sections[$tabIdentifier]))
			{
				return $this->_sections[$tabIdentifier];
			}
			return false;
		}

		public function getGroups($sectionIdentifier = null)
		{
			if ($sectionIdentifier == null)
			{
				$sectionIdentifier = \Z::getRequest()->getParam('section');
			}
			$this->loadGroups($sectionIdentifier);
			if (isset($this->_groups[$sectionIdentifier]))
			{
				return $this->_groups[$sectionIdentifier];
			}
			return array();
		}
	}
}
