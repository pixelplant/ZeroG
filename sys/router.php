<?php

namespace Sys
{
	use \Sys\Router\Route;

	/**
	 * Router class for ZeroG
	 * Based mostly on Dan's router:
	 * http://blog.sosedoff.com/2009/09/20/rails-like-php-url-router/
	 */
	class Router
	{
		/**
		 * The request uri ($_GET['path'])
		 * @var <string>
		 */
		protected $requestUri;

		/**
		 * An array of \Sys\Router\Route objects
		 * @var <array>
		 */
		protected $routes;

		/**
		 * Thee controller of the executed route
		 * @var <string>
		 */
		protected $controller;

		/**
		 * Camelcase controller name
		 * @var <string>
		 */
		protected $controllerName;

		/**
		 * The action of the executed route
		 * @var <string>
		 */
		protected $action;

		/**
		 * Parameters of the matched route
		 * @var <array>
		 */
		protected $params;

		/**
		 * Did we find a matching route or not?
		 * @var <bool>
		 */
		protected $routeFound = false;

		/**
		 * Constructor
		 */
		public function __construct()
		{
			$request = isset($_GET['path']) ? $_GET['path'] : '/';

			$this->requestUri = $request;
			$this->routes = array();

			// first load the xml rules, which have the highest priority
			$this->loadRules();
			// then load the default rules, which have the lowest priority
			$this->defaultRoutes();
		}

		/**
		 * Load the rules from routes.xml
		 */
		public function loadRules()
		{
			$xml = new \SimpleXMLElement('app/config/routes.xml', NULL, TRUE);

			foreach ($xml->route as $route)
			{
				$toArray = $route->to;
				$routeTo = array();
				foreach ($toArray[0] as $key => $value)
				{
					$routeTo[(string)$key] = (string)$value;
				}

				$fromArray = $route->from;
				$routeFrom = array();
				foreach ($fromArray as $from)
				{
					$this->map((string)$from, $routeTo);
				}
			}
		}

		/**
		 * Map the route to a target
		 * @param <string> $rule
		 * @param <array> $target
		 * @param <array> $conditions
		 */
		public function map($rule, $target=array(), $conditions=array())
		{
			$this->routes[$rule] = new Route($rule, $this->requestUri, $target, $conditions);
		}

		/**
		 * Set the default routes
		 */
		public function defaultRoutes()
		{
			$this->map(':controller');
			$this->map(':controller/:action');
		}

		/**
		 * Setup a \Sys\Routes\Route
		 * @param <\Sys\Routes\Route> $route
		 */
		private function setRoute($route)
		{
			$this->routeFound = true;
			$params = $route->getParams();
			if (empty($params['controller']))
				$params['controller'] = \Z::getConfig('default/controller');
			$this->controller = $params['controller'];
			if (empty($params['action']))
				$params['action'] = \Z::getConfig('default/action');
			$this->action = $params['action'];
			$this->params = array_merge($params, $_GET);

			$w = explode('_', $this->controller);
			foreach($w as $k => $v)
				$w[$k] = ucfirst($v);
			$this->controllerName = implode('', $w);
		}

		/**
		 * Executes all routes until we find a match
		 */
		public function execute()
		{
			foreach($this->routes as $route)
			{
				// set the first route matched
				if ($route->getIsMatched())
				{
					$this->setRoute($route);
					break;
				}
			}
		}

		/**
		 * Return the route parameters
		 * @return <array>
		 */
		public function getParams()
		{
			return $this->params;
		}
	}
}
