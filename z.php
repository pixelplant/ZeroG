<?php
/**
 * Main class of the framework. Takes care of bootstraping, routing and models loading.
 * User: radu.mogos
 * Date: 30.11.2011
 * Time: 00:36:02
 */

namespace
{

	final class Z
	{
		/**
		 * The current running ZeroG Instance
		 * @var <\Sys\ZeroG>
		 */
		private static $_instance = NULL;

		/**
		 * Holds an array of various singleton classes
		 * @var <array>
		 */
		private static $_singletons = array();

		/**
		 * Request class
		 * @var <Sys\Request>
		 */
		private static $_request;

		/**
		 * The current context ZeroG is running in. Eg: cms/index runs in cms_index
		 * context, so the cms_index tag will be processed from the layout xml file.
		 * Basically this is just a shortcut to the specific tag in the layout that
		 * should be processed
		 * @var <string>
		 */
		private static $_context = '';

		/**
		 * Reference to the Localization/Translation class
		 * @var <\Sys\L10n\Locale>
		 */
		private static $_locale = NULL;

		/**
		 * A shortcut to the Profiler class
		 * @var <\Sys\Profiler>
		 */
		private static $_profiler = NULL;

		/**
		 * A shortcut to the Configuration class
		 * @var <App\Config\System>
		 */
		private static $_config = NULL;

		/**
		 * The internal registry holding array of mixed types
		 * 
		 * @var <array>
		 */
		private static $_registry = array();

		/**
		 * Current running module name
		 *
		 * @var <string>
		 */
		private static $_module = '';

		/**
		 * An array of the current available db connections
		 *
		 * @var <array>
		 */
		private static $_connections = array();

		/**
		 * Current running controller name
		 * @var <mixed>
		 */
		private static $_controller = null;

		/**
		 * Current running action name
		 * @var <string>
		 */
		private static $_action = '';

		/**
		 * Current website view
		 * @var <Core\Model\Website\View>
		 */
		private static $_website_view = null;

		/**
		 * Start up ZeroG, load the Localization class, store the REQUEST
		 * parameters and configure URL rewrites
		 *
		 * @param <string> $code Website or Website_View code
		 * @param <string> $type Possible values: Website or Website_View
		 */
		final public static function run($code = '', $type = '')
		{
			if (self::$_instance !== NULL)
				return;

			// generate the singleton
			self::$_instance = self::getInstance();

			// start global profiler timer
			self::getProfiler()->start('timer/global');

			// load config data + execute router
			self::$_config = self::getSingleton('Sys\\Config');
			// set current website
			self::initializeWebsite($code, $type);
			// load config data for current website/group or view
			self::$_config->loadDatabaseData();

			// call the framework start event
			// it's called after the config is loaded because the observers are
			// defined in the xml config files or in the config cache
			self::dispatchEvent('zerog_start');

			// get request parameters
			self::getRequest();

			if (self::getRequest()->getParams() === NULL)
				throw new \Sys\Exception('No map matched the current route');

			// load locale settings and labels
			self::$_locale = self::getSingleton('Sys\\L10n\\Locale', self::getRequest()->getParam('locale', self::getConfig('config/global/default/locale')));

			// run the application
			self::bootstrap();

			// call the framework stop event
			self::dispatchEvent('zerog_stop');

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
			// current frontend router
			$router = self::getRequest()->getParam('router');
			// current module (extension)
			$module = self::getRequest()->getParam('module');
			// current controller called in our extension
			$controller = self::getRequest()->getParam('controller');
			// current action called in our controller
			$action = self::getRequest()->getParam('action');
			// register also the module, so we can attach it to the modules config class
			self::register('current_module', self::getConfig()->getModule($module));
			// current context, used to fetch the current layout xml tag
			self::$_context = $router.'_'.$controller.'_'.$action;
			// store the current module, controller, and action
			self::$_module = $module;
			// store the current controller
			//self::$_controller = $controller;
			// store the current action
			$action .= 'Action';
			self::$_action = $action;
			// get the controller classname (+path) from the currenly loaded extension
			$controllerClassName = self::getConfig()->getModule($module)->getControllerClass($controller);
			//if (!class_exists($controllerClassName))
			//	throw new Sys\Exception("The controller %s class you are trying to initialize does not exist", $controllerClassName);
			// instantiate the extension's controller class
			try
			{
				if (class_exists($controllerClassName))
					$class = new $controllerClassName;
			}
			catch (LogicException $e)
			{
				die('TODO: Fa un redirect la pagina 404');
				//$this->redirect('page/view/error');
			}
			self::$_controller = $class;
			// send all the GET/POST requests to the controller's action for
			// further processing
			//if (!method_exists($class, $action))
			//	throw new Sys\Exception("The called action %s does not exist on the current controller", $action);
			$class->dispatch();
			// and this is all there is to it. our app should be running now...
		}

		/**
		 * Load website data based on code and type
		 *
		 * @param <string> $code Website or Website_View code
		 * @param <string> $type String for 'website' or 'website_view'
		 */
		public static function initializeWebsite($code, $type)
		{
			if ($type == 'website_view' && $code != '')
			{
				$website_view  = \Z::getModel('core/website/view')->loadByCode($code);
				self::$_website_view = $website_view;
				//$website_group = $website_view->getWebsiteGroup();
				//$website       = $website_view->getWebsite();
			}
			else if ($type == 'website_view')
			{
				if ($code != '')
				{
					$website   = \Z::getModel('core/website')->loadByCode($code);
				}
				else
				{
					$website   = \Z::getModel('core/website')->loadDefault();
				}
				$website_group = \Z::getModel('core/website/group')->load($website->getDefaultWebsiteGroupId());
				$website_view  = \Z::getModel('core/website/view')->load($website_group->getDefaultWebsiteViewId());
				$website_view->setWebsite($website)->setWebsiteGroup($website_group);
				self::$_website_view = $website_view;
			}
			else
			{
				throw new \Sys\Exception("Please specify a valid website code and type to display!");
			}
			if ($website_view->getIsActive() == 0)
			{
				throw new \Sys\Exception("The currently selected website_view is not active");
			}
		}

		/**
		 * Get current loaded website view
		 *
		 * @return <Core\Model\Website\View>
		 */
		public static function getWebsiteView()
		{
			return self::$_website_view;
		}

		/**
		 * Dispatch observer callbacks defined in xml files
		 *
		 * @param <string> $eventName
		 * @param <mixed> $eventParams
		 */
		public static function dispatchEvent($eventName, $eventParams = null)
		{
			// There can be more than 1 observer for an event
			$observersForThisEvent = self::$_config->getEventObserver($eventName);
			if ($observersForThisEvent !== FALSE)
			{
				$eventData = new \Sys\Model();
				$eventData->setData($eventParams);
				foreach ($observersForThisEvent as $event)
				{
					$class = self::getSingleton($event['class']);
					$action = $event['method'];
					$class->$action($eventData);
				}
			}
			// check if we have an observer and then execute it
			// TO BE IMPLEMENTED
		}

		/**
		 * Returns the request instance
		 */
		public static function getRequest()
		{
			if (self::$_request == null)
				self::$_request = new \Sys\Request();
			return self::$_request;
		}

		/**
		 * Context parameters are read from app/config/routes.xml
		 *
		 * @return <array>
		 */
		/*public static function getContextParams()
		{
			return self::$_config->getRouter()->getContextParams();
		}*/

		/**
		 * Return the cached context name
		 *
		 * @return <string>
		 */
		public static function getContext()
		{
			return self::$_context;
		}

		/**
		 * Return the profiler
		 *
		 * @return <\Sys\Profiler>
		 */
		public static function getProfiler()
		{
			if (!array_key_exists('Sys\\Profiler', self::$_singletons))
			{
				self::$_singletons['Sys\\Profiler'] = new \Sys\Profiler();
				self::$_profiler = self::$_singletons['Sys\\Profiler'];
			}
			return self::$_profiler;
		}

		/**
		 * Return the current ZeroG instance
		 * @return <\Sys\ZeroG> ZeroG instance
		 */
		final public static function getInstance()
		{
			if (self::$_instance === NULL)
			{
				self::$_instance = new Z();
				//self::init();
			}
			return self::$_instance;
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
			//static $_singletons = array();
			$identifier = $class;
			// if it's a model we created in an extension, the identifier and
			// class are different, so we need to load the proper class
			if (strpos($class, '/') !== FALSE)
				$class = self::$_config->getModelClass($identifier);
			if (!array_key_exists($identifier, self::$_singletons)) {
				//$serializedFile = 'var/cache/serialized/'.md5($class).'.ser';
				self::getProfiler()->start($class);
				//if (!file_exists($serializedFile))
				//{
					self::$_singletons[$identifier] = new $class($classParams);
				//	file_put_contents($serializedFile, serialize(self::$_singletons[$class]));
				//}
				//else
				//	self::$_singletons[$class] = unserialize(file_get_contents($serializedFile));
				self::getProfiler()->stop($class);
			}
			return self::$_singletons[$identifier];
		}

		/**
		 * Get the current controller object
		 * @return <mixed>
		 */
		public static function getController()
		{
			return self::$_controller;
		}

		/**
		 * Get the current action name
		 * @return <string>
		 */
		public static function getAction()
		{
			return self::$_action;
		}

		/**
		 * Returns the database connection specified by $connectionName in app/etc/local.xml
		 *
		 * @param <string> $connectionName Connection name to use
		 */
		public static function getDatabaseConnection($connectionName = 'default_setup')
		{
			// Store an array of connections, depending if it's a write/read adapter for example, or
			// if we have more than one database connections.
			// The default connection is specified in the "default_setup" adapter
			if (!array_key_exists($connectionName, self::$_connections))
			{
				self::$_connections[$connectionName] = new \Sys\Database\Pdo($connectionName);
			}
			return self::$_connections[$connectionName];
		}

		/**
		 * Store a value in the registry
		 *
		 * @param <string> $name
		 * @param <mixed> $value
		 */
		public static function register($name, $value)
		{
			self::$_registry[$name] = $value;
		}

		/**
		 * Return a value from the registry
		 *
		 * @param <string> $name
		 * @return <mixed>
		 */
		public static function registry($name)
		{
			if (isset(self::$_registry[$name]))
				return self::$_registry[$name];
			return FALSE;
		}

		/**
		 * Resets the registry records
		 */
		public static function resetRegistry()
		{
			self::$_registry = array();
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
			return self::getSingleton(self::$_config->getHelperClass($name),
					self::$_config->getHelper($name)->getModule());
		}
		
		/**
		 * Returns a model class by identifier
		 *
		 * @param <string> $name
		 * @return <mixed>
		 */
		public static function getModel($name)
		{
			//return self::getSingleton(self::$_config->getModelClass($name));
			$class = self::$_config->getModelClass($name);
			self::getProfiler()->start($class);
			$instance = new $class;
			self::getProfiler()->stop($class);
			return $instance;
		}

		/**
		 * Add a block to the current layout (if the layout was not rendered yet)
		 *
		 * @param <string> $name
		 * @param <string> $type
		 * @return \Sys\Layout\Block
		 */
		public static function getBlock($name, $type)
		{
			$class = self::$_config->getBlockClass($type);
			self::getProfiler()->start($class);
			$instance = new $class($name, $type);
			self::getProfiler()->stop($class);
			return $instance;
		}

		/**
		 * Returns the current controller's layout object
		 * 
		 * @return <Sys\Layout>
		 */
		public static function getLayout()
		{
			return self::$_controller->getLayout();
		}

		/**
		 * Returns a resource model
		 *
		 * @param <string> $name
		 * @return <mixed>
		 */
		public static function getResource($name)
		{
			$resourceIdentifier = 'resource_'.$name;
			if (!array_key_exists($resourceIdentifier, self::$_singletons))
			{
				$class = self::$_config->getResourceClass($name);
				self::$_singletons[$resourceIdentifier] = new $class;
			}
			return self::$_singletons[$resourceIdentifier];
		}

		/**
		 * Return the value of a config variable
		 *
		 * @param <string> $variable
		 * @return <mixed>
		 */
		public static function getConfig($variable = NULL)
		{
			return self::$_config->getConfig($variable);
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
			return self::$_locale;
		}

		/**
		 * Return a translated label, based on the current locale
		 *
		 * @param <string> $label The label to translate, in english
		 * @param <string> $module The module the label belongs to
		 * @return <string> The translated label
		 */
		public static function __($label, $module)
		{
			return self::$_locale->__($label, $module);
		}

	}
}
