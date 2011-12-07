<?php

namespace Sys\L10n
{
	class Currency
	{
		/**
		 * name of the currency: eg. USD for dollar
		 * @var <string>
		 */
		private $name = '';

		/**
		 * symbol of the currency: eg. $ for dollar
		 * @var <string>
		 */
		private $symbol = '';

		/**
		 * format for the singular version of the currency: eg. %s dollar
		 * @var <string>
		 */
		private $singleFormat = '';

		/**
		 * plural form of the currency:  eg. %s dollars
		 * @var <string>
		 */
		private $multiFormat = '';

		/**
		 * How many decimals to show after the comma.
		 * @var <integer>
		 */
		private $decimals = 2;

		/**
		 * Set the currency data
		 *
		 * @param <string> $name Name of the currency, eg: USD for dollar
		 * @param <string> $symbol Currency symbol. eg: $ for dollar
		 * @param <string> $singleFormat Text and format for singular form of the currency. eg: %s dollar
		 * @param <string> $multiFormat Text and format for plural form of the currency. eg: %s dollars
		 * @param <integer> $decimals Number of decimals to show after the comma, for float values
		 */
		public function __construct($name, $symbol, $singleFormat, $multiFormat, $decimals = 2)
		{
			$this->name = $name;
			$this->symbol = $symbol;
			$this->singleFormat = $singleFormat;
			$this->multiFormat = $multiFormat;
			$this->decimals = $decimals;
		}

		/**
		 * Get the currency name
		 * @return <string>
		 */
		public function getName()
		{
			return $this->name;
		}

		/**
		 * Set the currency name
		 * @param <string> $value
		 */
		public function setName($value)
		{
			$this->name = $value;
		}

		/**
		 * Get the currency symbol
		 * @return <string>
		 */
		public function getSymbol()
		{
			return $this->symbol;
		}

		/**
		 * Set the currency symbol
		 * @param <string> $value
		 */
		public function setSymbol($value)
		{
			$this->symbol = $value;
		}

		/**
		 * Return the singular form for the currency
		 * @return <string>
		 */
		public function getSingleFormat()
		{
			return $this->singleFormat;
		}

		/**
		 * Set the singular form for the currency
		 * @param <string> $value
		 */
		public function setSingleFormat($value)
		{
			$this->singleFormat = $value;
		}

		/**
		 * Get the plural form for the currency
		 * @return <string>
		 */
		public function getMultiFormat()
		{
			return $this->multiFormat;
		}

		/**
		 * Set the plural form for the currency
		 * @param <string> $value
		 */
		public function setMultiFormat($value)
		{
			$this->multiFormat = $value;
		}

		public function format($value)
		{
			$decimal_format = '%01.'.$this->decimals.'f';
			if ($value == 1)
				$format = $this->singleFormat;
			else
				$format = $this->multiFormat;
			$format = str_replace('%s', $decimal_format, $format);
			return sprintf($format, $value);
		}
	}
}

