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
		const INT = 'int';
		const VARCHAR = 'varchar';
		const TEXT = 'text';

		const OP_INSERT = 'create';
		const OP_UPDATE = 'alter';
		const OP_DELETE = 'drop';

		/**
		 * List of fields to create/modify in the table
		 * @var <array>
		 */
		protected $fields;

		protected $operation = '';

		public function addTable($table)
		{
			$this->operation = self::OP_INSERT;
			$this->table = $this->pdo->getTableName($table);
		}

		public function updateTable($table)
		{
			$this->operation = self::OP_UPDATE;
			$this->table = $this->pdo->getTableName($table);
		}

		public function deleteTable($table)
		{
			$this->operation = self::OP_DELETE;
			$this->table = $this->getTableName($table);
		}

		public function addField($name, $type)
		{
			$this->fields[$name] = $type;
		}

		public function execute()
		{
			$query = '';
			switch ($this->operation)
			{
				case OP_INSERT:
					$query = sprintf('%s TABLE %s', OP_INSERT, $this->table);
					break;
				case OP_UPDATE:
					$query = sprintf('%s TABLE %s', OP_UPDATE, $this->table);
					break;
				case OP_DELETE:
					$query = sprintf('%s TABLE %s', OP_DELETE, $this->table);
					break;
			}
			if ($query != '')
				$this->pdo->query($query);
			else
				throw new \Sys\Exception("The query in the installer %s is empty.", get_class($this));
		}
	}
}
