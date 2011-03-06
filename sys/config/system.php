<?php

namespace Sys\Config
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
			$xml = new \SimpleXMLElement('app/config/system.xml', NULL, TRUE);
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
