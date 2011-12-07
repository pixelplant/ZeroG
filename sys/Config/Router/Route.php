<?php

namespace Sys\Config\Router
{
	/**
	 * Route class for the ZeroG router
	 * Based mostly on Dan's router:
	 * http://blog.sosedoff.com/2009/09/20/rails-like-php-url-router/
	 */
	class Route
	{
		/**
		 * Wether this route matches the current uri request
		 * @var <bool>
		 */
		protected $isMatched = false;

		/**
		 * Route parameters
		 * @var <array>
		 */
		protected $params;

		/**
		 * Route url (eg: '/ro/:controller/:action')
		 * @var <string>
		 */
		protected $url;

		/**
		 * Routing conditions
		 * @var <array>
		 */
		private $conditions;

		function __construct($url, $request_uri, $target, $conditions)
		{
			$this->url = $url;
			$this->params = array();
			$this->conditions = $conditions;
			$p_names = array();
			$p_values = array();

			preg_match_all('@:([\w]+)@', $url, $p_names, PREG_PATTERN_ORDER);
			$p_names = $p_names[0];

			$url_regex = preg_replace_callback('@:[\w]+@', array($this, 'regex_url'), $url);
			$url_regex .= '/?';

			if (preg_match('@^' . $url_regex . '$@', $request_uri, $p_values))
			{
				array_shift($p_values);
				foreach($p_names as $index => $value)
					$this->params[substr($value,1)] = urldecode($p_values[$index]);
				foreach($target as $key => $value)
					$this->params[$key] = $value;
				$this->isMatched = true;
			}
			unset($p_names); unset($p_values);
		}

		function regex_url($matches)
		{
			$key = str_replace(':', '', $matches[0]);
			if (array_key_exists($key, $this->conditions))
			{
				return '('.$this->conditions[$key].')';
			}
			else
			{
				return '([a-zA-Z0-9_\+\-%]+)';
			}
		}

		/**
		 * Return this route's parameters
		 * @return <array>
		 */
		public function getParams()
		{
			return $this->params;
		}

		/**
		 * Did we find a matching route?
		 * @return <bool>
		 */
		public function getIsMatched()
		{
			return $this->isMatched;
		}
	}
}
