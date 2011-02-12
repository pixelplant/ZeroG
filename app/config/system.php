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
		 * Defines the base url, used for link generation
		 */
		const BASE_URL = 'http://local/zerog/';

		/**
		 * You can define a separate url from where images, pdf filed and other
		 * editor specific files would be retrieved from
		 */
		const MEDIA_URL = self::BASE_URL;

		/**
		 * This url links to the public folder, where your css, js and editor files
		 * will be hosted
		 */
		const PUBLIC_URL = 'http://local/zerog/public/';

		/**
		 * enables nice urls or not. if set to TRUE, mod_rewrite must be also loaded in Apache.
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
		 * Localization settings
		 */
		//const LANGUAGE = 'FR';

		/**
		 * Locale settings
		 */
		const LOCALE = 'ro_RO';

		/**
		 * Developer mode disables all caching. When you are ready for production
		 * set this field to FALSE!
		 */
		const DEVELOPER_MODE = TRUE;
	}
}
