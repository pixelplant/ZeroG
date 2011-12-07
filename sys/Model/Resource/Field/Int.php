<?php

/**
 * Int type field class
 *
 * @author radu.mogos
 */

namespace Sys\Model\Resource\Field
{
	class Int extends Base
	{
		/**
		 * Mimimum allowed string length by default
		 * @var <int>
		 */
		protected $minimumLength = 0;

		/**
		 * Maximum allowed string length by default
		 * @var <string>
		 */
		protected $maximumLength = 255;

		/**
		 * Minimum value this field can hold
		 * @var <int>
		 */
		protected $minimumValue = 0;

		/**
		 * Maximum value this field can hold
		 * @var <int>
		 */
		protected $maximumValue = 9999;

		public function __construct($name)
		{
			parent::__construct($name);
		}

		public function validateField()
		{
			if (strlen($this->value) < $this->minimumLength)
				$this->errors['minimumLength'] = $this->minimumLength;
			if (strlen($this->value) > $this->maximumLength)
				$this->errors['maximumLength'] = $this->maximumLength;
			if ($this->value < $this->minimumValue)
				$this->errors['minimumValue'] = $this->minimumValue;
			if ($this->value > $this->maximumValue)
				$this->errors['maximumValue'] = $this->maximumValue;
			return $this;
		}
	}
}

