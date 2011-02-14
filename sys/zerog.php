<?php
/**
 * Main class of the framework. Takes care of bootstraping, routing and models loading.
 * User: radu.mogos
 * Date: 24.10.2010
 * Time: 00:36:02
 */

namespace Sys
{

	final class ZeroG
	{

		protected static $instance = NULL;

		protected static $params = array();

		const LOCALE = 'Sys\L10n\Locale';

		const PROFILER = 'Sys\Profiler';

		public static function init()
		{
			if (self::$instance === NULL)
				self::$instance = new ZeroG();
			else
				return self::$instance;
			// load locale settings and labels
			self::getModuleInstance(self::LOCALE, \App\Config\System::LOCALE);
			// get request parameters
			self::$params = array_merge($_GET, $_POST);
			// start url rewriting/routing
			self::getUrlRewrites(\App\Config\System::URL_REWRITE);
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

		public static function getParam($key, $defaultValue = '', $clean = FALSE)
		{
			$processedParam = self::getRequest($key, $clean);
			if ($processedParam === NULL)
				return $defaultValue;
			else
				return $processedParam;
		}

		/**
		 * Bootstraps the application, meaning it creates the current controller and then calls the specified action
		 * @static
		 * @return <void>
		 */
		public static function bootstrap()
		{
			$controller = self::getParam('controller', \App\Config\System::DEFAULT_CONTROLLER);
			$action = self::getParam('action', \App\Config\System::DEFAULT_ACTION);
			$className = ucfirst(\App\Config\System::APP_DIR)."\\Controllers\\".ucfirst($controller);
			$class = new $className;
			// we set as function parameters only the paramters starting from
			// index 2. we already use the 'controller' and 'action' parameters
			// so the other parameters are set as function calls
			$class->$action(self::getParams(2));
			//call_user_func_array(array($class, $action), self::getParams(2));
		}

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

		/**
		 * Returns a View class instance
		 * @static
		 * @param <string> $view The view's name and location, eg: 'register/new_user' loads 'new_user.php' from folder 'views/register'
		 * @return <View>
		 */
		public static function getView($view)
		{
			return new View($view);
		}

		public static function getExtension($name)
		{
			$parts = explode("/", $name);
			$table = str_replace("/", "_", strtolower($name));
			$modelName = ucfirst(\App\Config\System::EXT_DIR).'\\'.ucfirst($parts[0]).'\\Models\\'.ucfirst($parts[1]);
			$class = new $modelName($table);
			return $class;
		}

		final public static function getInstance()
		{
			return self::$instance;
		}

		public static function getModuleInstance($class, $classParams = '')
		{
			// hold an array  of every instances...
			static $instances = array();
			if (!array_key_exists($class, $instances)) {
				$instances[$class] = new $class($classParams);
			}
			$instance = $instances[$class];
			return $instance;
		}

		// prevent cloning
		final private function __clone() {}

		// --- shortcut helpers ---
		// eg: L is a shortcut to the locale, for translations, currency, etc

		/**
		 * Returns a link to the current locale class
		 * @return <\Sys\L10n\Locale> Reference to Locale class
		 */
		public static function L()
		{
			return self::getModuleInstance(self::LOCALE);
		}

		/**
		 * Return a translated label, based on the current locale
		 * @param <string> $label The label to translate, in english
		 * @param <string> $module The module the label belongs to
		 * @return <string> The translated label
		 */
		public static function __($label, $module = 'global')
		{
			return self::L()->__($label, $module);
		}

	}
}
