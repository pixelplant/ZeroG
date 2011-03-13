<?php
/**
 * Class used to load all the xml config files
 * 
 * @author Radu Mogos <radu.mogos@pixelplant.ro>
 */
namespace Sys
{
	class Config
	{
		/**
		 * Array containing all config xml data, merged
		 *
		 * @var <type>
		 */
		protected $configData;

		/**
		 * Array holding the module names
		 *
		 * @var <array>
		 */
		private $modules;

		/**
		 * Constructor
		 */
		public function __construct()
		{
			$this->configData = array();
			$this->modules = array();
			
			$this->load('app/etc/modules/');
			foreach ($this->configData['config']['modules'] as $moduleName => $moduleData)
			{
				$moduleCodePool = $moduleData['codePool'];
				$modulePath = $moduleCodePool.'/'.str_replace('_', '/', $moduleName);
				$this->load('app/code/'.$modulePath.'/etc/');
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
			if ($handle = opendir($path))
			{
				while (FALSE !== ($file = readdir($handle)))
				{
					if (strpos($file, '.xml') > 0)
					{
						$moduleConfig = new \Sys\Helper\XmlToArray($path.$file);
						$moduleArray = $moduleConfig->createArray();
						$this->configData = array_replace_recursive($this->configData, $moduleArray);
					}
				}
				closedir($handle);
			}
			else
				throw new \Sys\Exception('Cannot read xml modules data in app/etc');
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
	}
}
