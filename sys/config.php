<?php
/**
 * Class used to load all the xml config files
 * 
 * @author Radu Mogos <radu.mogos@pixelplant.ro>
 */
namespace Sys
{
	use \Sys\Config\Module;
	//use \Sys\Config\Router;

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
		 * The library router
		 * @var <\Sys\Config\Router>
		 */
		//private $router;

		/**
		 * Constructor
		 */
		public function __construct()
		{
			$this->_configData = array();
			$this->_flatData = array();
			$this->_modules = array();
			$this->_blocks = array();
			$this->_helpers = array();
			$this->_models = array();
			$this->_translations = array();
			$this->_resources = array();

			// first we load all summary module config files defined in app/etc/modules
			$this->load('app/etc/modules/');
			// then we read the system config in app/etc/local.xml
			$this->load('app/etc/');
			// and then each module's specific xml file
			foreach ($this->_configData['config']['modules'] as $moduleName => $moduleData)
			{
				// there's no use in reading inactive module's data...
				if ($moduleData['active'] == 'true')
				{
					$moduleCodePool = $moduleData['codePool'];
					$modulePath = $moduleCodePool.'/'.str_replace('_', '/', $moduleName);
					// read the xml data for each module, and add it to the global config
					$this->load('app/code/'.$modulePath.'/etc/');
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
			$this->setReferences();
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
			foreach ($this->_configData['config']['frontend']['routers'] as $router)
			{
				if ($router['from'] == $routerName)
				{
					return $router['to']['module'];
				}
			}
			throw new \Sys\Exception('There is no module mapped to this router name '.$routerName);
		}

		private function setReferences()
		{
			// Populates the block, model and translations data from all
			// the xml configuration files
			$this->setXmlConfigData($this->_configData);
		}

		/**
		 * Set the references from names to classnames for each block, model
		 * translation, etc
		 *
		 * @param <array> $configData
		 */
		private function setXmlConfigData($configData)
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
						$this->_blocks[$key] = $this->getModule($module)->getCodePoolPath($class);
						$this->_helpers[$key] = $this->getModule($module)->getClassName('Helper');
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
			if (isset($configData['config']['frontend']['translate']['modules']))
				foreach ($configData['config']['frontend']['translate']['modules'] as $key => $module)
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
		}

		/**
		 * Starts the installer
		 * 
		 * @return core/installer
		 */
		public function getInstaller()
		{
			$class = $this->getModelClass('core/installer');
			return new $class;
		}

		/**
		 * Executes all the setup scripts that have not been run yet
		 */
		public function runSetupScripts()
		{
			//$installed = \Z::getModel('core/resource')->loadAll();
			foreach ($this->_resources as $name => $resource)
			{
				$latestVersion = $resource['requiredVersion'];
				//$installedVersion = $installed->getResource($name)->getVersion();
				$installedVersion = '0.0.5';
				$end = 'install-'.$latestVersion.'.php';
				$start = 'install-'.$installedVersion.'.php';
				if ($latestVersion != $installedVersion)
				{
					$installerFilesLocation = $this->getModule($resource['module'])->getPath('sql/'.$name.'/');
					$handle = opendir($installerFilesLocation);
					if ($handle)
					{
						while (FALSE !== ($file = readdir($handle)))
						{
							if (strpos($file, '.php') > 0)
							{
								if (($file > $start) && ($file <= $end))
								{
									//echo $installerFilesLocation.$file.'<br/>';
									include $installerFilesLocation.$file;
								}
								// execute the install script for $file
								// update the version in the table
							}
						}
						closedir($handle);
					}
					else
						throw new \Sys\Exception('Cannot open the location => %s of the setup install scripts',
							$installerFilesLocation);
				}
			}
		}

		/**
		 * Load all xml files from specified directory path
		 * 
		 * into one big multi array
		 * @param <string> Directory path
		 */
		protected function load($path)
		{
			$handle = opendir($path);
			if ($handle)
			{
				while (FALSE !== ($file = readdir($handle)))
				{
					if (strpos($file, '.xml') > 0)
					{
						$moduleConfig = new \Sys\Helper\XmlToArray($path.$file);
						$moduleArray = $moduleConfig->createArray();
						$flattenArray = $moduleConfig->getFlatData();
						$this->_configData = array_replace_recursive($this->_configData, $moduleArray);
						$this->_flatData = array_replace_recursive($this->_flatData, $flattenArray);
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

		public function getRouterXmlData()
		{
			return $this->_configData['config']['frontend']['routers'];
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
			$data = $this->classAddition($name);
			$index = $data['index'];
			$class = $data['class'];
			if (!isset($this->_blocks[$index]))
				throw new \Sys\Exception('The block type => %s is not a registered block', $index);
			return $this->_blocks[$index].$class;
		}
		
		/**
		 * Returns the classname of a model
		 * @param <string> $name
		 * @return <\Sys\Model>
		 */
		public function getModelClass($name)
		{
			$data = $this->classAddition($name);
			$index = $data['index'];
			$subclass = $data['class'];
			if (!isset($this->_models[$index]['class']))
				throw new \Sys\Exception('The model name => %s is not a registered model', $index);
			return $this->_models[$index]['class'].$subclass;
		}

		public function getResourceClass($name)
		{
			$parts  = explode('/', $name);
			$model  = $this->_models[$parts[0]]['resourceModel'];
			$name   = $model.'/'.$parts[1];
			return  $this->getModelClass($name);
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
			$data = $this->classAddition($name);
			$index = $data['index'];
			$class = $data['class'];
			if (!isset($this->_helpers[$index]))
				throw new \Sys\Exception('The helper type => %s is not a registered helper'. $index);
			return $this->_helpers[$index].$class;
		}

		/**
		 * Return the relative class name of a block/helper/model. Eg: page/test/user becomes Test\User
		 * 
		 * @param <string> $name
		 * @return <array> Holds index and class name
		 */
		private function classAddition($name)
		{
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
