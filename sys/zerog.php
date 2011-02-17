<?php
/**
 * Main class of the framework. Takes care of bootstraping, routing and models loading.
 * User: radu.mogos
 * Date: 24.10.2010
 * Time: 00:36:02
 */

namespace Sys
{

	class ZeroG
	{
		/**
		 * The current running ZeroG Instance
		 * @var <\Sys\ZeroG>
		 */
		protected static $instance = NULL;

		/**
		 * Holds an array of various singleton classes
		 * @var <array>
		 */
		protected static $singletons = array();

		/**
		 * The GET and POST parameters combined
		 * @var <array>
		 */
		protected static $params = array();

		/**
		 * The current context ZeroG is running in. Eg: cms/index runs in cms_index
		 * context, so the cms_index tag will be processed from the layout xml file.
		 * Basically this is just a shortcut to the specific tag in the layout that
		 * should be processed
		 * @var <string>
		 */
		protected static $context = '';

		/**
		 * Reference to the Localization/Translation class
		 * @var <\Sys\L10n\Locale>
		 */
		protected static $locale;

		/**
		 * A shortcut to the Profiler class
		 */
		protected static $profiler;

		/**
		 * Start up ZeroG, load the Localization class, store the REQUEST
		 * parameters and configure URL rewrites
		 * @return <void>
		 */
		final public static function init()
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

			// load profiler
			//self::$profiler = self::getSingleton('Sys\\Profiler');

			// start global profiler timer
			self::getProfiler()->start('timer/global');
			
			// load locale settings and labels
			self::$locale = self::getSingleton('Sys\\L10n\\Locale', \App\Config\System::LOCALE);

			// get request parameters
			self::$params = array_merge($_GET, $_POST);
			
			// start url rewriting/routing
			self::getUrlRewrites(\App\Config\System::URL_REWRITE);
		}

		/**
		 * Bootstraps the application, meaning it creates the current controller and then calls the specified action
		 * @static
		 * @return <void>
		 */
		final public static function bootstrap()
		{
			$controller = self::getParam('controller', \App\Config\System::DEFAULT_CONTROLLER);
			$action = self::getParam('action', \App\Config\System::DEFAULT_ACTION);
			self::$context = $controller.'_'.$action;
			$className = ucfirst(\App\Config\System::APP_DIR)."\\Controllers\\".ucfirst($controller);
			$class = new $className;
			// we set as function parameters only the paramters starting from
			// index 2. we already use the 'controller' and 'action' parameters
			// so the other parameters are set as function calls
			$class->$action(self::getParams(2));
			//call_user_func_array(array($class, $action), self::getParams(2));

			// end global profiler timer
			self::getProfiler()->stop('timer/global');
		}

		/**
		 * if url rewriting is enabled, this function translates every variable in the
		 * url string to the proper GET params.
		 * eg: cms/blog/id/3 => controller=cms, action=blog, id=3
		 * @param <boolean> $isUrlRewriteEnbled
		 */
		public static function getUrlRewrites($isUrlRewriteEnbled = FALSE)
		{
			// if there's no url data, then there's nothing to process here...
			if (!isset($_GET['path']) || strlen($_GET['path']) > 250)
				return;
			if ($isUrlRewriteEnbled === TRUE)
			{
				$uri = explode("/", $_GET['path']);
				if (isset($uri[0]))
					self::$params['controller'] = $uri[0];
				if (isset($uri[1]))
					self::$params['action'] = $uri[1];
				$uri = array_slice($uri, 2);
				$i = 0;
				$key = '';
				foreach ($uri as $value)
				{
					$i++;
					if ($i % 2 == 0)
					{
						self::$params[$key] = $value;
					}
					else
						$key = $value;
				}
			}
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
			if (array_key_exists($key, self::$params))
			{
				if ($clean === TRUE)
					return htmlspecialchars(self::$params[$key]);
				else
					return self::$params[$key];
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
				return array_slice(self::$params, $index);
			return self::$params;
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
			if ($processedParam === NULL)
				return $defaultValue;
			else
				return $processedParam;
		}

		/**
		 * Return the cached context name
		 * @return <string>
		 */
		public function getContext()
		{
			return self::$context;
		}

		/**
		 * Return the profiler
		 * @return <\Sys\Profiler>
		 */
		public function getProfiler()
		{
			if (!array_key_exists('\\Sys\\Profiler', self::$singletons))
			{
				self::$singletons['\\Sys\\Profiler'] = new \Sys\Profiler();
				self::$profiler = self::$singletons['\\Sys\\Profiler'];
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
			$className = ucfirst(\App\Config\System::EXT_DIR).'\\'.ucfirst($controller)."\\Controllers\\".ucfirst($controller);
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
			$directory = \App\Config\System::APP_DIR;
			// extension models start with 'ext/' so we need to load
			// them from the extension directory
			if (substr($model, 0, 4) == 'ext/')
			{
				$model = substr($model, 4);
				$is_extension = TRUE;
				$directory = \App\Config\System::EXT_DIR;
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
			$modelName = ucfirst(\App\Config\System::EXT_DIR).'\\'.ucfirst($parts[0]).'\\Models\\'.ucfirst($parts[1]);
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
				self::$instance = new ZeroG();
				//self::init();
			}
			return self::$instance;
		}

		/**
		 * Return a singleton of a certain class
		 * @staticvar array $instances A list of the currently cached modules
		 * @param <type> $class Which class to return
		 * @param <type> $classParams The parameters sent to the class, in case of initialization
		 * @return class 
		 */
		public static function getSingleton($class, $classParams = '')
		{
			// hold an array  of every instances...
			//static $instances = array();
			if (!array_key_exists($class, self::$singletons)) {
				self::getProfiler()->start($class);
				self::$singletons[$class] = new $class($classParams);
				self::getProfiler()->stop($class);
			}
			$instance = self::$singletons[$class];
			return $instance;
		}

		/**
		 * Prevent cloning
		 */
		final private function __clone() {}

		// --- shortcut helpers ---
		// eg: L is a shortcut to the locale, for translations, currency, etc

		/**
		 * Returns a link to the current locale class
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
