<?php

namespace Sys\Database
{
	//use CrudInterface;

	class Pdo implements CrudInterface
	{
		/**
		 * Database driver used
		 * @var <mixed>
		 */
		protected $_driver = NULL;
		
		/**
		 * An array holding a list of fields available for all used tables...
		 * @var <array>
		 */
		protected $_fields = array();

		/**
		 * Table prefix, if any
		 * @var <string>
		 */
		protected $_tablePrefix;

		public function __construct($connectionName)
		{
			$configString = "config/global/resources/db/$connectionName/";
			$adapter = \Z::getConfig($configString."adapter");
			$this->_tablePrefix = \Z::getConfig($configString."table_prefix");
			if (!in_array($adapter, \PDO::getAvailableDrivers()))
				throw new \Sys\Exception("The driver => %s does not seem to be installed on your system.", $connectionName);
			try
			{
				if (\Z::getConfig($configString.'active') != 1)
					throw new \Sys\Exception("If you want to use this database connection name
							=> %s then please make sure it is set to active in app/etc/local.xml", $connectionName);
				if (\Z::getConfig($configString.'dsn'))
					$dsn = \Z::getConfig($configString.'dsn');
				else
				{
					$dsn = 
						\Z::getConfig($configString.'adapter').
						':dbname='.
						\Z::getConfig($configString.'dbname').
						';host='.
						\Z::getConfig($configString.'host');
				}
				$this->_driver =
					new \PDO(
							$dsn,
							\Z::getConfig($configString.'username'),
							\Z::getConfig($configString.'password')
					);
			}
			catch (\PDOException $e)
			{
				throw new \Sys\Exception($e->getMessage());
			}
		}

		//private function __clone() {}

		/**
		 * Execute the final query
		 * @param <string> $query
		 * @return <resource>
		 */
		public function query($query)
		{
			//$query = $this->_driver->quote($query);
			return $this->_driver->query($query);
		}

		public function quote($query)
		{
			return $this->_driver->quote($query);
		}

		/**
		 * Destroy the driver
		 */
		public function __destruct()
		{
			$this->_driver = NULL;
		}

		/**
		 * Returns the table name and the table prefix
		 * @param <string> $table
		 * @return <string>
		 */
		public function getTableName($table)
		{
			return $this->_tablePrefix.htmlspecialchars($table);
		}

		public function getCachedFields($table)
		{
			return $this->_fields[$this->getTableName($table)];
		}

		/**
		 * Cache all table fields in an array
		 * @param <string> $table
		 * @return <mixed>
		 */
		public function cacheFields($table)
		{
			$table = $this->getTableName($table);
			if (empty($this->_fields[$table]))
			{
				//$table = $this->_driver->quote($table);
				$table = $this->getTableName($table);
				$statement = $this->_driver->prepare("select DISTINCT column_name from information_schema.columns where table_name = '$table'");
				try
				{
					if ($statement->execute())
					{
						$raw_column_data = $statement->fetchAll(\PDO::FETCH_ASSOC);

						foreach ($raw_column_data as $outer_key => $array)
						{
							foreach ($array as $inner_key => $value)
							{
								if (!(int)$inner_key)
								{
									$this->_fields[$table][] = $value;
								}
							}
						}
					}
				}
				catch (Exception $e)
				{
					return $e->getMessage(); //return exception
				}
			}
		}

		public function createTable($createQuery)
		{
			$this->_driver->prepare($createQuery)->execute();
		}

		/**
		 * Loads the row with a specified id from the table
		 *
		 * @param <string> $table
		 * @param <int> $id
		 * @return <array> Associated array containing the table row data
		 */
		public function load($table, $id = 0)
		{
			$id = (int)$id;
			$table = $this->getTableName($table);
			$statement = $this->_driver->prepare("SELECT * FROM `$table` WHERE id = :id");
			$statement->execute(array(':id' => $id));
			$row = $statement->fetch(\PDO::FETCH_ASSOC);
			//var_dump($row);
			return $row;
		}

		/**
		 * Updates existing records for a table row with a certain id
		 * @param <string> $table
		 * @param <int> $id
		 * @param <array> $data
		 */
		public function save($table, $id, $data = array())
		{
			$list = array();
			$statementData[':id'] = (int)$id;
			foreach ($data as $key => $value)
			{
				$list[]= $key.' = :'.$key;
				//$statementData[':'.$key] = $this->_driver->quote($value);
				$statementData[':'.$key] = $value;
			}
			$list = implode(",", $list);
			$table = $this->getTableName($table);
			$statement = $this->_driver->prepare("UPDATE `$table` SET $list WHERE id = :id");
			$statement->execute($statementData);
		}

		/**
		 * Inserts new records in a table
		 *
		 * @param <string> $table
		 * @param <array> $data
		 */
		public function add($table, $data = array())
		{
			$fields = array();
			$values = array();
			foreach ($data as $key => $value)
			{
				$fields[]= $key;
				$values[] = ':'.$key;
				$statementData[':'.$key] = $this->_driver->quote($value);
			}
			$fields = implode(",", $fields);
			$values = implode(",", $values);
			$table = $this->getTableName($table);
			$statement = $this->_driver->prepare("INSERT INTO `$table` ($fields) VALUES ($values)");
			$statement->execute($statementData);
		}

		/**
		 * Find records based on search criterias and return an array of
		 * the found records as a model array
		 * @param <string> $class Class to be returned in array
		 * @param <array> $criteria Query criterias
		 * @return <array> Returns an array of $class objects
		 */
		public function find($class, $criteria = array())
		{
			//$table = $this->_driver->quote($criteria['from']);
			$table = $this->getTableName($criteria['from']);
			$query = sprintf("SELECT * FROM `%s` ", $table);
			if (isset($criteria['where']))
				$query .= sprintf(' WHERE %s ', $criteria['where']);
			if (isset($criteria['limit']))
				$query .= sprintf(' LIMIT %s ', $criteria['limit']);
			$statement = $this->query($query);
			$statement->setFetchMode(\PDO::FETCH_CLASS, $class);
			$objects = array();
			while ($object = $statement->fetch())
			{
				$objects[] = $object;
			}
			return $objects;
		}

		/**
		 * Gets the last inserted id
		 * @return <int>
		 */
		public function lastInsertId()
		{
			return (int)$this->_driver->lastInsertId();
		}
	}
}
