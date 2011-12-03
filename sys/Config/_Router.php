<?php

namespace Sys\Config
{
	use \Sys\Config\Router\Route;

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
		 * Reference to the main config
		 *
		 * @var <\Sys\Config>
		 */
		private $config;

		/**
		 * Constructor
		 */
		public function __construct($config)
		{
			$request = isset($_GET['path']) ? $_GET['path'] : '/';

			$this->config = $config;
			$this->requestUri = $request;
			$this->routes = array();
		}

		private function getPath()
		{
			return 'app'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR;
		}

		/**
		 * Load the rules from routes.xml
		 */
		/*public function loadRules()
		{
			$xml = new \SimpleXMLElement($this->getPath().$this->file, NULL, TRUE);
			if (!$xml)
				throw new \Sys\Exception("Current router file ".$this->getPath().$this->file." does not exist or is not a valid xml");

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

			// set the context variables, used for the URI prevariable data
			foreach ($xml->context->children() as $key => $context)
			{
				foreach ($context->translate as $translate)
				{
					$this->params['context'][(string)$key][(string)$translate['from']] = (string)$translate['to'];
				}
			}
		}*/

		/**
		 * Load all the routing rules specified in the xml files
		 * 
		 * @param <array> $routes
		 */
		public function loadRules($routes)
		{
			// load the custom rules specified per module
			foreach ($routes as $route)
			{
				$toArray = $route['to'];
				$routeTo = array();
				foreach ($toArray as $key => $value)
				{
					$routeTo[(string)$key] = (string)$value;
				}

				//$fromArray = $route['from'];
				$fromArray = array();
				$fromArray[] = $route['from'];
				$fromArray[] = $route['from'].'/:controller';
				$fromArray[] = $route['from'].'/:controller/:action';
				$routeFrom = array();
				if (is_array($fromArray))
					foreach ($fromArray as $from)
					{
						$this->map((string)$from, $routeTo);
					}
				else
					$this->map((string)$fromArray, $routeTo);
			}
			// then load the default rules, which have the lowest priority
			$this->defaultRoutes();
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
			$this->map('/');
			$this->map(':router');
			$this->map(':router/:controller');
			$this->map(':router/:controller/:action');
		}

		/**
		 * Setup a \Sys\Routes\Route
		 * @param <\Sys\Routes\Route> $route
		 */
		private function setRoute($route)
		{
			$this->routeFound = true;
			$params = $route->getParams();
			if (empty($params['router']))
				$params['router'] = $this->config->getConfig('config/global/default/router');
			if (empty($params['module']))
				$params['module'] = $this->config->getCurrentModule($params['router']);
			if (empty($params['controller']))
				$params['controller'] = $this->config->getConfig('config/global/default/controller');
			$this->controller = $params['controller'];
			if (empty($params['action']))
				$params['action'] = $this->config->getConfig('config/global/default/action');
			$this->action = $params['action'];
			$this->params['request'] = array_merge($params, $_GET);

			// transforms a controller named "do_something_evil" into "doSomethingEvil"
			// so we can easily call the action "doSomethingEvilAction"
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

		/**
		 * To be defined
		 */
		public function getContextParams()
		{
			return array('locale' => array('fr_FR' => 'fr_FR', 'ro_RO' => 'ro'));
		}
	}
}
