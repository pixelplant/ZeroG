<?php

namespace App\Code\Core\ZeroG\Admin\Block\System\Config\Element
{

	/**
	 * Site selector
	 *
	 * @author radu.mogos
	 */
	class Site extends \Sys\Layout\Block
	{
		
		protected function _construct()
		{
			$this->setTemplate('system/config/element/site.phtml');
		}
		
		public function getWebsiteViewCollection()
		{
			return \Z::getModel('core/website/view')->getCollection();
		}
		
		public function getWebsiteGroupCollection()
		{
			return \Z::getModel('core/website/group')->getCollection();
		}
		
		public function getWebsiteCollection()
		{
			return \Z::getModel('core/website')->getCollection();
		}
		
		public function getEditUrl($params)
		{
			$params['_use_current'] = true;
			return $this->getUrl('*/*/*', $params);
		}
	
		/*public function render()
		{

			$html .= '<select name="'.$this->getFieldName().'" id="'.$this->getHtmlId().'">';

			foreach ($websiteCollection as $website)
			{
				$websiteShow = false;
				foreach ($groupCollection as $group)
				{
					if ($group->getWebsiteId() != $website->getId())
					{
						continue;
					}
					$groupShow = false;
					foreach ($viewCollection as $view)
					{
						if ($view->getWebsiteGroupId() != $group->getId() || $view->isAdmin())
						{
							continue;
						}
						if (!$websiteShow)
						{
							$websiteShow = true;
							$html .= '<option value="' . $website->getCode() . '">'.$website->getName().'</option>';
						}
						if (!$groupShow)
						{
							$groupShow = true;
							$html .= '<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;' . $group->getName() . '">';
						}
						$value = $this->getValue();
						$html .= '<option value="' . $view->getId() . '"' . ($value == $view->getId() ? ' selected="selected"' : '') . '>&nbsp;&nbsp;&nbsp;&nbsp;' . $view->getName() . '</option>';
					}
					if ($groupShow)
					{
						$html .= '</optgroup>';
					}
				}
			}

			$html .= '</select>';
			return $html;
		} */

	}
}
