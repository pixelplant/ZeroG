<?php

namespace Sys\Model
{
	abstract class Resource
	{
		/**
		 * Array of Sys\Model\Resource\Field elements
		 * @var <array>
		 */
		//protected $fields = array();

		/**
		 * The name of the xml resource that holds the fields definition
		 * @var <string>
		 */
		//protected $resourceName = '';

		public function __construct()
		{
			$this->_construct();
		}

		abstract protected function _construct();
		abstract protected function _getReadAdapter();
		abstract protected function _getWriteAdapter();

		private function loadResource($descriptionFile)
		{
			$xml = new \SimpleXMLElement($descriptionFile, NULL, TRUE);

			// make sure the xml is loaded
			if (!$xml)
				throw new \Sys\Exception("Cannot find XML file for resource: ".$this->resourceName);

			// create every field found in the xml resource file
			foreach ($xml->field as $field)
			{
				$fieldName = (string)$field->name;
				$class = 'Sys\\Model\\Resource\\Field\\'.ucfirst((string)$field->type);
				$this->fields[$fieldName] = new $class($fieldName);
				$this->fields[$fieldName]->setActions($field->actions);
			}
		}

		/**
		 * Validate all fields from this resource
		 * @return <bool> returns if fields are valid or not
		 */
		public function validateFields()
		{
			$hasErrors = 0;
			foreach ($this->fields as $field)
			{
				$field->clearErrors();
				$hasErrors += sizeof($field->validateField()->getErrors());
			}
			return (bool)!$hasErrors;
		}

		/**
		 * Return a field data by its name
		 * @param <string> $fieldName The name of the field to retrieve
		 * @return <mixed> returns an instance of the field type (varchar, int, etc)
		 */
		public function getField($fieldName)
		{
			return $this->fields[$fieldName];
		}

		/**
		 * Return all the fields for this resource
		 *
		 * @return <array>
		 */
		public function getFields()
		{
			return $this->fields;
		}
	}
}
