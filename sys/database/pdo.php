<?php

namespace Sys\Database
{
	class Pdo
	{
		protected static $instance = NULL;

		protected static $driver = NULL;

		private function __construct() {}

		private function __clone() {}

		public static function driver()
		{
			return self::$driver;
		}

		public static function query($query)
		{
			return self::$driver->query($query);
		}

		public final static function getInstance()
		{
			if (self::$instance === NULL)
			{
				self::$instance = new Pdo();
				self::$driver = new \PDO(\App\Config\System::DB_DSN, \App\Config\System::DB_USER, \App\Config\System::DB_PASS);
			}
			return self::$instance;
		}

		public function __destruct()
		{
			self::$driver = NULL;
		}
	}
}