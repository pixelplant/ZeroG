<?php
/**
 * Class used to load all the xml config files
 * 
 * @author Radu Mogos <radu.mogos@pixelplant.ro>
 */
namespace Sys
{
	use \Sys\Config\Module;
	use \Sys\Config\Router;

	class Config
	{
		/**
		 * Array containing all config xml data, merged
		 *
		 * @var <array>
		 */
		protected $configData;

		/**
		 * Array containing all config data in a flattened array
		 *
		 * @var <array>
		 */
		protected $flatData;

		/**
		 * Array holding the module names
		 *
		 * @var <array>
		 */
		private $modules;
		
		/**
		 * Array holding all the models that can be accesed in the framework
		 *
		 * @var <array>
		 */
		private $models;

		/**
		 * Array holding all blocks defined in the xml files
		 * @var <array>
		 */
		private $blocks;

		/**
		 * Array holding all helpers defined in the xml files
		 * @var <array>
		 */
		private $helpers;

		/**
		 * Array holding all csv files used per module
		 * @var <array>
		 */
		private $translations;

		/**
		 * The library router
		 * @var <\Sys\Router>
		 */
		private $router;

		/**
		 * Constructor
		 */
		public function __construct()
		{
			$this->configData = array();
			$this->flatData = array();
			$this->modules = array();
			$this->blocks = array();
			$this->helpers = array();
			$this->models = array();
			$this->translations = array();

			// first we load all summary module config files defined in app/etc/modules
			$this->load('app/etc/modules/');
			// then we read the system config in app/etc/local.xml
			$this->load('app/etc/');
			// and then each module's specific xml file
			foreach ($this->configData['config']['modules'] as $moduleName => $moduleData)
			{
				// there's no use in reading inactive module's data...
				if ($moduleData['active'] == 'true')
				{
					$moduleCodePool = $moduleData['codePool'];
					$modulePath = $moduleCodePool.'/'.str_replace('_', '/', $moduleName);
					// read the xml data for each module, and add it to the global config
					$this->load('app/code/'.$modulePath.'/etc/');
					// make an object out of each module, for easy handling
					//$this->modules[$moduleName] = new Module($moduleName, $this->configData['config']['modules'][$moduleName]);
				}
				// unset it if it is NOT ACTIVE
				else
					unset ($this->configData['config']['modules'][$moduleName]);
			}
			// AFTER we have merged all the xml data, we create the module references
			// this way if we have any overwrites they will be taken into account
			foreach ($this->configData['config']['modules'] as $moduleName => $moduleData)
			{
				$this->modules[$moduleName] = new Module($moduleName, $this->configData['config']);
			}

			// Set blocks, layouts, models and other references
			$this->setReferences();

			// load and execute the Router
			$this->router = new Router($this);
			// load all the frontend routes
			$this->router->loadRules($this->configData['config']['frontend']['routers']);
			$this->router->execute();
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
			foreach ($this->configData['config']['frontend']['routers'] as $router)
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
			$this->setXmlConfigData($this->configData);
		}

		/**
		 * Set the references from names to classnames for each block, model
		 * translation, etc
		 *
		 * @param <array> $configData
		 */
		private function setXmlConfigData($configData)
		{
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
						$this->blocks[$key] = $this->getModule($module)->getClassName('Blocks');
						$this->helpers[$key] = $this->getModule($module)->getClassName('Helpers');
					}
				}
				
			if (isset($configData['config']['global']['models']))
				foreach ($configData['config']['global']['models'] as $key => $model)
				{
					$parts = explode("\\", $model['class']);
					$module = $parts[0].'_'.$parts[1];
					$this->models[$key] = $this->getModule($module)->getClassName('Models');
					/*print_r($model);
					foreach ($model as $class)
					{
					}*/
				}
			if (isset($configData['config']['frontend']['translate']['modules']))
				foreach ($configData['config']['frontend']['translate']['modules'] as $key => $module)
				{
					$temp = array();
					foreach ($module['files'] as $id => $file)
					{
						$temp[] = $file;
					}
					$this->translations[$key] = $temp;
				}
			//print_r($this->models);
		}

		/**
		 * Load all xml files from specified directory path
		 * 
		 * into one big multi array
		 * @param <string> Directory path
		 */
		protected function load($path)
		{
			if ($handle = opendir($path))
			{
				while (FALSE !== ($file = readdir($handle)))
				{
					if (strpos($file, '.xml') > 0)
					{
						$moduleConfig = new \Sys\Helper\XmlToArray($path.$file);
						$moduleArray = $moduleConfig->createArray();
						$flattenArray = $moduleConfig->getFlatData();
						$this->configData = array_replace_recursive($this->configData, $moduleArray);
						$this->flatData = array_replace_recursive($this->flatData, $flattenArray);
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
		 * @return <type>
		 */
		public function getRouter()
		{
			return $this->router;
		}

		/**
		 * Return the array config data
		 * 
		 * @return <array>
		 */
		public function getData()
		{
			return $this->configData;
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
			if (!isset($this->flatData[$name]))
				return NULL;
				//throw new \Sys\Exception("Config class: The configuration string <b>$name</b> is not set. Check your xml file or database config table for this value");
			return $this->flatData[$name];
		}

		/**
		 * Returns specific module data
		 *
		 * @param <string> $name Module name
		 * @return <\Sys\Config\Module>
		 */
		public function getModule($name)
		{
			if (isset($this->modules[$name]))
				return $this->modules[$name];
			throw
				new \Sys\Exception("Config class: Could not find module name $name in the loaded modules list");
		}

		/**
		 * Transform a block path into a block classname
		 * @param <type> $name
		 * @return <type>
		 */
		public function getBlockClass($name)
		{
			$data = $this->classAddition($name);
			if (!isset($this->blocks[$data['index']]))
				throw new \Sys\Exception('The block type '.$data['index'].' is not a registered block');
			return $this->blocks[$data['index']].$data['class'];
		}
		
		/**
		 * Returns the classname of a model
		 * @param <type> $name
		 * @return <type>
		 */
		public function getModelClass($name)
		{
			$data = $this->classAddition($name);
			if (!isset($this->models[$data['index']]))
				throw new \Sys\Exception('The model name '.$data['index'].' is not a registered model');
			return $this->models[$data['index']].$data['class'];
		}

		/**
		 * Transform a helper path into a helper classname
		 * @param <string> $name
		 * @return <string>
		 */
		public function getHelperClass($name)
		{
			$data = $this->classAddition($name);
			if (!isset($this->helpers[$data['index']]))
				throw new \Sys\Exception('The helper type '.$data['index'].' is not a registered helper');
			return $this->helpers[$data['index']].$data['class'];
		}

		/**
		 * Return the relative class name of a block/helper/model. Eg: page/test/user becomes Test\User
		 * @param <type> $name
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
