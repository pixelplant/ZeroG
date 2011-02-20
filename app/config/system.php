<?php

namespace App\Config
{
	final class System
	{
		/**
		 * Config data for our application
		 * @var <array>
		 */
		protected $configData;

		public function __construct()
		{
			//$this->configData['app/dir'] = 'app';
			$xml = new \SimpleXMLElement(dirname(__FILE__).'/system.xml', NULL, TRUE);
			foreach ($xml->config as $config)
			{
				$value = (string)$config[0];
				settype($value, (string)$config["type"]);
				$this->configData[(string)$config["name"]] = $value;
			}
		}

		public function getData($name)
		{
			return $this->configData[$name];
		}
	}
}
