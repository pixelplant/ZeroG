<?php

/**
 * Class used to install the database requirements per extension
 * for each particular version of this extension
 *
 * @author radu.mogos
 */

namespace App\Code\Core\ZeroG\Core\Model
{
	class Installer extends \Sys\Model {
		const TYPE_INTEGER   = 'int';
		const TYPE_SMALLINT  = 'smallint';
		const TYPE_TINYINT   = 'tinyint';
		const TYPE_TIMESTAMP = 'timestamp';
		const TYPE_DATETIME  = 'datetime';
		const TYPE_VARCHAR   = 'varchar';
		const TYPE_TEXT      = 'text';
		const TYPE_BLOB      = 'blob';
		const TYPE_ENUM      = 'enum';

		const OP_INSERT = 'create';
		const OP_UPDATE = 'alter';
		const OP_DELETE = 'drop';

		/**
		 * List of table data
		 * @var <array>
		 */
		protected $_tables;

		/**
		 * What operation do we execute on the table?
		 * @var <string>
		 */
		protected $_operation = '';

		/**
		 * Current table on which we add/remove columns and/or execute queries
		 * @var <string>
		 */
		protected $_currentTable = null;

		protected $_pdo;

		protected function _construct()
		{
			$this->_pdo = \Z::getDatabaseConnection();
			$this->startSetup();
		}

		/**
		 * Called in the constructor, to set initial SQL queries
		 *
		 * @return Installer
		 */
		protected function startSetup()
		{
			$this->_pdo->query("SET SQL_MODE='';
				SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
				SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
				");
			return $this;
		}

		/**
		 * Called in the installer's RUN() method to reset the settings done by
		 * startSetup() above
		 *
		 * @return Installer
		 */
		protected function endSetup()
		{
			$this->_pdo->query("SET SQL_MODE=IFNULL(@OLD_SQL_MODE,'');
				SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS=0, 0, 1);
				");
			return $this;
		}

		/**
		 * Activate the operation to create a new table
		 *
		 * @param <string> $table New table name
		 * @return Installer
		 */
		public function newTable($table)
		{
			$this->_operation = self::OP_INSERT;
			$this->_currentTable = $this->_pdo->getTableName($table);
			return $this;
		}

		/**
		 * Activate the operation to update an existing table
		 *
		 * @param <string> $table Existing table name
		 * @return Installer
		 */
		public function updateTable($table)
		{
			$this->_operation = self::OP_UPDATE;
			$this->_currentTable = $this->_pdo->getTableName($table);
			return $this;
		}

		/**
		 * Activate the operation to delete an existing table
		 *
		 * @param <string> $table Existing table
		 * @return Installer
		 */
		public function deleteTable($table)
		{
			$this->_operation = self::OP_DELETE;
			$this->_currentTable = $this->_pdo->getTableName($table);
			return $this;
		}

		/**
		 * Sets all the column data required to generate the creation sql query
		 *
		 * @param <string> $name Column name
		 * @param <string> $type Column type (eg: varchar, int, text, blob, etc)
		 * @param <int> $length Column length (eg: 200 for varchar(200))
		 * @param <array> $additionalData Additional settings, if it is primary, unsigned, etc
		 * @param <string> $comment Column comment to show in the database
		 * @return Installer
		 */
		public function addColumn($name, $type, $length, $additionalData, $comment = '')
		{
			if ($this->_currentTable == null)
				throw new Sys\Exception('addColumn must be called before a table is selected in the install script');
			$column = \Z::getModel('core/column');
			$column->setName($name);
			// This needs to be rewritten in the future to support other PDO's than MySQL
			if ($type == self::TYPE_TEXT && $length <= 255 && $length !== null)
				$type = self::TYPE_VARCHAR;
			if ($type == self::TYPE_INTEGER && $length === null)
				$length = 11;
			if (($type == self::TYPE_INTEGER || $type == self::TYPE_SMALLINT) && $length <= 1)
				$type = self::TYPE_TINYINT;
			if ($type == self::TYPE_ENUM)
				$type = self::TYPE_ENUM.'('.implode(',', $additionalData['values']).')';
			$column->setType($type)
					->setLength($length)
					->setComment($comment)
					->mergeData($additionalData);
			// Push the column in the table/operation list
			$this->_tables[$this->_currentTable][$this->_operation]['columns'][] = $column;
			return $this;
		}

		/**
		 * Add a new row of data for the current table
		 *
		 * @param <array> $data Data to insert in the currently selected table
		 * @return Installer
		 */
		public function insertData($data)
		{
			$this->_tables[$this->_currentTable][$this->_operation]['data'][] = $data;
			return $this;
		}

		/**
		 * Set the table comment
		 *
		 * @param <string> $comment
		 * @return Installer
		 */
		public function setComment($comment = '')
		{
			$this->_tables[$this->_currentTable][$this->_operation]['comment'] = $comment;
			return $this;
		}

		/**
		 * Returns the table referenced by this resource
		 *
		 * @param <string> $resourceName
		 * @return <string>
		 */
		public function getResourceTable($resourceName)
		{
			return \Z::getConfig()->getResourceTable($resourceName);
		}

		/**
		 * Generate all the sql queries based on the data gathered by the
		 * installer so far.
		 *
		 * @return <string> The final query to be executed by PDO
		 */
		private function generateQueries()
		{
			$query = '';
			foreach ($this->_tables as $table => $operations)
			{
				foreach ($operations as $operation => $data)
				{
					switch ($operation)
					{
						case self::OP_INSERT:
							$columnsQuery = array();
							if (isset($data['columns']))
							foreach ($data['columns'] as $column)
							{
								$type = ($column->getLength() != null) 
										? $column->getType().'('.$column->getLength().')'
										: $column->getType();
								$unsigned = ($column->getUnsigned() === TRUE)
										? 'UNSIGNED'
										: '';
								$null = ($column->getNullable() === TRUE)
										? 'NULL'
										: 'NOT NULL';
								$default = ($column->getDefault())
										? 'DEFAULT '.$column->getDefault()
										: '';
								$primary = ($column->getPrimary() === TRUE)
										? ',PRIMARY KEY (`'.$column->getName().'`)'
										: '';
								$identity = ($column->getIdentity() === TRUE)
										? 'AUTO_INCREMENT'
										: '';
								$columnsQuery[] = sprintf('%s %s %s %s %s %s %s',
											$column->getName(),
											$type,
											$unsigned,
											$identity,
											$null,
											$default,
											$primary
										);
							}
							$insertsQuery = array();
							if (isset($data['data']))
							{
								// go through each row we have to insert
								foreach ($data['data'] as $row)
								{
									// Add the string separator for strings
									foreach ($row as $key => $value)
									{
										//$value = $this->_pdo->quote($value);
										if (gettype($value) == 'string')
										{
											$row[$key] = "'$value'";
										}
									}
									$insertsQuery[] = sprintf('INSERT INTO `%s` (%s) VALUES (%s); ',
											$table, 
											implode(",", array_keys($row)),
											implode(",", array_values($row)));
								}
							}
							$tableComment = isset($data['comment'])
								? htmlspecialchars($data['comment'])
								: '';
							$query .= sprintf('CREATE TABLE `%s` (%s) ENGINE=InnoDB DEFAULT CHARSET = utf8 COMMENT=\'%s\'; %s ',
									$table,
									implode(", ", $columnsQuery),
									$tableComment,
									implode(" ", $insertsQuery));
							break;
					}
				}
			}
			return $query;
		}

		/**
		 * Try to execute all the queries generated so far in the install script
		 */
		public function run()
		{
			$query = $this->generateQueries();
			if ($query != '')
			{
				echo $query;
				try
				{
					//if ($this->_pdo->query($query) === FALSE)
					$this->_pdo->query($query);
				}
				catch (\PDOException $e)
				{
					throw new \Sys\Exception('The installer query is invalid: %s', $e->getMessage());
				}
				$this->endSetup();
			}
			else
				throw new \Sys\Exception('The query in the installer %s is empty.',
						$this->getClassName());
		}
	}
}
