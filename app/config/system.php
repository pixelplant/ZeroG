<?php

namespace App\Config
{
	final class System
	{
		/**
		 * Config data for our application
		 * @var <array>
		 */
		protected $configData;

		public function __construct()
		{
			/**
			* Defines the location of the application directory, for the custom models, views controllers of this app
			*/
			$this->configData['app/dir'] = 'app';

			/**
			* Defines the location of the extensions used by the application
			*/
			$this->configData['ext/dir'] = 'ext';

			/**
			* Defines the base url, used for link generation + css and js linking
			* All content images (besides images used in the css) will be linked to
			* MEDIA_URL/MEDIA_URL2 below
			*/
			$this->configData['base/url'] = 'http://local/zerog/';

			/**
			* You can define a separate url from where images, pdf files and other
			* editor specific files would be retrieved from, this way you can have
			* parallel downloads of content and assets. Please note that your browser
			* opens up about 2 parallel connections for every domain, so using 1 or
			* 2 different hostnames for your media should increase the download speed.
			* This way you can also make sure you're using cookie free domains for
			* your components.
			* http://developer.yahoo.com/performance/rules.html
			*/
			$this->configData['media/url'] = 'http://local/zerog/';

			/**
			* The second link to a hostname storing your content (images, pdf, etc)
			* By default both links point to the base url, but you can change them
			* to a real location
			*
			*/
			$this->configData['media2/url'] = 'http://local/zerog';

			/**
			* enables nice urls or not. if set to TRUE, the mod_rewrite module
			* must be also loaded in Apache.
			*/
			$this->configData['default/controller'] = 'cms';

			/**
			* The default action called when no action is specified for the controller
			*/
			$this->configData['default/action'] = 'index';

			/**
			* enables nice urls or not. if set to TRUE, the mod_rewrite module
			* must be also loaded in Apache.
			*/
			$this->configData['url/rewrite'] = TRUE;

			/**
			* Developer mode disables all caching. When you are ready for production
			* set this field to FALSE!
			*/
			$this->configData['developer/mode'] = TRUE;

			/**
			* Locale settings
			* To change the settings for the current locale open the coresponding
			* xx_XX.xml file in sys\locale. Make sure you save the file as UTF-8
			*/
			$this->configData['locale'] = 'ro_RO';

			/**
			 * Do you need a database connection?
			 */
			$this->configData['use/db'] = FALSE;

			/**
			* Database DSN connection
			*/
			$this->configData['db/dsn'] = 'mysql:host=localhost;port=3306;dbname=zerog';

			/**
			 * Database user
			 */
			$this->configData['db/user'] = 'root';

			/**
			 * Database password
			 */
			$this->configData['db/password'] = '';
		}

		public function getData($name)
		{
			return $this->configData[$name];
		}
	}
}
