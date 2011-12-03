<?php

namespace Sys
{
	class Exception extends \Exception
	{
		//protected $params;

		public function __construct($message = NULL, $parameters = array())
		{
			$message = sprintf($message, $parameters);
			parent::__construct($message);
			//$this->message = Z::__($message, $params);
		}
		
		public function __toString()
		{
			return "<pre>".get_class($this) . " '{$this->message}' in {$this->file} (line: {$this->line})\n"
                                . "{$this->getTraceAsString()}</pre>";
			//return "<p style=\"color:red\">".__CLASS__ . ": [{$this->code}]: {$this->message} </p>";
		}
	}
}