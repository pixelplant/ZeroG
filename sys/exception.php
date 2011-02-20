<?php

namespace Sys
{
	class Exception extends \Exception
	{
		//protected $params;

		public function __construct($message = NULL)
		{
			parent::__construct($message);
			//$this->message = Z::__($message, $params);
		}
		
		public function __toString()
		{
			return "<p style=\"color:red\">".__CLASS__ . ": [{$this->code}]: {$this->message} </p>";
		}
	}
}