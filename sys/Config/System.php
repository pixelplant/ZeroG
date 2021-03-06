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
			$xml = new \SimpleXMLElement('app/etc/local.xml', NULL, TRUE);
			foreach ($xml->config as $config)
			{
				$value = (string)$config[0];
				settype($value, (string)$config["type"]);
				$this->configData[(string)$config["name"]] = $value;
			}
		}

		public function getData($name)
		{
			if (isset($this->configData[$name]))
				return $this->configData[$name];
			throw new \Sys\Exception('Config parameter: '.$name.' not found');
		}
	}
}
