<?php

/**
 * Description of cookie
 *
 * @author radu.mogos
 */

namespace App\Code\Core\ZeroG\Core\Model
{
	class Cookie extends \Sys\Model
	{
		// Cookie XML variables
		const XML_COOKIE_LIFETIME = 'config/global/default/cookie/lifetime';
		const XML_COOKIE_PATH     = 'config/global/default/cookie/path';
		const XML_COOKIE_DOMAIN   = 'config/global/default/cookie/domain';

		/**
		 * Cookie lifetime in seconds
		 *
		 * @var <int>
		 */
		protected $_lifetime = null;

		/**
		 * Return Cookie lifetime in seconds
		 *
		 * @return int
		 */
		public function getLifetime()
		{
			if (!is_null($this->_lifetime))
			{
				$lifetime = $this->_lifetime;
			}
			else
			{
				$lifetime = \Z::getConfig(self::XML_COOKIE_LIFETIME);
			}
			if (!is_numeric($lifetime))
			{
				$lifetime = 3600;
			}
			return $lifetime;
		}

		/**
		 * Set cookie lifetime
		 *
		 * @param <int> $lifetime Cookie lifetime in seconds
		 * @return Cookie
		 */
		public function setLifetime($lifetime)
		{
			$this->_lifetime = $lifetime;
			return $this;
		}

		/**
		 * Returns the Cookie Path
		 *
		 * @return string
		 */
		public function getPath()
		{
			$path = \Z::getConfig(self::XML_COOKIE_PATH);
			if (empty($path))
			{
				$path = '/';
			}
			return $path;
		}

		/**
		 * Get the Cookie domain
		 *
		 * @return <string>
		 */
		public function getDomain()
		{
			$domain = \Z::getConfig(self::XML_COOKIE_DOMAIN);
			if (empty($domain))
			{
				$domain = \Z::getRequest()->getHost();
			}
			return $domain;
		}

		/**
		 * Is the cookie used for https only or both?
		 * @return <bool>
		 */
		public function getSecure()
		{
			// !!!TODO
			// Get the is_secure flag for the current store
			return 0;
		}

		/**
		 * Create the cookie
		 *
		 * @param <string> $name
		 * @param <string> $value
		 * @param <string> $expire
		 * @param <string> $path
		 * @param <string> $domain
		 * @param <string> $secure
		 */
		public function set($name, $value, $expire = null, $path = null, $domain = null, $secure = null)
		{
			if ($expire > 0 || is_null($expire))
			{
				$expire = time() + $this->getLifetime();
			}
			if (is_null($path))
			{
				$path = $this->getPath();
			}
			if (is_null($domain))
			{
				$domain = $this->getDomain();
			}
			if (is_null($secure))
			{
				$secure = $this->getSecure();
			}
			setcookie($name, $value, $expire, $path, $domain, $secure);
		}

		/**
		 * Delete the Cookie
		 *
		 * @param <string> $name
		 * @param <string> $path
		 * @param <string> $domain
		 * @param <string> $secure
		 */
		public function delete($name, $path = null, $domain = null, $secure = null)
		{
			if (is_null($path))
			{
				$path = $this->getPath();
			}
			if (is_null($domain))
			{
				$domain = $this->getDomain();
			}
			if (is_null($secure))
			{
				$secure = $this->getSecure();
			}
			setcookie($name, null, null, $path, $domain, $secure);
		}
	}
}
