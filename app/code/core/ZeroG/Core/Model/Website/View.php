<?php

/**
 * Website view model
 *
 * @author radu.mogos
 */
namespace App\Code\Core\ZeroG\Core\Model\Website
{
	class View extends \Sys\Database\Model
	{
		const XML_BASE_URL        = 'config/global/default/base/url';
		const XML_BASE_SECURE_URL = 'config/global/default/base/secure_url';
		const XML_USE_REWRITES    = 'config/global/default/url/rewrite';

		const ADMIN_CODE          = 'admin';
		const DEFAULT_CODE        = 'default';
		/**
		 * The website this view belongs to
		 * 
		 * @var <\App\Code\Core\ZeroG\Core\Model\Website> 
		 */
		protected $_website;

		/**
		 * The website group this view belongs to
		 *
		 * @var <\App\Code\Core\ZeroG\Core\Model\Website\Group>
		 */
		protected $_website_group;

		/**
		 * Current site session
		 *
		 * @var <\Sys\Session>
		 */
		protected $_session;

		protected $_eventPrefix = 'website_view';
		
		protected function _construct()
		{
			parent::_construct();
			$this->_init('core/website_view', 'website_view_id');
		}

		protected function _getSession()
		{
			if (!$this->_session)
			{
				$this->_session = \Z::getModel('core/session')
						->init('site_'.$this->_getCode());
			}
			return $this->_session;
		}

		public function loadByCode($code)
		{
			$this->_getResource()->loadByField($this, 'code', $code);
			return $this;
		}

		public function setWebsite(\App\Code\Core\ZeroG\Core\Model\Website $website)
		{
			$this->_website = $website;
			return $this;
		}

		public function getWebsite()
		{
			if (is_null($this->getWebsiteId()))
			{
				return false;
			}
			if (is_null($this->_website))
			{
				//$this->_website = \Z::app()->getWebsite($this->getWebsiteId());
				// OR
				$this->_website = \Z::getModel('core/website')->load($this->getWebsiteId());
			}
			return $this->_website;
		}

		public function setWebsiteGroup(\App\Code\Core\ZeroG\Core\Model\Website\Group $group)
		{
			$this->_website_group = $group;
			return $this;
		}
		
		public function getWebsiteGroup()
		{
			if (is_null($this->getWebsiteGroupId()))
			{
				return false;
			}
			if (is_null($this->_website_group))
			{
				$this->_website_group = \Z::getModel('core/website/group')->load($this->getWebsiteGroupId());
			}
			return $this->_website_group;
		}

		public function isAdmin()
		{
			return ($this->getId() == \App\Code\Core\ZeroG\Core\Model\App::ADMIN_SITE_ID);
		}
	}
}
