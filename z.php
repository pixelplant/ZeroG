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
		 * The library router
		 * @var <\Sys\Router>
		 */
		private static $router;

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

			/*
			* Autoload settings. By default every namespace/class path links directly
			* to the folder/file path for each class. Which it should, like every other
			* normal programming language.
			*/
			\spl_autoload_extensions('.php');
			\spl_autoload_register();

			// generate the singleton
			self::$instance = self::getInstance();

			// start global profiler timer
			self::getProfiler()->start('timer/global');

			// load config data
			self::$config = self::getSingleton('Sys\\Config\\System');

			self::$router = self::getSingleton('Sys\\Config\\Router');
			self::$router->execute();

			// get request parameters
			self::$params = self::$router->getParams();
			if (self::$params === NULL)
				throw new \Sys\Exception('No map matched the current route');

			// load locale settings and labels
			self::$locale = self::getSingleton('Sys\\L10n\\Locale', self::getParam('locale', self::getConfig('locale')));

			// run the application
			self::bootstrap();

			// end global profiler timer
			self::getProfiler()->stop('timer/global');
		}

		/**
		 * Bootstraps the application, meaning it creates the current controller and then calls the specified action
		 * @static
		 * @return <void>
		 */
		final public static function bootstrap()
		{
			$controller = self::getParam('controller');
			$action = self::getParam('action');
			self::$controller = $controller;
			self::$action = $action;
			self::$context = $controller.'_'.$action;
			$className = ucfirst(self::getConfig('app/dir')).'\\Controllers\\'.ucfirst($controller);
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
			return self::$params['context'];
		}

		/**
		 * Return the cached context name
		 * @return <string>
		 */
		public static function getContext()
		{
			return self::$context;
		}

		/**
		 * Return the profiler
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
		 * Returns a Model class instance
		 * @static
		 * @param  <string> $model the models name, eg: 'profiles/user' loads the User class from the 'models/profiles' location
		 * @return <Model> A Model derived class
		 */
		public static function getModel($model)
		{
			$model = strtolower($model);
			$is_extension = FALSE;
			$directory = self::getConfig('app/dir');
			// extension models start with 'ext/' so we need to load
			// them from the extension directory
			if (substr($model, 0, 4) == 'ext/')
			{
				$model = substr($model, 4);
				$is_extension = TRUE;
				$directory = self::getConfig('ext/dir');
			}
			$table = str_replace("/", "_", $model);
			if ($is_extension)
			{
				$parts = explode('_', $table);
				$modelName = ucfirst($directory)."\\".ucfirst($parts[0])."\\Models\\".ucfirst($parts[1]);
			}
			else
			{
				$model = str_replace("/", "\\", ucwords($model));
				$modelName = ucfirst($directory)."\\Models\\".$model;
			}
			$class = new $modelName($table);
			return $class;
		}

		public static function getExtension($name)
		{
			$parts = explode("/", $name);
			$table = str_replace("/", "_", strtolower($name));
			$modelName = ucfirst(self::getConfig('ext/dir')).'\\'.ucfirst($parts[0]).'\\Models\\'.ucfirst($parts[1]);
			$class = new $modelName($table);
			return $class;
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
			return self::getSingleton($name);
		}

		/**
		 * Return the value of a config variable
		 * @param <string> $variable
		 * @return <mixed>
		 */
		public static function getConfig($variable)
		{
			return self::$config->getData($variable);
		}


		/**
		 * Returns a reference to the current locale class
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
