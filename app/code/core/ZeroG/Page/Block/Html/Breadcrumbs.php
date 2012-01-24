<?php

/**
 * Footer block class
 *
 */

namespace App\Code\Core\ZeroG\Page\Block\Html
{
	/**
	 * Bread...cruuuumbs!
	 *
	 * @author Radu Mogos <radu.mogos@pixelplant.ro>
	 * @package App
	 */
	class Breadcrumbs extends \Sys\Layout\Block
	{
		protected $_links;

		public function addLink($label, $url = null)
		{
			$data = array('label' => $label);
			if ($url != null)
				$data['url'] = $this->getUrl($url);
			$link = new \Sys\Model();
			$link->setData($data);
			$this->_links[] = $link;
			return $this;
		}

		public function getLinks()
		{
			return $this->_links;
		}
	}
}