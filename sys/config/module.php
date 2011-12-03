<?php
/**
 * ZeroG config
 */

namespace Sys\Config
{
	class Module
	{
		/**
		 * Module name (format: DEVELOPER_EXTENSION)
		 *
		 * @var <string>
		 */
		protected $name;

		/**
		 * Running code pool for the module
		 *
		 * @var <string>
		 */
		protected $codePool;

		/**
		 * Module version number
		 * 
		 * @var <string>
		 */
		protected $version;

		/**
		 * The developer name
		 *
		 * @var <string>
		 */
		protected $developer;

		/**
		 * The name of the extension
		 *
		 * @var <string>
		 */
		protected $extension;

		/**
		 * The layout file, if one is specified
		 *
		 * @var <string>
		 */
		//protected $layout;

		/**
		 * The router name used for this module
		 *
		 * @var <string>
		 */
		//protected $routerName;

		/**
		 * Constructor
		 *
		 * @param <string> $name Module name
		 * @param <array> $configData XML Module Config data
		 */
		public function __construct($name, $configData)
		{
			$this->name = $name;
			$this->codePool = $configData['modules'][$name]['codePool'];
			$this->version = $configData['modules'][$name]['version'];
			$modulePath = explode("_", $this->name);
			$this->developer = $modulePath[0];
			$this->extension = $modulePath[1];
		}

		/**
		 * Return just the code pool path of the module
		 * @return <type>
		 */
		public function getCodePoolPath($path = '')
		{
			return sprintf('App\\Code\\%s\\%s',
					ucfirst($this->codePool),
					htmlspecialchars($path));
		}

		/**
		 * Return a module's class path, for blocks, models, etc
		 * @param <string> $path
		 * @return <string>
		 */
		public function getClassName($path = '')
		{
			return sprintf('App\\Code\\%s\\%s\\%s\\%s',
					ucfirst($this->codePool),
					$this->developer,
					$this->extension,
					htmlspecialchars($path));
		}

		/**
		 * Return this module's controller class name
		 *
		 * @param <string> $controller Which controller to return
		 * @return <string>
		 */
		public function getControllerClass($controller = 'index')
		{
			$controller = ucfirst($controller);
			return $this->getClassName('Controller\\'.$controller);
		}

		/**
		 * Returns the classpath of a required block
		 * @param <string> $block
		 * @return <string>
		 */
		public function getBlockClass($block)
		{
			$controller = ucfirst($block);
			return $this->getClassName('Block\\'.$block);
		}

		/**
		 * Return the path to a part of this module's location
		 * @param <string> $additionalPath Add for example 'etc' to return the entire path to this extension's etc folder
		 * @return <string>
		 */
		public function getPath($additionalPath = '')
		{
			return sprintf('app/code/%s/%s/%s/%s',
					$this->codePool,
					$this->developer,
					$this->extension,
					$additionalPath);
		}

		/**
		 * Return this module's name
		 *
		 * @return <string>
		 */
		public function getName()
		{
			return $this->name;
		}

		/**
		 * The developer name of this module
		 *
		 * @return <string>
		 */
		public function getDeveloper()
		{
			return $this->developer;
		}

		/**
		 * Name of the extension
		 *
		 * @return <string>
		 */
		public function getExtension()
		{
			return $this->extension;
		}

		/**
		 * The code pool the module is running on
		 *
		 * @return <string>
		 */
		public function getCodePool()
		{
			return $this->codePool;
		}

		/**
		 * Version of the module
		 *
		 * @return <string>
		 */
		public function getVersion()
		{
			return $this->version;
		}

		/**
		 * The name of the module's layout xml file
		 *
		 * @return <string>
		 */
		/*public function getLayout()
		{
			if ($this->layout)
				return $this->layout;
			return false;
		}*/

		/**
		 * Gets the router name used by this module
		 * For example: The module Pixelplant_Blog uses the router "blog" to
		 * direct all calls to this module's controllers
		 *
		 * @return <string>
		 */
		/*public function getRouterName()
		{
			return $this->routerName;
		}*/
	}
}
