<?php

/**
 * Class used to install the database requirements per extension
 * for each particular version of this extension
 *
 * @author radu.mogos
 */

namespace App\Code\Core\ZeroG\Core\Models
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

		protected function startSetup()
		{
			$this->pdo->query("SET SQL_MODE='';
				SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
				SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
				");
			return $this;
		}

		protected function endSetup()
		{
			$this->pdo->query("SET SQL_MODE=IFNULL(@OLD_SQL_MODE,'');
				SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS=0, 0, 1);
				");
			return $this;
		}

		public function newTable($table)
		{
			$this->operation = self::OP_INSERT;
			$this->currentTable = $this->pdo->getTableName($table);
			return $this;
		}

		public function updateTable($table)
		{
			$this->operation = self::OP_UPDATE;
			$this->currentTable = $this->pdo->getTableName($table);
			return $this;
		}

		public function deleteTable($table)
		{
			$this->operation = self::OP_DELETE;
			$this->currentTable = $this->pdo->getTableName($table);
			return $this;
		}

		public function addColumn($name, $type, $length, $columnData, $comment = '')
		{
			if ($this->currentTable == null)
				throw new Sys\Exception('addColumn must be called before a table is selectioned in the install script');
			$column = \Z::getModel('core/column');
			$column->setName($name);
			// This needs to be rewritten in the future to support other PDO's than MySQL
			if ($type == self::TYPE_TEXT && $length <= 255)
				$type = self::TYPE_VARCHAR;
			if (($type == self::TYPE_INTEGER || $type == self::TYPE_SMALLINT) && $length <= 1)
				$type = self::TYPE_TINYINT;
			$column->setType($type)
				   ->setLength($length)
				   ->mergeData($columnData)
				   ->setComment($comment);
			// Push the column in the table/operation list
			$this->tables[$this->currentTable][$this->operation]['columns'][] = $column;
			return $this;
		}

		public function insertData($data)
		{
			$this->tables[$this->currentTable][$this->operation]['data'][] = $data;
			return $this;
		}

		public function setComment($comment = '')
		{
			$this->tables[$this->currentTable][$this->operation]['comment'] = $comment;
			return $this;
		}

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

		public function run()
		{
			$query = $this->generateQueries();
			if ($query != '')
			{
				$this->pdo->query($query);
				$this->endSetup();
			}
			else
				throw new \Sys\Exception("The query in the installer %s is empty.", get_class($this));
		}
	}
}
