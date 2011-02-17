<?php

namespace App\Config
{
	final class System
	{
		/**
		 * Defines the location of the application directory, for the custom models, views controllers of this app
		 */
		const APP_DIR = 'app';

		/**
		 * Defines the location of the extensions used by the application
		 */
		const EXT_DIR = 'ext';

		/**
		 * Defines the base url, used for link generation + css and js linking
		 * All content images (besides images used in the css) will be linked to
		 * MEDIA_URL/MEDIA_URL2 below
		 */
		const BASE_URL = 'http://local/zerog/';

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
		const MEDIA_URL = self::BASE_URL;

		/**
		 * The second link to a hostname storing your content (images, pdf, etc)
		 * By default both links point to the base url, but you can change them
		 * to a real location
		 *
		 */
		const MEDIAL_URL2 = self::BASE_URL;

		/**
		 * enables nice urls or not. if set to TRUE, the mod_rewrite module
		 * must be also loaded in Apache.
		 */
		const URL_REWRITE = TRUE;

		/**
		 * The default controller used when nothing is specified in the url
		 */
		const DEFAULT_CONTROLLER = 'cms';

		/**
		 * The default action called when no action is specified for the controller
		 */
		const DEFAULT_ACTION = 'index';

		/**
		 * Database DSN connection
		 */
		const DB_DSN = 'mysql:host=localhost;port=3306;dbname=zerog';

		/**
		 * Database user
		 */
		const DB_USER = 'root';

		/**
		 * Database password
		 */
		const DB_PASS = '';

		/**
		 * Locale settings
		 * To change the settings for the current locale open the coresponding
		 * xx_XX.xml file in sys\locale. Make sure you save the file as UTF-8
		 */
		const LOCALE = 'ro_RO';

		/**
		 * Developer mode disables all caching. When you are ready for production
		 * set this field to FALSE!
		 */
		const DEVELOPER_MODE = FALSE;
	}
}
