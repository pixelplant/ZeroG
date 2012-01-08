<?php

namespace Sys
{

	/**
	 * Description of Cache
	 *
	 * @author radu.mogos
	 */
	class Cache
	{
		const TYPE_DEFAULT = 'serialized';
		const TYPE_CSS     = 'css';
		const TYPE_JS      = 'js';
		const TYPE_AJAX    = 'ajax';
		const TYPE_LOCALE  = 'locale';

		/**
		 * Cache type
		 * 
		 * @var <string>
		 */
		protected $_type;

		/**
		 * Data to serialize
		 * 
		 * @var <array>
		 */
		protected $_data;

		public function __construct($type = self::TYPE_DEFAULT)
		{
			$this->_type = $type;
		}

		public function save()
		{
			fopen('var/cache/'.$this->_type);
			$content = \serialize($this->_data);
		}

		public function load()
		{
			$this->_data = \unserialize($content);
			return $this->_data;
		}
	}
}
