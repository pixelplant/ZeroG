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
		 * Database name
		 * @var <string>
		 */
		protected $_databaseName;

		/**
		 * Table prefix, if any
		 * @var <string>
		 */
		protected $_tablePrefix;

		public function __construct($connectionName)
		{
			$configString = "config/global/resources/db/$connectionName/";
			$adapter = \Z::getConfig($configString."adapter");
			$this->_databaseName = \Z::getConfig($configString.'dbname');
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
						$this->_databaseName.
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
		 * 
		 * @param <string> $query
		 * @return <resource>
		 */
		public function query($query)
		{
			//$query = $this->_driver->quote($query);
			return $this->_driver->query($query);
		}

		/**
		 * Quotes an sql query
		 *
		 * @param <string> $query
		 * @return <type>
		 */
		public function quote($query)
		{
			return $this->_driver->quote($query);
		}

		/**
		 * Prepares an SQL query
		 *
		 * @param <string> $query
		 * @return <type>
		 */
		public function prepare($query)
		{
			return $this->_driver->prepare($query);
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
				try
				{
					$statement = $this->_driver->prepare("SELECT column_name FROM information_schema.columns WHERE table_name = ? AND table_schema = ? ");
					$statement->bindValue(1, $table,               \PDO::PARAM_STR);
					$statement->bindValue(2, $this->_databaseName, \PDO::PARAM_STR);
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
			return $this->_fields[$table];
		}

		public function createTable($createQuery)
		{
			$this->_driver->prepare($createQuery)->execute();
		}

		/**
		 * Loads the row with a specified id from the table
		 *
		 * @param <string> $table Name of the table to load data from
		 * @param <string> $field Name of the id column in the table
		 * @param <int> $id Value of the id column to load
		 * @return <array> Associated array containing the table row data
		 */
		public function load($table, $field, $value = 0)
		{
			//$id = (int)$id;
			try
			{
				if (!in_array($field, $this->_fields[$table]))
					throw new \Sys\Exception('Table field is not defined');
				$table = $this->getTableName($table);
				$statement = $this->_driver->prepare("SELECT * FROM `$table` WHERE $field = ?");
				//$statement->bindValue(1, $table, \PDO::PARAM_STR);
				//$statement->bindValue(1, $field, \PDO::PARAM_STR);
				$statement->bindValue(1, $value, \PDO::PARAM_STR);
				$statement->execute();
				$row = $statement->fetch(\PDO::FETCH_ASSOC);
				return $row;
			}
			catch(PDOException $e)
			{
				// this NEEDS to be set into a LOG function
				throw new \Sys\Exception('Cannot load the field %s for the table %s. Further messages: %s',
						$field,
						$table,
						$e->getMessage());
			}
		}

		/**
		 * Updates existing records for a table row with a certain id
		 * @param <string> $table
		 * @param <int> $id
		 * @param <array> $data
		 */
		public function save($table, $idField, $id, $data = array())
		{
			$list = array();
			$statementData[':id'] = $id;
			foreach ($data as $key => $value)
			{
				$list[]= $key.' = :'.$key;
				//$statementData[':'.$key] = $this->_driver->quote($value);
				$statementData[':'.$key] = $value;
			}
			$list = implode(",", $list);
			$table = $this->getTableName($table);
			try
			{
				$statement = $this->_driver->prepare("UPDATE `$table` SET $list WHERE $idField = :id");
				$statement->execute($statementData);
			}
			catch (\PDOException $e)
			{
				throw new \Sys\Exception("Cannot update table data: %s", $e->getMessage());
			}
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
				$fields[] = $key;
				$values[] = ':'.$key;
				$statementData[':'.$key] = $value;
			}
			$fields    = implode(",", $fields);
			$values    = implode(",", $values);
			$table     = $this->getTableName($table);
			$statement = $this->_driver->prepare("INSERT INTO `$table` ($fields) VALUES ($values)");
			if ($statement->execute($statementData) === FALSE)
				throw new \Sys\Exception('Cannot execute prepared statement for table => %s', $table);
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
			$query = "SELECT * FROM `$table` ";
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

		public function beginTransaction()
		{
			$this->beginTransaction();
		}

		public function commit()
		{
			$this->commit();
		}

		public function rollback()
		{
			$this->rollback();
		}
	}
}
