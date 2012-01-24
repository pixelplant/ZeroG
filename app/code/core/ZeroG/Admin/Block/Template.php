<?php

namespace App\Code\Core\ZeroG\Admin\Block
{

	/**
	 * Description of Template
	 *
	 * @author radu.mogos
	 */
	class Template extends \Sys\Layout\Block
	{
		/**
		 * Used mainly for the html element's id tag
		 * 
		 * @var <string> 
		 */
		protected $_id;

		public function getId()
		{
			return $this->_id;
		}

		public function setId($id)
		{
			$this->_id = $id;
			return $this;
		}

		public function getFormKey()
		{
			return \Z::getSingleton('core/session')->getFormKey();
		}

		public function getHtmlId()
		{
			return 'template_'.$this->_id;
		}
	}
}
