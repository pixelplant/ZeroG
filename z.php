<?php
/**
 * Main class of the framework. Takes care of bootstraping, routing and models loading.
 * User: radu.mogos
 * Date: 24.10.2010
 * Time: 00:36:02
 */

namespace
{

	class Z
	{
		/**
		 * The current running ZeroG Instance
		 * @var <\Sys\ZeroG>
		 */
		private static $instance = NULL;

		/**
		 * Holds an array of various singleton classes
		 * @var <array>
		 */
		private static $singletons = array();

		/**
		 * The GET and POST parameters combined
		 * @var <array>
		 */
		private static $params = array();

		/**
		 * The current context ZeroG is running in. Eg: cms/index runs in cms_index
		 * context, so the cms_index tag will be processed from the layout xml file.
		 * Basically this is just a shortcut to the specific tag in the layout that
		 * should be processed
		 * @var <string>
		 */
		private static $context = '';

		/**
		 * Reference to the Localization/Translation class
		 * @var <\Sys\L10n\Locale>
		 */
		private static $locale;

		/**
		 * A shortcut to the Profiler class
		 * @var <\Sys\Profiler>
		 */
		private static $profiler;

		/**
		 * A shortcut to the Configuration class
		 * @var <App\Config\System>
		 */
		private static $config;

		/**
		 * The internal registry holding array of mixed types
		 * 
		 * @var <array>
		 */
		private static $registry;

		/**
		 * Current running module name
		 *
		 * @var <string>
		 */
		private static $module;

		/**
		 * Current running controller name
		 * @var <string>
		 */
		private static $controller;

		/**
		 * Current running action name
		 * @var <string>
		 */
		private static $action;

		/**
		 * Start up ZeroG, load the Localization class, store the REQUEST
		 * parameters and configure URL rewrites
		 * @return <void>
		 */
		final public static function run()
		{
			if (self::$instance !== NULL)
				return;

			// generate the singleton
			self::$instance = self::getInstance();

			// initialize the registry
			self::resetRegistry();

			// start global profiler timer
			self::getProfiler()->start('timer/global');

			// load config data + execute router
			self::$config = self::getSingleton('Sys\\Config');

			// get request parameters
			self::$params = self::getConfig()->getRouter()->getParams();
			if (self::$params === NULL)
				throw new \Sys\Exception('No map matched the current route');

			// load locale settings and labels
			self::$locale = self::getSingleton('Sys\\L10n\\Locale', self::getParam('locale', self::getConfig('config/global/default/locale')));

			// run the application
			self::bootstrap();

			// end global profiler timer
			self::getProfiler()->stop('timer/global');
		}

		/**
		 * Bootstraps the application, meaning it creates the current controller and then calls the specified action
		 *
		 * @static
		 * @return <void>
		 */
		final public static function bootstrap()
		{
			$module = self::getParam('module');
			$controller = self::getParam('controller');
			$action = self::getParam('action');
			// register also the module, so we can attach it to the modules config class
			self::register('current_module', self::getConfig()->getModule($module));
			self::$context = self::registry('current_module')->getRouterName().'_'.$controller.'_'.$action;
			self::$module = $module;
			self::$controller = $controller;
			$action .= 'Action';
			self::$action = $action;
			$className = self::getConfig()->getModule($module)->getControllerClass($controller);
			//$className = 'App\\Controllers\\'.ucfirst($controller);
			$class = new $className;
			// we set as function parameters only the paramters starting from
			// index 2. we already use the 'controller' and 'action' parameters
			// so the other parameters are set as function calls
			//$class->$action(self::getParams(2));
			$class->$action(self::getParams());
		}

		/**
		 * Returns a GET or POST request variable
		 * 
		 * @static
		 * @param <string> $key name of the request variable (from the POST or GET array)
		 * @param <bool> $clean wether we want a htmlspecialcharsed value or not
		 * @return <string> the request variable specified by key, or NULL if it does not exist
		 */
		public static function getRequest($key, $clean = FALSE)
		{
			if (array_key_exists($key, self::$params['request']))
			{
				if ($clean === TRUE)
					return htmlspecialchars(self::$params['request'][$key]);
				else
					return self::$params['request'][$key];
			}
			else
				return NULL;
		}

		/**
		 * Returns the $params array, meaning all GET and POST variables
		 * 
		 * @param <integer> $index starting index of the array to return
		 * @return <array> $_GET parameters, spliced or not
		 */
		public static function getParams($index = 0)
		{
			if ($index > 0)
				return array_slice(self::$params['request'], $index);
			return self::$params['request'];
		}

		/**
		 * Returns a REQUEST parameter specified by Key
		 *
		 * @param <type> $key The parameter to return
		 * @param <type> $defaultValue The default value to set if the REQUEST parameter has no value
		 * @param <type> $clean Set to TRUE to XSS clean the returned value
		 * @return <mixed> parameter[$key]
		 */
		public static function getParam($key, $defaultValue = '', $clean = FALSE)
		{
			$processedParam = self::getRequest($key, $clean);
			if ($processedParam === NULL || $processedParam == '')
				return $defaultValue;
			else
				return $processedParam;
		}

		/**
		 * Context parameters are read from app/config/routes.xml
		 *
		 * @return <array>
		 */
		public static function getContextParams()
		{
			return self::$config->getRouter()->getContextParams();
		}

		/**
		 * Return the cached context name
		 *
		 * @return <string>
		 */
		public static function getContext()
		{
			return self::$context;
		}

		/**
		 * Return the profiler
		 *
		 * @return <\Sys\Profiler>
		 */
		public static function getProfiler()
		{
			if (!array_key_exists('Sys\\Profiler', self::$singletons))
			{
				self::$singletons['Sys\\Profiler'] = new \Sys\Profiler();
				self::$profiler = self::$singletons['Sys\\Profiler'];
			}
			return self::$profiler;
		}

		/**
		 * Execute a controller. The first name is the controller, separated by a
		 * slash, and then followed by the action name to perform.
		 * Eg: callController('blog/list') would return the data for the blog
		 * controller, and the list action.
		 *
		 * @static
		 * @param <string> $controller Controller/Action name
		 */
		public static function callController($controller)
		{
			$parameters = explode('/', $controller);
			$extension = $parameters[0];
			$controller = $parameters[1];
			$action = $parameters[2];
			$className = ucfirst(self::getConfig('ext/dir')).'\\'.ucfirst($controller)."\\Controllers\\".ucfirst($controller);
			$class = new $className;
			$class->$action();
		}

		/**
		 * Return the current ZeroG instance
		 * @return <\Sys\ZeroG> ZeroG instance
		 */
		final public static function getInstance()
		{
			if (self::$instance === NULL)
			{
				self::$instance = new Z();
				//self::init();
			}
			return self::$instance;
		}

		/**
		 * Return a singleton of a certain class
		 * 
		 * @param <type> $class Which class to return
		 * @param <type> $classParams The parameters sent to the class, in case of initialization
		 * @return class 
		 */
		public static function getSingleton($class, $classParams = '')
		{
			//static $singletons = array();
			if (!array_key_exists($class, self::$singletons)) {
				//$serializedFile = 'var/cache/serialized/'.md5($class).'.ser';
				self::getProfiler()->start($class);
				//if (!file_exists($serializedFile))
				//{
					self::$singletons[$class] = new $class($classParams);
				//	file_put_contents($serializedFile, serialize(self::$singletons[$class]));
				//}
				//else
				//	self::$singletons[$class] = unserialize(file_get_contents($serializedFile));
				self::getProfiler()->stop($class);
			}
			return self::$singletons[$class];
		}

		public static function getResource($resourceName)
		{
			return self::getSingleton($resourceName);
		}

		/**
		 * Get the current controller name
		 * @return <string>
		 */
		public static function getController()
		{
			return self::$controller;
		}

		/**
		 * Get the current action name
		 * @return <string>
		 */
		public static function getAction()
		{
			return self::$action;
		}

		/**
		 * Store a value in the registry
		 *
		 * @param <string> $name
		 * @param <mixed> $value
		 */
		public static function register($name, $value)
		{
			self::$registry[$name] = $value;
		}

		/**
		 * Return a value from the registry
		 *
		 * @param <string> $name
		 * @return <mixed>
		 */
		public static function registry($name)
		{
			if (isset(self::$registry[$name]))
				return self::$registry[$name];
			return FALSE;
		}

		/**
		 * Resets the registry records
		 */
		public static function resetRegistry()
		{
			self::$registry = array();
		}

		/**
		 * Prevent cloning
		 */
		final private function __clone() {}

		// --- shortcut helpers ---
		// eg: L is a shortcut to the locale, for translations, currency, etc

		/**
		 * Returns a current helper class (if it does not exist, it creates it
		 * as a singleton)
		 *
		 * @param <string> $name
		 * @return <mixed>
		 */
		public static function getHelper($name)
		{
			if (substr($name, 0, 4) == "Sys\\")
				return self::getSingleton($name);
			return self::getSingleton(self::$config->getHelperClass($name));
		}
		
		/**
		 * Returns a model class by identifier
		 *
		 * @param <string> $name
		 * @return <mixed>
		 */
		public static function getModel($name)
		{
			return self::getSingleton(self::$config->getModelClass($name));
		}

		/**
		 * Return the value of a config variable
		 *
		 * @param <string> $variable
		 * @return <mixed>
		 */
		public static function getConfig($variable = NULL)
		{
			return self::$config->getConfig($variable);
		}


		/**
		 * Returns a reference to the current locale
		 *
		 * @see L()
		 *
		 * @return <\Sys\L10n\Locale>
		 */
		public static function getLocale()
		{
			return self::$locale;
		}

		/**
		 * Returns a reference to the current locale class (short version)
		 *
		 * @see getLocale()
		 *
		 * @return <\Sys\L10n\Locale> Reference to Locale class
		 */
		public static function L()
		{
			return self::$locale;
		}

		/**
		 * Return a translated label, based on the current locale
		 *
		 * @param <string> $label The label to translate, in english
		 * @param <string> $module The module the label belongs to
		 * @return <string> The translated label
		 */
		public static function __($label, $module = 'global')
		{
			return self::$locale->__($label, $module);
		}

	}
}
