<?php

namespace Sys\Config\Module
{
	class Item
	{
		protected $reference;

		protected $class;

		protected $path;

		/**
		 * The module it belongs to
		 * @var <Sys\Config\Module>
		 */
		protected $parent;

		public function __construct($reference, $module)
		{
			$this->reference = $reference;
			$this->class = $class;
			$this->path = $path;
			$this->parent = $module;
		}

		protected function setPath()
		{
			return sprintf('App\\Code\\%s\\%s\\%s\\%s\\%s',
					$this->parent->getCodePool(),
					$this->parent->getDeveloper(),
					$this->parent->getExtension(),
					ucfirst($this->type),
					'');
		}
	}
}