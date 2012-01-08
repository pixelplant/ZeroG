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
			$siteCode = isset($params['_site_code']) ? $params['_site_code'] : '';
			$siteType = isset($params['_site_type']) ? $params['_site_type'] : 'website_view';
			$options  = isset($params['_options']) ? $params['_options'] : array();
		}
	}
}
