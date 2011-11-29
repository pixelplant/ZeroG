<?php
/**
 * Base Block class, used by layouts
 *
 * @author radu.mogos
 */

namespace Sys\Layout
{

	class Block
	{
		/**
		 * Holds an array of \Sys\Block chidren
		 * @var <array> 
		 */
		protected $children = array();

		/**
		 * The parent name this block belongs to
		 * @var <string>
		 */
		protected $parent;

		/**
		 * The block's name
		 * @var <string>
		 */
		protected $name;

		/**
		 * The template name used by the block for content rendering, if required
		 * @var <string>
		 */
		protected $template;
		
		/**
		 * The actual template resource (php code for the view)
		 * @var <string>
		 */
		protected $templateResource;

		/**
		 * The PHP/HTML code loaded from the block's template
		 * @var <string>
		 */
		protected $code;

		public function __construct($name, \SimpleXMLElement $xml)
		{
			$this->children = array();
			$this->parent = '';
			$this->name = $name;
			$this->templateResource = NULL;
			$this->code = NULL;
			$this->setTemplate((string)$xml["template"]);
		}

		/**
		 * Adds a child to the children list
		 * @param \Sys\Layout\Block $value 
		 */
		public function addChild(\Sys\Layout\Block $value)
		{
			$this->children[$value->getName()] = $value;
		}

		// processing

		/**
		 * Get the relative view path
		 *
		 * @return <string> the relative path to the app/views directory
		 */
		protected function getPath()
		{
			return sprintf('app/design/frontend/%s/%s/template/',
					\Z::getConfig('config/global/default/package'),
					\Z::getConfig('config/global/default/template'));
		}

		/**
		 * @param <string> $template the location + name of the template file
		 * @return <Sys\Layout\Block> reference to the current instance
		 */
		public function loadTemplate($template = '')
		{
			if ($template != '')
				$this->template = $this->getPath().$template;
			// old code, where we first loaded the php/html code
			/*if ($template != '')
			{
				$this->template = $this->getPath().$template;
				$filesize = filesize($this->template);
				$file = fopen($this->template, 'r');
				$this->code = fread($file, $filesize);
				fclose($file);
			}*/
			//else
			//	throw new \Sys\Exception('Block file missing: Please specify the block template filename!');
			return $this;
		}

		/**
		 * Render the current php/html block
		 * @return <string> The final generated HTML code
		 */
		public function render()
		{
			if ($this->template != '')
			{
				ob_start();
				/*eval("?>".$this->code);*/
				if ($this->code == NULL)
					include $this->template;
				else
					eval("?>".$this->code);
				//$renderedCode = ob_get_contents();
				//ob_end_clean();
				$renderedCode = ob_get_clean();
				return $renderedCode;
			}
			else
				return '';
		}

		/**
		 * Render a block and all it's children
		 * @param <string> $childName The name of the parent block
		 * @return <string> The HTML code generated by executing all the children blocks
		 */
		public function getChildHtml($childName)
		{
			if (!array_key_exists($childName, $this->children))
			{
				//throw new \Sys\Exception("Error in: $this->template. Make sure the child block '$childName' with parent block '$this->name' is defined in the xml file.");
				return;
			}
			$child = $this->children[$childName];
			$html = '';
			/*if ($child->getChildrenCount() > 0)
			{
				foreach ($child->getChildren() as $block)
				{
					$html .= $block->render();
				}
			}
			else*/
				$html = $child->render();
			return $html;
		}

		/**
		 * Return the number of children blocks this parent block has
		 * @param <string> $childName If you want to return the block count for another block, instead of the current one, specify it's name
		 * @return <int> Number of children blocks
		 */
		public function getChildrenCount($childName = '')
		{
			if ($childName != '')
			{
				return count($this->children[$childName]->getChildren());
			}
			return count($this->children);
		}

		/**
		 * Return a child block by it's name
		 * @param <string> $name The name of the child block to return
		 * @return <Sys\Layout\Block> An instance of Block classs
		 */
		public function getChild($name)
		{
			return $this->children[$name];
		}

		/**
		 * Remove a child from the list of children blocks
		 * @param <string> $name The child name to remove
		 */
		public function unsetChild($name)
		{
			unset($this->children[$name]);
		}

		// getters, setters

		/**
		 * Return all the children blocks this block has
		 * @return <array>
		 */
		public function getChildren()
		{
			return $this->children;
		}

		/**
		 * Set all the block children for this block
		 * @param <array> $value An array of \Sys\Layout\Block instances
		 */
		public function setChildren($value)
		{
			$this->children = $value;
		}

		/**
		 * Sets the block's name
		 * @param <string> $value
		 */
		public function setName($value)
		{
			$this->name = $value;
		}

		/**
		 * Returns the block's name
		 * @return <string>
		 */
		public function getName()
		{
			return $this->name;
		}

		/**
		 * Sets the template file for this block and ALSO loads the template code
		 * @param <string> $value The path to the php template. eg: 'page/left.phtml'
		 */
		public function setTemplate($value)
		{
			$this->loadTemplate($value);
		}

		/**
		 * Returns the template filename used by this block
		 * @return <string> eg: 'page/left.phtml'
		 */
		public function getTemplate()
		{
			return $this->template;
		}

		/**
		 * Return the PHP/Html code for this block
		 * @return <string>
		 */
		public function getCode()
		{
			return $this->code;
		}

		/**
		 * Set the PHP/Html code for this block
		 * @param <string> $value
		 */
		public function setCode($value)
		{
			$this->code = $value;
		}

		/**
		 * Return the parent block name
		 * @return <string>
		 */
		public function getParent()
		{
			return $this->parent;
		}

		/**
		 * Set the parent block name
		 * @param <string> $value
		 */
		public function setParent($value)
		{
			$this->parent = $value;
		}

		// shortcuts to be used in every block phtml file

		/**
		 * Return a specific helper
		 * @param <string> $name
		 * @return <Object>
		 */
		public function helper($name)
		{
			return \Z::getHelper($name);
		}

		/**
		 * Return a translated label
		 *
		 * @param <string> $label the label to translate
		 * @param <string> $module the name of the module holding the translation
		 * @return <string> The translation for the curent locale
		 */
		public function __($label, $module = 'global')
		{
			return \Z::getLocale()->__($label, $module);
		}

		/**
		 * Returns an absolute url containing the module+controller+action to be called
		 * @param <string> $path
		 * @return <string>
		 */
		public function getUrl($path)
		{
			return $this->helper('Sys\Helper\Html')->url($path);
		}

		/**
		 * Returns the absolute path to a resource (image, file, etc)
		 * located in the current package/theme/skin directory
		 * @param <string> $resource
		 * @return <string>
		 */
		public function getSkinUrl($resource)
		{
			return $this->helper('Sys\Helper\Html')->skinUrl($resource);
		}
	}
}
