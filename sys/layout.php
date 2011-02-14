<?php

/**
 * Base Layout class (loads and processes layout xml files)
 *
 * @author radu.mogos
 */
namespace Sys
{
	class Layout
	{
		/**
		 * The name of the xml layout file. The default xml file is page.xml
		 * @var <string>
		 */
		protected $file;

		/**
		 * A layout consists of an array of blocks
		 * @var <array>
		 */
		protected $blocks = array();

		/**
		 * The layout version number
		 * @var <string>
		 */
		protected $version;

		public function __construct($file)
		{
			$this->file = $file.'.xml';
			$this->loadLayout();
		}

		/**
		 * Read the layout from the XML file
		 */
		protected function loadLayout()
		{
			$xml = new \SimpleXMLElement(\App\Config\System::APP_DIR.'/views/layout/'.$this->file, NULL, TRUE);

			// make sure the xml layout file exists and is valid
			if (!$xml)
				throw new \Sys\Exception("Current layout ".$this->file." file does not exist or is not a valid xml");

			// get the version number and store it as a float
			// this way we can do version requirement checks in the future
			$this->version = (float)$xml["version"];

			// first we read the "default" block in the xml which holds the settings
			// for all the actions of this page
			$this->processSection($xml->default);
			// then we apply the custom action layout. for example, the page /cms/index
			// would have it's layout defined in "cms_index"
			$custom_page = \Sys\ZeroG::getParam('controller').'_'.\Sys\ZeroG::getParam('action');
			// if the tag is defined in the xml, process it, otherwise just use the default settings
			if (isset($xml->$custom_page))
				$this->processSection($xml->$custom_page);
		}

		/**
		 * Create the specific blocks for the specified section. A section by default
		 * links to the "default" xml tag, or to the "controller_action" tag, if it is defined
		 * @param <\SimpleXMLElement> $section
		 */
		protected function processSection(\SimpleXMLElement $section)
		{
			if ($section->block)
				$this->getBlocks($section->block);
			// thankfully references are not recursive
			if ($section->reference)
				$this->getReferences($section->reference);
			//print_r($this->blocks);
		}

		/**
		 * Recursively retrieve all blocks from a layout section
		 * @param \SimpleXMLElement $xml
		 * @return <type>
		 */
		protected function getBlocks(\SimpleXMLElement $xml, $parent = '')
		{
			// first we process and add the nodes that have children
			if (count($xml->block) > 0)
			{
				$temp = $parent;
				$name = ($parent == '') ? (string)$xml["name"] : $temp.'.'.(string)$xml["name"];
				$this->blocks[$name] = new \Sys\Layout\Block($name, $xml);
				// if it has a parent, add this block as a child
				//$lastParent = substr($name, 0, strrpos($name, "."));
				//if (strlen($lastParent) > 0)
				//	$this->blocks[$lastParent]->addChildren($this->blocks[$name]);
				if (strlen($temp) > 0)
					$this->blocks[$temp]->addChildren($this->blocks[$name]);
				foreach ($xml->block as $block)
				{
					$parent = $name;
					$this->getBlocks($block, $parent);
				}
			}
			// then we add the leaf nodes
			else
			{
				$name = ($parent == '') ? (string)$xml["name"] : $parent.'.'.(string)$xml["name"];
				$this->blocks[$name] = new \Sys\Layout\Block($name, $xml);
				// if it has a parent, add this block as a child
				//$lastParent = substr($name, 0, strrpos($name, "."));
				if (strlen($parent) > 0)
					$this->blocks[$parent]->addChildren($this->blocks[$name]);
			}
			// Pfew: I spent 4 hours writing this function. Recursion is a bitch
		}

		/**
		 * Process all references from the XML file
		 * @param \SimpleXMLElement $references
		 */
		protected function getReferences(\SimpleXMLElement $references)
		{
			foreach ($references as $reference)
			{
				if ($reference->block)
					$this->getBlocks($reference->block, (string)$reference["name"]);
				if ($reference->action)
				{
					foreach ($reference->action as $action)
					{
						$method = (string)$action["method"];
						$params = array();
						foreach ($action as $key => $value)
						{
							//$params[$key] = $value;
							$params[] = (string)$value;
						}
						call_user_func_array(array($this->getBlock((string)$reference["name"]), $method), $params);
					}
				}
			}
		}

		/**
		 * Render all blocks from the page
		 */
		public function render()
		{
			return $this->blocks['root']->render();
		}

		/**
		 * Return a Sys\Layout\Block referenced by nam
		 * @param <string> $name The block name
		 * @return <Sys\Layout\Block> Block instance reference
		 */
		public function getBlock($name)
		{
			if (isset($this->blocks[$name]))
				return $this->blocks[$name];
			return NULL;
		}
	}
}
