<?php

namespace App\Code\Core\ZeroG\Install\Model
{

	/**
	 * Runs the setup scripts for all extensions, or upgrades them if needed
	 *
	 * @author radu.mogos
	 */
	class Setup extends \Sys\Model
	{
		/**
		 * A list of currently executed install scripts
		 * 
		 * @var <array> 
		 */
		protected $_executedFiles;

		protected function _construct()
		{
			$this->_executedFiles = array();
		}

		/**
		 * A list of executed install scripts
		 * 
		 * @return <array>
		 */
		public function getExecutedFiles()
		{
			return $this->_executedFiles;
		}

		/**
		 * Starts the installer
		 *
		 * @return core/installer
		 */
		public function getInstaller()
		{
			$class = \Z::getConfig()->getModelClass('core/installer');
			return new $class;
		}

		/**
		 * Executes all the extension setup scripts that have not been run yet
		 */
		public function runSetupScripts()
		{
			$xmlResources = \Z::getConfig()->getResources();
			try
			{
				$installedResources = \Z::getModel('core/extension')->getCollection()->load();
			}
			catch (\Sys\Exception $e)
			{
				$this->initialInstall();
				$installedResources = \Z::getModel('core/extension')->getCollection()->load();
			}
			foreach ($xmlResources as $name => $resource)
			{
				// this is the version we should have installed on our system
				$latestVersion = $resource['requiredVersion'];
				// this is the version we currently have on our system, if the
				// extension is installed at all
				$installedExtension = $installedResources->isInstalled($name);
				if ($installedExtension)
					$installedVersion = $installedExtension->getVersion();
				else
				{
					$installedVersion = '0.0.0';
					$installedExtension = \Z::getModel('core/extension');
				}
				$end = 'install-'.$latestVersion.'.php';
				$start = 'install-'.$installedVersion.'.php';
				if ($latestVersion != $installedVersion)
				{
					$installerFilesLocation = \Z::getConfig()->getModule($resource['module'])->getPath('sql/'.$name.'/');
					$handle = opendir($installerFilesLocation);
					if ($handle)
					{
						while (FALSE !== ($file = readdir($handle)))
						{
							if (strpos($file, '.php') > 0)
							{
								if (($file > $start) && ($file <= $end))
								{
									$this->_executedFiles[] = $installerFilesLocation.$file;
									//echo $installerFilesLocation.$file.'<br/>';
									include $installerFilesLocation.$file;
									// after the script is executed, set the
									// current version in the extension table
									if ($installedExtension->getCode() != '')
										$installedExtension->setVersion($latestVersion);
									else
										$installedExtension->setVersion($latestVersion)
											->setCode($name);
									$installedExtension->save();
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
			return $this;
		}
		
		/**
		* If the core_extension table is missing from the database then it means
		* this is the initial install of ZeroG, so this table has to be created
		* first
		*/
		public function initialInstall()
		{
			$installer = $this->getInstaller();
			$installer->newTable($installer->getResourceTable('core/extension'))
			->addColumn('code', $installer::TYPE_TEXT, 50, array(
				'nullable' => FALSE,
				'default' => '',
				'primary' => TRUE,
				),'Extension identifier')
			->addColumn('version', $installer::TYPE_TEXT, 50, array(
				'nullable' => FALSE,
				'default' => '',
				),'The currently installed version of this extension')
			->setComment('List of ZeroG installed extensions and their version number');
			$installer->run();
		}
	}
}
