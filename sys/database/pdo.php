<?php

namespace Sys\Database
{
	//use CrudInterface;

	class Pdo implements CrudInterface
	{
		// Database driver used
		protected $driver = NULL;
		
		// An array holding a list of fields available for all used tables...
		protected $fields;

		public function __construct($connectionName)
		{
			$configString = "config/global/resources/db/$connectionName/";
			$adapter = \Z::getConfig($configString."adapter");
			if (!in_array($adapter, \PDO::getAvailableDrivers()))
				throw new \Sys\Exception("The driver $connectionName does not seem to be installed on your system.");
			try
			{
				if (\Z::getConfig($configString.'active') != 1)
					throw new \Sys\Exception("If you want to use this database connection name
							<b>$connectionName</b> then please make sure it is set to active in app/etc/local.xml");
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
				$this->driver = 
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

		public function query($query)
		{
			return $this->driver->query($query);
		}

		public function __destruct()
		{
			$this->driver = NULL;
		}

		public function getCachedFields($table)
		{
			return $this->fields[$table];
		}
		
		public function cacheFields($table)
		{
			if (!isset($this->fields[$table]))
			{
				//$table = $this->driver->quote($table);
				$table = htmlspecialchars($table);
				$statement = $this->driver->prepare("select column_name from information_schema.columns where table_name = '$table'");
				try
				{
					if($statement->execute())
					{
						$raw_column_data = $statement->fetchAll(\PDO::FETCH_ASSOC);

						foreach($raw_column_data as $outer_key => $array)
						{
							foreach($array as $inner_key => $value)
							{
								if (!(int)$inner_key)
								{
									$this->fields[$table][] = $value;
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
			//$table = $this->driver->quote($table);
			$table = htmlspecialchars($table);
			$statement = $this->driver->prepare("SELECT * FROM `$table` WHERE id = :id");
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
				//$statementData[':'.$key] = $this->driver->quote($value);
				$statementData[':'.$key] = $value;
			}
			$list = implode(",", $list);
			//$table = $this->driver->quote($table);
			$table = htmlspecialchars($table);
			$statement = $this->driver->prepare("UPDATE `$table` SET $list WHERE id = :id");
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
				$statementData[':'.$key] = $this->driver->quote($value);
			}
			$fields = implode(",", $fields);
			$values = implode(",", $values);
			//$table = $this->driver->quote($table);
			$table = htmlspecialchars($table);
			//var_dump($statementData);
			//die("INSERT INTO `$table` ($fields) VALUES ($values)");
			$statement = $this->driver->prepare("INSERT INTO `$table` ($fields) VALUES ($values)");
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
			//$table = $this->driver->quote($criteria['from']);
			$table = htmlspecialchars($criteria['from']);
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
			return (int)$this->driver->lastInsertId();
		}
	}
}
