<?php

/**
 * Base class used for sessions
 * All session data is accessible through $this->_data or $this->getData()/setData()
 *
 * @author radu.mogos
 */

namespace App\Code\Core\ZeroG\Core\Model
{	
	class Session extends \Sys\Model
	{
		// XML configuration data
		const XML_SESSION_PATH          = 'config/global/default/session/path';
		const XML_SESSION_METHOD        = 'config/global/default/session/method';
		const XML_SESSION_CACHE_LIMITER = 'config/global/default/session/cache_limiter';

		/**
		 * Session name
		 *
		 * @var <string>
		 */
		protected $_sessionName;

		/**
		 * Session namespace
		 *
		 * @var <string>
		 */
		protected $_namespace;

		/**
		 * Create the session
		 */
		public function __construct()
		{
			$this->_construct();
		}

		/**
		 * Protected _constructor
		 */
		protected function _construct()
		{
			//parent::_construct();
		}

		/**
		 * Return the core cookie
		 *
		 * @return <type>
		 */
		public function getCookie()
		{
			return \Z::getSingleton('core/cookie');
		}

		/**
		 * Start the session
		 *
		 * @param <string> $sessionName
		 * @return Session
		 */
		public function start($sessionName = null)
		{
			// if session already exists, return the current object
			if (isset($_SESSION)) {
				return $this;
			}

			switch ($this->getSessionSaveMethod())
			{
				case 'memcache':
					ini_set('session.save_handler', 'memcache');
					session_save_path($this->getSessionSavePath());
					break;
				case 'eaccelerator':
					ini_set('session.save_handler', 'eaccelerator');
					break;
				default:
					session_module_name($this->getSessionSaveMethod());
					if (is_writable($this->getSessionSavePath()))
					{
						session_save_path($this->getSessionSavePath());
					}
					break;
			}
			
			$cookie = $this->getCookie();

			/*
			$cookieParams = array(
				'lifetime' => $cookie->getLifetime(),
				'path'     => $cookie->getPath(),
				'domain'   => $cookie->getConfigDomain(),
				'secure'   => $cookie->isSecure(),
				'httponly' => $cookie->getHttponly()
			);
			
			if (!$cookieParams['httponly']) 
			{
				unset($cookieParams['httponly']);
				if (!$cookieParams['secure'])
				{
					unset($cookieParams['secure']);
					if (!$cookieParams['domain'])
			 		{
						unset($cookieParams['domain']);
					}
				}
			}

			if (isset($cookieParams['domain']))
			{
				$cookieParams['domain'] = $cookie->getDomain();
			}

			call_user_func_array('session_set_cookie_params', $cookieParams);
			 */

			if (!empty($sessionName))
			{
				$this->setSessionName($sessionName);
			}

			$this->setSessionId();

			$sessionCacheLimiter = \Z::getConfig(self::XML_SESSION_CACHE_LIMITER);
			if ($sessionCacheLimiter) {
				session_cache_limiter((string)$sessionCacheLimiter);
			}

			session_start();

			return $this;
		}

		/**
		 * Initialize session
		 *
		 * @param <string> $namespace Session namespace
		 * @param <string> $sessionName Session name
		 * @return Session
		 */
		public function init($namespace, $sessionName=null)
		{
			if (!isset($_SESSION)) {
				$this->start($sessionName);
			}
			if (!isset($_SESSION[$namespace])) {
				$_SESSION[$namespace] = array();
			}

			$this->_data = &$_SESSION[$namespace];

			//$this->validate();
			//$this->revalidateCookie();

			return $this;
		}

		/**
		 * Set current session name
		 *
		 * @param <string> $name
		 * @return Session
		 */
		public function setSessionName($name)
		{
			session_name($name);
			return $this;
		}

		/**
		 * Return current session name
		 *
		 * @return <string>
		 */
		public function getSessionName()
		{
			return session_name();
		}

		/**
		 * Set session id
		 *
		 * @param <string> $id
		 * @return Session
		 */
		public function setSessionId($id=null)
		{
			if (!is_null($id) && preg_match('#^[0-9a-zA-Z,-]+$#', $id)) {
				session_id($id);
			}
			return $this;
		}

		/**
		 * Current session save method
		 *
		 * @return <string>
		 */
		public function getSessionSaveMethod()
		{
			return \Z::getConfig(self::XML_SESSION_METHOD);
		}

		/**
		 * Return the session save path
		 *
		 * @return <string>
		 */
		public function getSessionSavePath()
		{
			return \Z::getConfig(self::XML_SESSION_PATH);
		}

		/**
		 * Get the session form key
		 *
		 * @return <string>
		 */
		public function getFormKey()
		{
			if (!$this->getData('_form_key'))
			{
				$this->setData('_form_key', 'TEST');
			}
			return $this->getFormKey();
		}
	}
}
