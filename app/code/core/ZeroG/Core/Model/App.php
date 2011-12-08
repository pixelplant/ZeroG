<?php

namespace App\Code\Core\ZeroG\Core\Model
{
	/**
	 * Application class - does all initialization
	 *
	 * @author radu.mogos
	 */
	class App
	{
		const ADMIN_SITE_ID = 0;

		/**
		 * Current website view code
		 * 
		 * @var <string>
		 */
		protected $_currentWebsiteViewCode;

		public function __construct()
		{
			
		}

		/**
		 * Initialize the site with the specified type and code
		 * @param <string> $code
		 * @param <string> $type
		 * @return <App\Code\Core\ZeroG\Core\Model\App>
		 */
		public function init($code, $type = null)
		{
			return $this;
		}

		public function run($params)
		{
			$siteCode = isset($params['site_code']) ? $params['site_code'] : '';
			$siteType = isset($params['site_type']) ? $params['site_type'] : 'website_view';
			$options  = isset($params['options']) ? $params['options'] : array();
		}
	}
}
