<?php

/**
 * Abstract base class for all fields
 *
 * @author radu.mogos
 */

namespace Sys\Model\Resource\Field
{
	abstract class Base
	{
		/**
		 * List of validation errors for this field
		 * @var <array> 
		 */
		protected $errors = array();

		/**
		 * The field's name
		 * @var <string>
		 */
		protected $name = '';

		/**
		 * The value this field holds
		 * @var <mixed>
		 */
		protected $value;

		/**
		 * Checks if a field validates all given conditions
		 * @param <mixed> $value
		 */
		abstract public function validateField();

		public function __construct($name, $value = NULL)
		{
			$this->value = $value;
			$this->name = $name;
			$this->errors = array();
		}

		/**
		 * Reset all errors for this field
		 */
		public function clearErrors()
		{
			empty($this->errors);
		}

		/**
		 * Retrieve all field validation errors
		 * @return <array>
		 */
		public function getErrors()
		{
			return $this->errors;
		}

		/**
		 * Get the field's name
		 * @return <string>
		 */
		public function getName()
		{
			return $this->name;
		}

		/**
		 * Set the field name
		 * @param <string> $value
		 */
		public function setName($value)
		{
			$this->name = $value;
		}

		/**
		 * Return the value of this field
		 * @return <mixed>
		 */
		public function getValue()
		{
			return $this->value;
		}

		/**
		 * Set the value this field holds
		 * @param <mixed> $value
		 */
		public function setValue($value)
		{
			$this->value = $value;
		}

		/**
		 * Set actions to be performed on this field (during validation, value change, etc)
		 * @param <array> $actions
		 */
		public function setActions($actions)
		{
			foreach ($actions as $action)
			{
				foreach ($action as $field => $fieldValue)
				{
					$this->$field = (string)$fieldValue;
				}
			}
		}
	}
}
?>
