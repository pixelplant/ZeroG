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

		public static function init()
		{
			if (self::$instance === NULL)
				self::$instance = new ZeroG();
			if (isset($_GET))
				foreach ($_GET as $key => $value)
					self::$params[$key] = $value;
			if (isset($_POST))
				foreach ($_POST as $key => $value)
					self::$params[$key] = $value;
		}

		/**
		 * Returns a GET or POST request variable
		 * @static
		 * @param  $key name of the request variable (from the POST or GET array)
		 * @param bool $clean wether we want a htmlspecialcharsed value or not
		 * @return string the request variable specified by key, or NULL if it does not exist
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
		 * @static
		 * @return array
		 */
		public static function getParams()
		{
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
		 * @return void
		 */
		public static function bootstrap()
		{
			$controller = self::getParam('controller', \App\Config\System::DEFAULT_CONTROLLER);
			$action = self::getParam('action', \App\Config\System::DEFAULT_ACTION);
			$className = ucfirst(\App\Config\System::APP_DIR)."\\Controllers\\".ucfirst($controller)."Controller";
			$class = new $className;
			$class->$action();
		}

		public static function callController($controller)
		{
			$parameters = explode('/', $controller);
			$controller = $parameters[0];
			$action = $parameters[1];
			$className = ucfirst(\App\Config\System::EXT_DIR).'\\'.ucfirst($controller)."\\Controllers\\".ucfirst($controller)."Controller";
			$class = new $className;
			$class->$action();
		}

		/**
		 * Returns a Model class instance
		 * @static
		 * @param  string $model the models name, eg: 'profiles/user' loads the User class from the 'models/profiles' location
		 * @return
		 */
		public static function getModel($model)
		{
			$model = strtolower($model);
			$is_extension = FALSE;
			$directory = \App\Config\System::APP_DIR;
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
		 * @param  string $view The view's name and location, eg: 'register/new_user' loads 'new_user.php' from 'views/register'
		 * @return View
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

		final private function __clone() {}
	}
}
