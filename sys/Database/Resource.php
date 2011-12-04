<?php

/**
 * Description of Resource
 *
 * @author radu.mogos
 */
namespace Sys\Database
{
	class Resource extends \Sys\Model\Resource
	{
		/**
		 * Table field representing the id
		 *
		 * @var <string>
		 */
		protected $_idField;

		/**
		 * Table name the resource links to
		 *
		 * @var <string>
		 */
		protected $_table;

		/**
		 * Cached list of table fields
		 *
		 * @var <array>
		 */
		protected $_tableFields;

		/**
		 * We do not declare it as static cause in the future the same pdo connection
		 * might link to different PDO drivers or to multiple PDO connections using
		 * the same driver (eg: connecting to 3 different mysql databases at a time)
		 * In the future I also plan to add 2 different adapters per model for writing and reading data
		 *
		 * @var <\Sys\Database\Pdo> A reference to the db connection
		 */
		protected $_pdo = NULL;
		
		protected function _init($table, $idField = null)
		{
			$this->_pdo = \Z::getDatabaseConnection('default_setup');
			if ($idField === null)
				$idField = 'id';
			$this->_idField = $idField;
			$this->_table = \Z::getConfig()->getResourceTable($table);
			$this->_tableFields = $this->_pdo->cacheFields($this->_table);
		}

		protected function _construct() {}
		protected function _getReadAdapter() {}
		protected function _getWriteAdapter() {}
	}
}
