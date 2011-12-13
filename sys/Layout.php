<?php

namespace Sys
{
	/**
	* Base Layout class (loads and processes layout xml files)
	*
	* @author radu.mogos
	* @category Sys
	* @package Sys\Layout
	* @copyright Radu Mogos, www.pixelplant.ro
	*/
	class Layout
	{
		/**
		 * The name of the xml layout file. The default xml file is page.xml
		 * @var <string>
		 */
		protected $_file;

		/**
		 * A layout consists of an array of blocks
		 * @var <array>
		 */
		protected $_blocks = array();

		/**
		 * The layout version number
		 * @var <string>
		 */
		protected $_version;

		/**
		* Constructor
		*/
		public function __construct($file)
		{
			// always load page xml first
			$this->_file = 'page.xml';
			$this->_loadLayout();
			// then load current module's xml file
			/*if (\Z::registry('current_module')->getLayout())
			{
				$this->_file = \Z::registry('current_module')->getLayout();
				$this->loadLayout();
			}*/
		}

		protected function getPath($context = 'frontend')
		{
			return sprintf('app/design/%s/%s/%s/layout/',
					$context,
					\Z::getConfig('config/global/default/package'),
					\Z::getConfig('config/global/default/layout'));
		}

		/**
		 * Read the layout from the XML file
		 */
		protected function _loadLayout()
		{
			$custom_context = \Z::getContext();
			//$custom_context = \Z::getContext().'_'.\Z::getParam(\Z::getConfig('config/global/default/context/variable'));
			$hash = md5(\Z::getConfig('config/global/default/package').'/'.\Z::getConfig('config/global/default/layout') . $this->_file . $custom_context);
			$cacheXml = 'var/cache/serialized/xml_'.md5($hash).'.ser';
			/*if (file_exists($cacheXml))
			{
				$this->_blocks = unserialize(file_get_contents($cacheXml));
			}
			else*/
			{
				$xml = new \SimpleXMLElement($this->getPath().$this->_file, NULL, TRUE);
				// make sure the xml layout file exists and is valid
				if (!$xml)
					throw new \Sys\Exception('Current layout => %s file does not exist or is not a valid xml',
						$this->getPath().$this->_file);

				// get the version number and store it as a float
				// this way we can do version requirement checks in the future
				$this->_version = (float)$xml['version'];
				// first we read the "default" block in the xml which holds the settings
				// for all the actions of this page

				if (isset($xml->default))
					$this->_processSection($xml->default);
				// then we apply the custom action layout. for example, the page /cms/index
				// would have it's layout defined in "cms_index"
				//$custom_page = \Z::getContext();
				// if the tag is defined in the xml, process it, otherwise just use the default settings
				if (isset($xml->$custom_context))
					$this->_processSection($xml->$custom_context);;

				// cache everything...
				file_put_contents($cacheXml, serialize($this->_blocks));
			}
		}

		/**
		 * Create the specific blocks for the specified section. A section by default
		 * links to the "default" xml tag, or to the "controller_action" tag, if it is defined
		 *
		 * @param <\SimpleXMLElement> $section
		 */
		protected function _processSection(\SimpleXMLElement $section)
		{
			if ($section->block)
				$this->_getBlocks($section->block);
			// thankfully references are not recursive
			if ($section->reference)
				$this->_getReferences($section->reference);
			if ($section->remove)
			{
				$this->removeBlocks($section->remove);
			}
			//print_r($this->_blocks);
		}

		/**
		* Generates a block based on an XML node
		*
		* @param <\SimpleXMLElement> $xml
		*/
		private function _createBlock($xml)
		{
			$name = (string)$xml['name'];
			$type = (string)$xml['type'];
			/*$typeParts = explode("/", $type);
			foreach ($typeParts as $key => $value)
			{
				$typeParts[$key] = ucfirst($value);
			}*/
			$class = \Z::getConfig()->getBlockClass($type);
			//$type = "App\\Models\\Blocks\\".implode("\\", $typeParts);
			return new $class($name, $xml['template']);
		}

		/**
		 * Recursively retrieve all blocks from a layout section
		 *
		 * @param <\SimpleXMLElement> $xml
		 */
		protected function _getBlocks(\SimpleXMLElement $xml, $parent = '')
		{
			// first we process and add the nodes that have children
			if (count($xml->block) > 0)
			{
				$temp = $parent;
				$name = (string)$xml['name'];
				$this->_blocks[$name] = $this->_createBlock($xml);
				// if it has a parent, add this block as a child
				//$lastParent = substr($name, 0, strrpos($name, "."));
				//if (strlen($lastParent) > 0)
				//	$this->_blocks[$lastParent]->addChildren($this->_blocks[$name]);
				if (strlen($temp) > 0)
				{
					$this->_blocks[$name]->setParent($parent);
					$this->_blocks[$temp]->addChild($this->_blocks[$name]);
				}
				foreach ($xml->block as $block)
				{
					$parent = $name;
					$this->_getBlocks($block, $parent);
				}
				$this->_executeActions($xml);
			}
			// then we add the leaf nodes
			else
			{
				$name = (string)$xml['name'];
				$this->_blocks[$name] = $this->_createBlock($xml);
				//$this->_blocks[$name] = new \Sys\Layout\Block($name, $xml);
				// if it has a parent, add this block as a child
				//$lastParent = substr($name, 0, strrpos($name, "."));
				if (strlen($parent) > 0)
				{
					$this->_blocks[$name]->setParent($parent);
					$this->_blocks[$parent]->addChild($this->_blocks[$name]);
				}
				$this->_executeActions($xml);
			}
			// Pfew: I spent 4 hours writing this function. Recursion is a bitch
		}

		/**
		 * Process all references from the XML file
		 *
		 * @param <\SimpleXMLElement> $references
		 */
		protected function _getReferences(\SimpleXMLElement $references)
		{
			foreach ($references as $reference)
			{
				// if we have any new blocks added in the reference, process them
				if ($reference->block)
				foreach ($reference->block as $block)
				{
					$this->_getBlocks($block, (string)$reference['name']);
				}
				// if there are actions defined, process these too
				$this->_executeActions($reference);
			}
		}

		/**
		 * Remove all blocks marked by a <remove> tag in the layout xml
		 * Syntax: <remove name="BLOCK_NAME"/>
		 * 
		 * @param \SimpleXmlElement $blocks
		 */
		protected function _removeBlocks(\SimpleXmlElement $blocks)
		{
			// process all <remove> tags found
			foreach ($blocks as $block)
			{
				$name = (string)$block['name'];
				$this->_removeBlock($name);
			}
		}

		/**
		 * Execute all the action calls defined on a block or reference
		 * Used when we read the blocks and the references from the xml layout
		 *
		 * @param <mixed> $reference The xml tag containing the actions
		 */
		private function _executeActions($reference)
		{
			if ($reference->action)
			{
				foreach ($reference->action as $action)
				{
					$method = (string)$action['method'];
					$params = array();
					foreach ($action as $key => $value)
					{
						//$params[$key] = $value;
						$params[] = (string)$value;
					}
					call_user_func_array(array($this->getBlock((string)$reference['name']), $method), $params);
				}
			}
		}

		/**
		 * Render all blocks from the page
		 */
		public function render()
		{
			\Z::dispatchEvent('layout_render_before', array('object' => $this));
			return $this->_blocks['root']->render();
			\Z::dispatchEvent('layout_render_after', array('object' => $this));
		}

		/**
		 * Return a Sys\Layout\Block referenced by name
		 *
		 * @param <string> $name
		 * @return <\Sys\Layout\Block>
		 */
		public function getBlock($name)
		{
			if (isset($this->_blocks[$name]))
				return $this->_blocks[$name];
			throw
				new \Sys\Exception('The block with the name => %s was not found', $name);
			//return NULL;
		}

		/**
		 * Removes a block from the rendering layout
		 *
		 * @param <string> $name
		 */
		public function removeBlock($name)
		{
			// remove it from the parent's list
			if (isset($this->_blocks[$name]))
			{
				$this->getBlock($this->_blocks[$name]->getParent())->unsetChild($name);
				unset($this->_blocks[$name]);
			}
		}
	}
}
