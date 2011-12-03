<?php

/**
 * Class used to install the database requirements per extension
 * for each particular version of this extension
 *
 * @author radu.mogos
 */

namespace App\Code\Core\ZeroG\Core\Model
{
	class Installer extends \Sys\Database\Model {
		const TYPE_INTEGER = 'int';
		const TYPE_SMALLINT = 'smallint';
		const TYPE_TINYINT = 'tinyint';
		const TYPE_TIMESTAMP = 'timestamp';
		const TYPE_VARCHAR = 'varchar';
		const TYPE_TEXT = 'text';

		const OP_INSERT = 'create';
		const OP_UPDATE = 'alter';
		const OP_DELETE = 'drop';

		/**
		 * List of table data
		 * @var <array>
		 */
		protected $tables;

		/**
		 * What operation do we execute on the table?
		 * @var <string>
		 */
		protected $operation = '';

		/**
		 * Current table on which we add/remove columns and/or execute queries
		 * @var <string>
		 */
		protected $currentTable = null;

		public function __construct()
		{
			parent::__construct('core_resource');
			$this->startSetup();
		}

		/**
		 * Called in the constructor, to set initial SQL queries
		 *
		 * @return Installer
		 */
		protected function startSetup()
		{
			$this->pdo->query("SET SQL_MODE='';
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
			$this->pdo->query("SET SQL_MODE=IFNULL(@OLD_SQL_MODE,'');
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
			$this->operation = self::OP_INSERT;
			$this->currentTable = $this->pdo->getTableName($table);
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
			$this->operation = self::OP_UPDATE;
			$this->currentTable = $this->pdo->getTableName($table);
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
			$this->operation = self::OP_DELETE;
			$this->currentTable = $this->pdo->getTableName($table);
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
			if ($this->currentTable == null)
				throw new Sys\Exception('addColumn must be called before a table is selected in the install script');
			$column = \Z::getModel('core/column');
			$column->setName($name);
			// This needs to be rewritten in the future to support other PDO's than MySQL
			if ($type == self::TYPE_TEXT && $length <= 255)
				$type = self::TYPE_VARCHAR;
			if (($type == self::TYPE_INTEGER || $type == self::TYPE_SMALLINT) && $length <= 1)
				$type = self::TYPE_TINYINT;
			$column->setType($type)
					->setLength($length)
					->setComment($comment)
					->mergeData($additionalData);
			// Push the column in the table/operation list
			$this->tables[$this->currentTable][$this->operation]['columns'][] = $column;
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
			$this->tables[$this->currentTable][$this->operation]['data'][] = $data;
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
			$this->tables[$this->currentTable][$this->operation]['comment'] = $comment;
			return $this;
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
			foreach ($this->tables as $table => $operations)
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
				$this->pdo->query($query);
				$this->endSetup();
			}
			else
				throw new \Sys\Exception("The query in the installer %s is empty.",
						get_class($this));
		}
	}
}
