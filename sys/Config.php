<?php
/**
 * Class used to load all the xml config files
 * 
 * @author Radu Mogos <radu.mogos@pixelplant.ro>
 */
namespace Sys
{
	use \Sys\Config\Module;

	class Config
	{
		/**
		 * Array containing all config xml data, merged
		 *
		 * @var <array>
		 */
		protected $_configData;

		/**
		 * Array containing all config data in a flattened array
		 *
		 * @var <array>
		 */
		protected $_flatData;

		/**
		 * Array holding the module names
		 *
		 * @var <array>
		 */
		private $_modules;
		
		/**
		 * Array holding all the models that can be accesed in the framework
		 *
		 * @var <array>
		 */
		private $_models;

		/**
		 * Array holding all blocks defined in the xml files
		 * @var <array>
		 */
		private $_blocks;

		/**
		 * Array holding all helpers defined in the xml files
		 * @var <array>
		 */
		private $_helpers;

		/**
		 * Currently used only to run the install scripts of each extension
		 * @var <array>
		 */
		private $_resources;

		/**
		 * Array holding all csv files used per module
		 * @var <array>
		 */
		private $_translations;

		/**
		 * Array holding all the events we can run
		 * @var <type>
		 */
		private $_events;

		/**
		 * Layouts array
		 *
		 * @var <string>
		 */
		private $_layouts;

		/**
		 * To be changed by the site code
		 * @var <string>
		 */
		private $_siteVersion;

		/**
		 * The library router
		 * @var <\Sys\Config\Router>
		 */
		//private $router;

		/**
		 * Constructor
		 */
		public function __construct()
		{
			// TO BE REPLACED BY SITE CODE
			$this->_siteVersion = 'admin';

			$this->_configData = array();
			$this->_flatData = array();
			$this->_modules = array();
			$this->_blocks = array();
			$this->_helpers = array();
			$this->_models = array();
			$this->_translations = array();
			$this->_resources = array();
			$this->_events = array();
			$this->_layouts = array();

			// first we load all summary module config files defined in app/etc/modules
			$this->_load('app/etc/modules/');
			// then we read the system config in app/etc/local.xml
			$this->_load('app/etc/');
			// and then each module's specific xml file
			foreach ($this->_configData['config']['modules'] as $moduleName => $moduleData)
			{
				// there's no use in reading inactive module's data...
				if ($moduleData['active'] == 'true')
				{
					$moduleCodePool = $moduleData['codePool'];
					$modulePath = $moduleCodePool.'/'.str_replace('_', '/', $moduleName);
					// read the xml data for each module, and add it to the global config
					$this->_load('app/code/'.$modulePath.'/etc/');
					// make an object out of each module, for easy handling
					//$this->_modules[$moduleName] = new Module($moduleName, $this->_configData['config']['modules'][$moduleName]);
				}
				// unset it if it is NOT ACTIVE
				else
					unset ($this->_configData['config']['modules'][$moduleName]);
			}
			// AFTER we have merged all the xml data, we create the module references
			// this way if we have any overwrites they will be taken into account
			foreach ($this->_configData['config']['modules'] as $moduleName => $moduleData)
			{
				$this->_modules[$moduleName] = new Module($moduleName, $this->_configData['config']);
			}

			// Set blocks, layouts, models and other references
			$this->_setReferences();
		}

		/**
		 * Load config data from the database, based on current scope, overwriting
		 * from default scope, to website and website_view level
		 */
		public function loadDatabaseData()
		{
			// first load the website scope config data
			$defaultConfig = \Z::getModel('core/config/data')->getCollection()
					->addFieldToFilter('scope', 'default')
					->addFieldToFilter('scope_id', '0');

			// first load the website scope config data
			$websiteConfig = \Z::getModel('core/config/data')->getCollection()
					->addFieldToFilter('scope', 'website')
					->addFieldToFilter('scope_id', \Z::getWebsiteView()->getWebsite()->getId());

			// then replace it with the website_view scope, if any data is defined
			$websiteViewConfig = \Z::getModel('core/config/data')->getCollection()
					->addFieldToFilter('scope', 'website_view')
					->addFieldToFilter('scope_id', \Z::getWebsiteView()->getId());

			$this->_flatData = array_replace($this->_flatData,
					$defaultConfig->toFlatArray(),
					$websiteConfig->toFlatArray(),
					$websiteViewConfig->toFlatArray());
		}

		/**
		 * Returns the current module which uses this router name
		 * @param <string> $routerName
		 * @return <string>  class name
		 */
		public function getCurrentModule($routerName)
		{
			$routerName = htmlspecialchars($routerName);
			if (strpos($routerName, '_'))
				return $routerName;
			foreach ($this->_configData['config'][$this->_siteVersion]['routers'] as $router)
			{
				if ($router['use'] == $routerName)
				{
					return $router['args']['module'];
				}
			}
			throw new \Sys\Exception('There is no module mapped to this router name '.$routerName);
		}

		private function _setReferences()
		{
			// Populates the block, model and translations data from all
			// the xml configuration files
			$this->_setXmlConfigData($this->_configData);
		}

		/**
		 * Set the references from names to classnames for each block, model
		 * translation, etc
		 *
		 * @param <array> $configData
		 */
		private function _setXmlConfigData($configData)
		{
			// Set blocks data
			if (isset($configData['config']['global']['blocks']))
				foreach ($configData['config']['global']['blocks'] as $key => $block)
				{
					foreach ($block as $class)
					{
						//$codePool = $configData['config']['module']['codePool'];
						$parts = explode("\\", $class);
						$module = $parts[0].'_'.$parts[1];
						// set the class name for these block types, adding the codepool
						// and all the other info stored in the module
						//$this->_blocks[$key] = $this->getModule($module)->getCodePoolPath($class);
						$this->_blocks[$key] = new \Sys\Config\Block(
									$key,
									$module,
									$this->getModule($module)->getCodePoolPath($class)
								);
					}
				}
			// Set helpers
			if (isset($configData['config']['global']['helpers']))
			{
				foreach ($configData['config']['global']['helpers'] as $key => $helper)
				{
					$this->_helpers[$key] = new \Sys\Config\Module\Helper($this->getModule($helper['module']));
				}
			}
			// Set models data
			if (isset($configData['config']['global']['models']))
				foreach ($configData['config']['global']['models'] as $key => $model)
				{
					$parts = explode("\\", $model['class']);
					$module = $parts[0].'_'.$parts[1];
					//$this->_models[$key] = $this->getModule($module)->getCodePoolPath($model);
					// Load the model data
					$this->_models[$key] = $model;
					// Then set the actual path to the model depending on the extension and code pool
					$this->_models[$key]['class'] = $this->getModule($module)->getCodePoolPath($model['class']);
				}
			// Set translation files data
			if (isset($configData['config'][$this->_siteVersion]['translate']['modules']))
				foreach ($configData['config'][$this->_siteVersion]['translate']['modules'] as $key => $module)
				{
					$temp = array();
					foreach ($module['files'] as $id => $file)
					{
						$temp[] = $file;
					}
					$this->_translations[$key] = $temp;
				}
			// Set resources data
			if (isset($configData['config']['global']['resources']))
				foreach ($configData['config']['global']['resources'] as $resourceName => $resource)
				{
					if (isset($resource['setup']))
					{
						foreach ($resource['setup'] as $key => $setup)
						{
							$this->_resources[$resourceName][$key] = $setup;
						}
						$this->_resources[$resourceName]['requiredVersion'] =
								$this->getModule($this->_resources[$resourceName]['module'])
									->getVersion();
					}
				}
			// Set resources data
			if (isset($configData['config'][$this->_siteVersion]['layout']['updates']))
				$this->_layouts = $configData['config'][$this->_siteVersion]['layout']['updates'];
			// set events
			if (isset($configData['config']['global']['events']))
				$this->_events = $configData['config']['global']['events'];
		}

		/**
		 * Load all xml files from specified directory path
		 * 
		 * into one big multi array
		 * @param <string> Directory path
		 */
		protected function _load($path)
		{
			$handle = opendir($path);
			if ($handle)
			{
				while (FALSE !== ($file = readdir($handle)))
				{
					if (strpos($file, '.xml') > 0)
					{
						$moduleConfig      = new \Sys\Helper\XmlToArray($path.$file);
						$moduleArray       = $moduleConfig->createArray();
						$flattenArray      = $moduleConfig->getFlatData();
						$this->_configData = array_replace_recursive($this->_configData, $moduleArray);
						$this->_flatData   = array_replace_recursive($this->_flatData, $flattenArray);
					}
				}
				closedir($handle);
			}
			else
				throw new \Sys\Exception('Cannot read xml modules data in app/etc');
		}

		/**
		 * Return the router instance
		 * 
		 * @return <\Sys\Config\Router>
		 */
		/*public function getRouter()
		{
			return $this->router;
		}*/

		/**
		 * Return the array config data
		 * 
		 * @return <array>
		 */
		public function getData()
		{
			return $this->_configData;
		}

		/**
		 * Return the array for a specific node in the configuration data
		 * 
		 * @return <array>
		 */
		public function getNode($name)
		{
			return $this->_configData['config'][$name];
		}

		/**
		 * Return the router configuration data
		 * 
		 * @return <array>
		 */
		public function getRouterXmlData()
		{
			return $this->_configData['config'][$this->_siteVersion]['routers'];
		}

		/**
		 * Return a config variable value by name
		 * 
		 * @param <string> $name
		 * @return <mixed>
		 */
		public function getConfig($name = NULL)
		{
			if ($name == NULL)
				return $this;
			if (!isset($this->_flatData[$name]))
				return NULL;
				//throw new \Sys\Exception("Config class: The configuration string <b>$name</b> is not set. Check your xml file or database config table for this value");
			return $this->_flatData[$name];
		}

		/**
		 * Returns specific module data
		 *
		 * @param <string> $name Module name
		 * @return <\Sys\Config\Module>
		 */
		public function getModule($name)
		{
			if (isset($this->_modules[$name]))
				return $this->_modules[$name];
			throw
				new \Sys\Exception('Config class: Could not find module name => %s in the loaded modules list', $name);
		}

		/**
		 * Transform a block path into a block classname
		 * @param <string> $name
		 * @return <\Sys\Layout\Block>
		 */
		public function getBlockClass($name)
		{
			$data = $this->_classAddition($name);
			$index = $data['index'];
			$appendClass = $data['class'];
			if (!isset($this->_blocks[$index]))
				throw new \Sys\Exception('The block type => %s is not a registered block', $index);
			return $this->_blocks[$index]->getClass($appendClass);
		}

		/**
		 * Return Block Config data
		 * @param <string> $name
		 * @return <\Sys\Config\Block>
		 */
		public function getBlock($name)
		{
			return $this->_blocks[$name];
		}
		
		/**
		 * Returns the classname of a model
		 * @param <string> $name
		 * @return <\Sys\Model>
		 */
		public function getModelClass($name)
		{
			$data = $this->_classAddition($name);
			$index = $data['index'];
			$subclass = $data['class'];
			if (!isset($this->_models[$index]))
				throw new \Sys\Exception('The model identifier => %s is not registered', $index);
			return $this->_models[$index]['class'].$subclass;
		}

		public function getResource($name)
		{
			return \Z::getModel($this->getResourceClass($name));
			//return  $this->getModelClass($name);
		}

		public function getResourceClass($name)
		{
			$parts  = explode('/', $name);
			$model  = $this->_models[$parts[0]]['resourceModel'];
			$name   = $model.'/'.substr($name, strpos($name, $parts[1]));
			return $name;
		}

		/**
		 * Returns the table referenced by a resource entity
		 *
		 * @param <string> $resourceName
		 * @return <string>
		 */
		public function getResourceTable($resourceName)
		{
			$parts  = explode('/', $resourceName);
			$model  = $this->_models[$parts[0]]['resourceModel'];
			$entity = $parts[1];
			if (isset($this->_models[$model]['entities'][$entity]['table']))
				return $this->_models[$model]['entities'][$entity]['table'];
			throw new \Sys\Exception('The required resource name => %s is not defined in your xml files',
					$resourceName);
		}

		/**
		 * Transform a helper path into a helper classname
		 * @param <string> $name
		 * @return <string>
		 */
		public function getHelperClass($name)
		{
			$data = $this->_classAddition($name);
			$index = $data['index'];
			$class = $data['class'];
			if ($class == '')
				$class = '\Data';
			if (!isset($this->_helpers[$index]))
				throw new \Sys\Exception('The helper type => %s is not a registered helper', $index);
			return $this->_helpers[$index]->getClass().$class;

		}

		/**
		 * Returns the helper settings by name
		 *
		 * @param <string> $name
		 * @return <\Sys\Config\Module\Helper>
		 */
		public function getHelper($name)
		{
			if (isset($this->_helpers[$name]))
			{
				return $this->_helpers[$name];
			}
			return null;
		}

		/**
		 * Get the defined event observer
		 *
		 * @param <string> $eventName
		 * @return <type>
		 */
		public function getEventObserver($eventName)
		{
			if (isset($this->_events[$eventName]))
			{
				$event = $this->_events[$eventName];
				return $event;
			}
			return FALSE;
		}

		/**
		 * Return <resources> tags content
		 * 
		 * @return <array>
		 */
		public function getResources()
		{
			return $this->_resources;
		}

		/**
		 * Return the list of CSV files used for translations
		 *
		 * @return <array>
		 */
		public function getTranslations()
		{
			return $this->_translations;
		}

		/**
		 * Return the layouts xml array
		 *
		 * @return <array>
		 */
		public function getLayouts()
		{
			return $this->_layouts;
		}

		/**
		 * Return the xml layout files used by this router
		 *
		 * @param <string> $router
		 * @return <array>
		 */
		public function getRouterLayouts($router)
		{
			if (isset($this->_layouts[$router]))
			{
				return $this->_layouts[$router]['file'];
			}
			return FALSE;
		}

		/**
		 * Return the relative class name of a block/helper/model. Eg: page/test/user becomes Test\User
		 * 
		 * @param <string> $name
		 * @return <array> Holds index and class name
		 */
		private function _classAddition($name)
		{
			$name = str_replace('_', '/', $name);
			$parts = explode("/", $name);
			$index = $parts[0];
			$class = '';
			for ($i = 1; $i < sizeof($parts); $i++)
			{
				$class .= '\\'.ucfirst($parts[$i]);
			}
			$data['index'] = $index;
			$data['class'] = $class;
			return $data;
		}
	}
}
