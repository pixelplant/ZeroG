<?php

/**
 * Holds a column data for a table, either for creation, update or drop operations
 *
 * @author radu.mogos
 */

namespace App\Code\Core\ZeroG\Core\Model
{
	class Column extends \Sys\Model {
		
		public function getDefault()
		{
			$value = $this->getData('default');
			if ($value == '')
				return '';
			if (is_string($value) && $value[0] != "'")
				$this->setData('default', "'".$value."'");
			return $this->getData('default');
		}
	}
}
?>
