<?php

/**
 * Varchar type field class
 *
 * @author radu.mogos
 */

namespace Sys\Model\Resource\Field
{
	class Varchar extends Base
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
			return $this;
		}
	}
}

