<?php

namespace App\Code\Core\ZeroG\User\Models
{
	/**
	 * This is the base USER session model
	 *
	 * @author radu.mogos
	 */
	class Session extends \Sys\Session
	{
		/**
		 * Holds the user's data
		 * @var <App\Code\Core\ZeroG\User\Models\User> 
		 */
		protected $_user;

		/**
		 * Instantiate the user session
		 */
		public function __construct()
		{
			$this->init('user');
			\Z::dispatchEvent('user_session_init', array('object' => $this));
		}

		/**
		 * Sets the current session user
		 * @param App\Code\Core\ZeroG\User\Models\User $user
		 */
		public function setUser(App\Code\Core\ZeroG\User\Models\User $user)
		{
			$this->_user = $user;
			$this->setId($user->getId());
			return $this;
		}

		/**
		 * Return the current user
		 *
		 * @return App\Code\Core\ZeroG\User\Models\User
		 */
		public function getUser()
		{
			if ($this->_user instanceof App\Code\Core\ZeroG\User\Models\User)
				return $this->_user;
		}

		/**
		 * Check to see if user is logged in
		 * 
		 * @return <bool>
		 */
		public function isLoggedIn()
		{
			return (bool)$this->getId() && (bool)$this->checkUserId($this->getId());
		}

		/**
		 * Logout user
		 *
		 * @return Session
		 */
		public function logout()
		{
			if ($this->isLoggedIn())
			{
				\Z::dispatchEvent('user_logout', array('object' => $this->getUser()));
				$this->setId(null);
			}
			return $this;
		}
	}
}
