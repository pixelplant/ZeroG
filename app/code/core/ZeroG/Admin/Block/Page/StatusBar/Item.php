<?php

namespace App\Code\Core\ZeroG\Admin\Block\Page\StatusBar
{

	/**
	 * Description of StatusBar Item block
	 *
	 * @author radu.mogos
	 */
	class Item extends \App\Code\Core\ZeroG\Admin\Block\Template
	{
		/**
		 * The statusbar this item is linked to...
		 *
		 * @var <\App\Code\Core\ZeroG\Admin\Block\Page\StatusBar>
		 */
		protected $_statusBar;
		/**
		 * Generates the admin menu block
		 */
		protected function _construct()
		{
		}

		/**
		 * Set this item's status bar
		 *
		 * @param <type> $statusBar
		 */
		public function setStatusBar($statusBar)
		{
			$this->_statusBar = $statusBar;
			return $this;
		}

		/**
		 * Return the status bar this item belongs to+
		 *
		 * 
		 */
		public function getStatusBar()
		{
			return $this->_statusBar;
		}

		public function render()
		{
			$action = ($this->getAction() == '') ? '' : $this->getUrl($this->getAction());
			$name = $this->getName();
			$target = 'status-bar-popup-'.$name;
			$html = '<dt>'.$this->getTitle().'</dt>';
			$html .= '<dd id="status-bar-'.$name.'" class="status-bar-popup">
					<a href="javascript:void(0)" onclick="statusBarPopup(\''.$action.'\', \''.$name.'\')" class="plugins" id="indicator-'.$name.'"><span>'.$this->getLabel().'</span></a>
						<div style="display:none" class="popup" id="status-bar-popup-'.$name.'">
						</div>
						</dd>';
			return $html;
		}
	}
}
