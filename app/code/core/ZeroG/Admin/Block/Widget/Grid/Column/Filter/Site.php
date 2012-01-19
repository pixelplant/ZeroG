<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter
{

	/**
	 * Site selector
	 *
	 * @author radu.mogos
	 */
	class Site extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter\Select
	{

		public function getContent()
		{
			$viewCollection    = \Z::getModel('core/website/view')->getCollection();
			//->addFieldToFilter('website_view_id', array('gt' => '0'));
			$groupCollection   = \Z::getModel('core/website/group')->getCollection();
			$websiteCollection = \Z::getModel('core/website')->getCollection();

			$html = '<select name="'.$this->getFieldName().'" id="'.$this->getHtmlId().'">';

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
						if ($view->getWebsiteGroupId() != $group->getId())
						{
							continue;
						}
						if (!$websiteShow)
						{
							$websiteShow = true;
							$html .= '<optgroup label="' . $website->getName() . '"></optgroup>';
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
		}

	}
}
