<?php

namespace App\Code\Core\ZeroG\Admin\Block\System\Config
{
	class Tabs extends \App\Code\Core\ZeroG\Admin\Block\Template
	{
		/**
		 * List of config sections defined in system.xml
		 * 
		 * @var array
		 */
		protected $_sections = array();

		/**
		 * List of groups defined in system.xml
		 * 
		 * @var array
		 */
		protected $_groups = array();
		
		/**
		 * A list of fields to load and show in the seciton page
		 * 
		 * @var array 
		 */
		protected $_fieldsToLoad = array();
		
		protected function _construct()
		{
			$this->setTitle($this->__('Configuration'));
		}

		/**
		 * Returns a list of currently defined tabs
		 * 
		 * @return \Sys\Model\Collection
		 */
		public function getTabs()
		{
			$this->loadSections();

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
				$tabs->addItem($tab);
			}
			return $tabs;
		}

		/**
		 * Load all sections defined in system.xml
		 * @return App\Code\Core\ZeroG\Admin\Block\System\Config\Tabs
		 */
		public function loadSections()
		{
			if ($this->_sections == null)
			{
				$sections = \Z::getConfig()->getNode('sections');
				$_currentSection = $this->getRequest()->getParam('section');
				foreach ($sections as $_name => $_section)
				{
					$section = new \Sys\Model();
					$section->setData('label', $this->helper($_section['helper'])->__($_section['label']));
					$section->setData('url', $this->getUrl('*/*/edit/', array('section' => $_name)));
					$section->setData('name', $_name);
					if ($_currentSection == $_name)
					{
						$section->setData('is_current', true);
					}
					else
					{
						$section->setData('is_current', false);
					}
					$this->_sections[$_section['tab']][] = $section;
				}
			}
			return $this;
		}

		/**
		 * Load all groups defined in a section
		 * 
		 * @param string $sectionIdentifier
		 * @return boolean|\App\Code\Core\ZeroG\Admin\Block\System\Config\Tabs 
		 */
		protected function _loadGroups($sectionIdentifier)
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
							->setTemplate('system/config/form/fieldset.phtml')
							->setName($_groupName)
							->setSectionName($sectionIdentifier)
							->setTabName($groupsXml[$sectionIdentifier]['tab'])
							->setData('label', $this->helper($_group['helper'])->__($_group['label']));
					$this->setGroupFields($this->_groups[$sectionIdentifier][$_groupName], $_group['fields']);
					//$group = new \Sys\Model();
					//$group->setData('label', $this->helper($_group['helper'])->__($_group['label']));
					//$group->setData('fields', $this->getFields($_group['fields']));
					//$this->_groups[$sectionIdentifier][$_groupName] = $group;
				}
				$this->_loadFieldsFromDatabase($sectionIdentifier);
			}
			return $this;
		}

		/*
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
		}*/

		public function setGroupFields($group, $xmlFields)
		{
			foreach ($xmlFields as $_name => $_fieldData)
			{
				$visible = false;
				if (isset($_fieldData['show_in_default']) && $_fieldData['show_in_default'] == '1')
				{
					$visible = true;
				}
				if (isset($_fieldData['show_in_website']) && $_fieldData['show_in_website'] == '1' && ($this->getRequest()->getParam('scope') == 'website'))
				{
					$visible = true;
				}
				if (isset($_fieldData['show_in_website_view']) && $_fieldData['show_in_website_view'] == '1' && ($this->getRequest()->getParam('scope') == 'website_view'))
				{
					$visible = true;
				}
				if ($visible)
				{
					$_fieldData['label'] = $this->helper($_fieldData['helper'])->__($_fieldData['label']);
					$_fieldData['index'] = $group->getTabName().'['.$group->getSectionName().']['.$group->getName().']['.$_name.']';
					$this->_fieldsToLoad[] = $group->getTabName().'\\'.$group->getSectionName().'\\'.$group->getName().'\\'.$_name;
					$field = $group->addElement($_fieldData['index'], $_fieldData);
				}
			}
			return $group;
		}
		
		protected function _loadFieldsFromDatabase($sectionIdentifier)
		{
			$collection = \Z::getModel('core/config_data')->getCollection()
					->addFieldToFilter('path', array('in' => $this->_fieldsToLoad));
			$data = array();
			foreach ($collection as $item)
			{
				$path = implode("][", explode("\\", $item->getPath())).']';
				$path = preg_replace('/\]\[/', '[', $path, 1);
				$data[$path] = $item->getValue();
			}
			foreach ($this->_groups[$sectionIdentifier] as $group)
			{
				$group->setElementsValues($data);
			}
		}
			
		/**
		 * Get sections by name
		 * 
		 * @param string $tabIdentifier
		 * @return boolean|\Sys\Model 
		 */
		public function getSections($tabIdentifier)
		{
			if (isset($this->_sections[$tabIdentifier]))
			{
				return $this->_sections[$tabIdentifier];
			}
			return false;
		}

		/**
		 * Get all groups defined for a section
		 * 
		 * @param string $sectionIdentifier
		 * @return array
		 */
		public function getGroups($sectionIdentifier = null)
		{
			if ($sectionIdentifier == null)
			{
				$sectionIdentifier = \Z::getRequest()->getParam('section');
			}
			$this->_loadGroups($sectionIdentifier);
			if (isset($this->_groups[$sectionIdentifier]))
			{
				return $this->_groups[$sectionIdentifier];
			}
			return array();
		}
	}
}
