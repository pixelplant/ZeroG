<?php

namespace Sys\Config
{

	/**
	 * Router class for ZeroG
	 */
	class Router
	{
		const XML_REWRITE_ENABLED = 'config/global/default/url/rewrite';
		const XML_DEFAULT_ROUTER = 'config/global/default/router';
		const XML_DEFAULT_CONTROLLER = 'config/global/default/controller';
		const XML_DEFAULT_ACTION = 'config/global/default/action';
		/**
		 * The request uri ($_GET['path'])
		 * @var <string>
		 */
		protected $_requestUri;

		/**
		 * The initial URI, before any processing
		 * @var <string>
		 */
		protected $_originalRequestUri;

		/**
		 * The controller of the executed route
		 * @var <string>
		 */
		protected $_controller;

		/**
		 * Camelcase controller name
		 * @var <string>
		 */
		protected $_controllerName;

		/**
		 * The action of the executed route
		 * @var <string>
		 */
		protected $_action;

		/**
		 * Parameters of the matched route
		 * @var <array>
		 */
		protected $_params;

		/**
		 * Array holding all routes found in xml files
		 *
		 * @var <array>
		 */
		protected $_routes;

		/**
		 * Did we find a matching route or not?
		 * @var <bool>
		 */
		protected $_routeFound = false;

		/**
		 * Reference to the main config
		 *
		 * @var <\Sys\Config>
		 */
		private $_config;

		/**
		 * Constructor
		 */
		public function __construct()
		{
			//$request = isset($_GET['path']) ? $_GET['path'] : '/';

			$this->_config = \Z::getConfig();
			$this->_requestUri = $this->getRequestUri();
			$this->_routes = array();
		}

		public function getRequestUri()
		{
			$requestUri = FALSE;
			if (isset($_SERVER['REQUEST_URI']))
			{
				$requestUri = $_SERVER['REQUEST_URI'];
			}
			elseif (isset($_SERVER['ORIG_PATH_INFO']))
			{
				$requestUri = $_SERVER['ORIG_PATH_INFO'];
				if (!empty($_SERVER['QUERY_STRING']))
				{
					$requestUri .= '?'.$_SERVER['QUERY_STRING'];
				}
			}
            else if (isset($_SERVER['HTTP_X_REWRITE_URL']))
			{
                $requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
			}

			$host = (empty($_SERVER['HTTP_HOST'])) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
			$scriptFolder = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));

			$fullRequest = 'http://'.$host.$requestUri;
			$baseUrl = 'http://'.$host.$scriptFolder.'/';

			$requestUri = substr($fullRequest, strlen($baseUrl));

			return $requestUri;
		}

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
				$currentRoute = (string)$route['use'];
				foreach ($route['args'] as $key => $value)
				{
					$this->_routes[$currentRoute][(string)$key] = (string)$value;
				}
			}
		}

		/**
		 * Setup router parameters
		 */
		private function setRouterParams()
		{
			$this->_originalRequestUri = $this->_requestUri;
			if ($this->_config->getConfig(self::XML_REWRITE_ENABLED) == 1)
			{
				$this->checkRewriteUrlFirst();
				$regularUri = $this->_requestUri;
				$queryPos = strpos($this->_requestUri, '?');
				if ($queryPos !== FALSE)
					$regularUri = substr($this->_requestUri, 0, $queryPos);
				$parts = explode('/', $regularUri);
				$params = array(
					'router'      => !empty($parts[0]) ? $parts[0] : $this->_config->getConfig(self::XML_DEFAULT_ROUTER),
					'controller'  => !empty($parts[1]) ? $parts[1] : $this->_config->getConfig(self::XML_DEFAULT_CONTROLLER),
					'action'      => !empty($parts[2]) ? $parts[2] : $this->_config->getConfig(self::XML_DEFAULT_ACTION),
					'_requestUri' => $this->_requestUri,
					'_originalRequestUri' => $this->_originalRequestUri,
					);
				$params['module'] = $this->_config->getCurrentModule($params['router']);
				if (sizeof($parts) > 3)
					for ($i = 3; $i < sizeof($parts); $i+=2)
					{
						$key = $parts[$i];
						if (isset($parts[$i + 1]))
							$params[$key] = $parts[$i + 1];
						else
							$params[$key] = '';
					}
			}

			$this->_controller = $params['controller'];
			$this->_action = $params['action'];

			// merge the GET and POST variables
			$this->_params['request'] = array_merge($params, $_GET, $_POST);

			// transforms a controller named "do_something_evil" into "doSomethingEvil"
			// so we can easily call the action "doSomethingEvilAction"
			$w = explode('_', $this->_controller);
			foreach($w as $k => $v)
				$w[$k] = ucfirst($v);
			$this->_controllerName = implode('', $w);
		}

		/**
		 * Executes the found route
		 */
		public function execute()
		{
			$this->loadRules($this->_config->getRouterXmlData());
			$this->setRouterParams();
			$router = $this->_params['request']['router'];
			if (isset($this->_routes[$router]))
			{
				// load this router's settings, if any
				$this->_params['request'] = array_merge($this->_params['request'], $this->_routes[$router]);
			}
			else
				throw new \Sys\Exception('The called router => %s could not be found.
					Either it is not defined or you mistyped it\'s name',
						$router);
		}

		/**
		 * Return the route parameters
		 * @return <array>
		 */
		public function getParams($type = 'request')
		{
			return $this->_params[$type];
		}

		/**
		 * Loads a rewrite rule first, if one is defined
		 * @return <string>
		 */
		public function checkRewriteUrlFirst()
		{
			if ($this->_requestUri == '')
				return;
			$url = \Z::getModel('core/url/rewrite')->loadByUrl($this->_requestUri);
			if ($url->getTargetPath())
				$this->_requestUri = $url->getTargetPath();
		}

		/**
		 * Returns the original unaltered URI
		 */
		public function getOriginalRequestUri()
		{
			return $this->_originalRequestUri;
		}
	}
}
