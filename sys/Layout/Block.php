<?php
/**
 * Base Block class, used by layouts
 *
 * @author radu.mogos
 */

namespace Sys\Layout
{

	class Block extends \Sys\Model
	{
		/**
		 * Holds an array of \Sys\Block chidren
		 * 
		 * @var <array> 
		 */
		protected $_children = array();

		/**
		 * The parent this block belongs to
		 *
		 * @var <\Sys\Layout\Block>
		 */
		protected $_parent;

		/**
		 * The block's name
		 *
		 * @var <string>
		 */
		protected $_name;

		/**
		 * The template name used by the block for content rendering, if required
		 *
		 * @var <string>
		 */
		protected $_template;
		
		/**
		 * The actual template resource (php code for the view)
		 *
		 * @var <string>
		 */
		protected $_templateResource;

		/**
		 * The PHP/HTML code loaded from the block's template
		 *
		 * @var <string>
		 */
		protected $_code;

		/**
		 * The module name this block is linked to
		 *
		 * @var <string>
		 */
		protected $_moduleName;

		/**
		 * Block type, eg: 'core/text'
		 *
		 * @var <string>
		 */
		protected $_type;

		/**
		 * Block type identifier (the first part of $this->_type)
		 * @var <type>
		 */
		protected $_identifier;

		/**
		 * Event prefix used when loading blocks
		 * 
		 * @var <type>
		 */
		protected $_eventPrefix = 'block';

		/**
		 * Reference to the parent layout it belongs to
		 * @var <Sys\Layout>
		 */
		protected $_layout;

		/**
		 * Create a new Block
		 *
		 * @param <Sys\Layout> $layout The layout object it belongs to
		 * @param <string> $name Block name, eg: content
		 * @param <string> $type Block type, eg: core/base
		 * @param <string> $template Block template file
		 */
		public function __construct($layout, $name, $type, $template = '')
		{
			$this->_layout = $layout;
			$this->_children = array();
			$this->_parent = null;
			$this->_name = $name;
			$this->_templateResource = NULL;
			$this->_code = NULL;
			$this->_type = $type;
			$parts = explode('/', $type);
			$this->_identifier = $parts[0];
			$this->setTemplate($template);
			$this->_setModuleName();

			$this->_construct();
		}

		/**
		 * Please overwrite this in your extended classes and not the default
		 * constructor
		 */
		protected function _construct()
		{
		}

		/**
		 * Link the block to it's calling module so we know what translation
		 * to use
		 */
		protected function _setModuleName()
		{
			$this->_moduleName = \Z::getConfig()->getBlock($this->_identifier)->getModule();
		}

		/**
		 * Adds a child to the children list
		 * @param \Sys\Layout\Block $value 
		 */
		public function addChild(\Sys\Layout\Block $value)
		{
			$value->setParent($this);
			$this->_children[$value->getName()] = $value;
		}

		/**
		 * Set a block to be it's child
		 * @param <string> $name Child name
		 * @param \Sys\Layout\Block $value Child block
		 */
		public function setChild($name, \Sys\Layout\Block $value)
		{
			$value->setParent($this);
			$this->_children[$name] = $value;
		}

		// processing

		/**
		 * Get the relative view path
		 *
		 * @return <string> the relative path to the app/views directory
		 */
		protected function getPath($location = 'adminhtml')
		{
			return sprintf('app/design/%s/%s/%s/template/',
					$location,
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
				$this->_template = $this->getPath().$template;
			// old code, where we first loaded the php/html code
			/*if ($template != '')
			{
				$this->_template = $this->getPath().$template;
				$filesize = filesize($this->_template);
				$file = fopen($this->_template, 'r');
				$this->_code = fread($file, $filesize);
				fclose($file);
			}*/
			//else
			//	throw new \Sys\Exception('Block file missing: Please specify the block template filename!');
			return $this;
		}

		protected function _beforeToHtml() {}
		protected function _afterToHtml() {}

		/**
		 * Render the current php/html block
		 * @return <string> The final generated HTML code
		 */
		public function render()
		{
			\Z::dispatchEvent($this->_eventPrefix.'_block_render_before', array('object' => $this));
			\Z::dispatchEvent('block_render_before', array('object' => $this));
			$code = '';
			if ($this->_template != NULL || $this->_code != NULL)
			{
				$this->_beforeToHtml();
				ob_start();
				if ($this->_code == NULL)
				{
					include $this->_template;
				}
				else
					echo $this->_code;
				//$renderedCode = ob_get_contents();
				//ob_end_clean();
				$renderedCode = ob_get_clean();
				if ($this->getShowTemplateHints())
				{
					$renderedCode = $this->wrapTemplateHints($renderedCode);
				}
				$code = $renderedCode;
				$this->_afterToHtml();
			}
			else
				$code = '';
			\Z::dispatchEvent($this->_eventPrefix.'_block_render_after', array('object' => $this));
			\Z::dispatchEvent('block_render_after', array('object' => $this));
			return $code;
		}

		/**
		 * Render a block and all it's children
		 * @param <string> $childName The name of the parent block
		 * @return <string> The HTML code generated by executing all the children blocks
		 */
		public function getChildHtml($childName)
		{
			if (!array_key_exists($childName, $this->_children))
			{
				//throw new \Sys\Exception("Error in: $this->_template. Make sure the child block '$childName' with parent block '$this->_name' is defined in the xml file.");
				return;
			}
			$child = $this->_children[$childName];
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
				return count($this->_children[$childName]->getChildren());
			}
			return count($this->_children);
		}

		/**
		 * Return a child block by it's name
		 * @param <string> $name The name of the child block to return
		 * @return <Sys\Layout\Block> An instance of Block classs
		 */
		public function getChild($name)
		{
			return $this->_children[$name];
		}

		/**
		 * Remove a child from the list of children blocks
		 * @param <string> $name The child name to remove
		 */
		public function unsetChild($name)
		{
			unset($this->_children[$name]);
		}

		// getters, setters

		/**
		 * Return all the children blocks this block has
		 * @return <array>
		 */
		public function getChildren()
		{
			return $this->_children;
		}

		/**
		 * Set all the block children for this block
		 * @param <array> $value An array of \Sys\Layout\Block instances
		 */
		public function setChildren($value)
		{
			$this->_children = $value;
		}

		/**
		 * Sets the block's name
		 * @param <string> $value
		 */
		public function setName($value)
		{
			$this->_name = $value;
			return $this;
		}

		/**
		 * Returns the block's name
		 * @return <string>
		 */
		public function getName()
		{
			return $this->_name;
		}

		/**
		 * Sets the template file for this block and ALSO loads the template code
		 * @param <string> $value The path to the php template. eg: 'page/left.phtml'
		 */
		public function setTemplate($value)
		{
			$this->loadTemplate($value);
			return $this;
		}

		/**
		 * Returns the template filename used by this block
		 * @return <string> eg: 'page/left.phtml'
		 */
		public function getTemplate()
		{
			return $this->_template;
		}

		/**
		 * Return the PHP/Html code for this block
		 * @return <string>
		 */
		public function getHtml()
		{
			return $this->_code;
		}

		/**
		 * Set the PHP/Html code for this block
		 * @param <string> $value
		 */
		public function setHtml($value)
		{
			$this->_code = $value;
			return $this;
		}

		/**
		 * Return the parent block name
		 * @return <string>
		 */
		public function getParent()
		{
			return $this->_parent;
		}

		/**
		 * Set the parent block
		 * @param <\Sys\Layout\Block> $value
		 */
		public function setParent($value)
		{
			$this->_parent = $value;
			return $this;
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
		 * @return <string> The translation for the curent locale
		 */
		public function __($label)
		{
			$arguments = func_get_args();
			return \Z::getLocale()->__($label, $this->_moduleName, $arguments);
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
		 * Get Request data
		 *
		 * @return <array>
		 */
		public function getRequest()
		{
			return \Z::getRequest();
		}

		/**
		 * Returns the absolute path to a resource (image, file, etc)
		 * located in the current package/theme/skin directory
		 * @param <string> $resource
		 * @return <string>
		 */
		public function getSkinUrl($resource = '')
		{
			return $this->helper('Sys\Helper\Html')->skinUrl($resource);
		}

		public function getMediaUrl($resource = '')
		{
			return $this->helper('Sys\Helper\Html')->mediaUrl($resource);
		}

		/**
		 * Return current layout object
		 * 
		 * @return <\Sys\Layout>
		 */
		public function getLayout()
		{
			//return \Z::getController()->getLayout();
			return $this->_layout;
		}

		public function getParam($name, $defaultValue = null)
		{
			return \Z::getRequest()->getParam($name, $defaultValue);
		}

		/**
		 * Are path hints enabled? If so, then write the block data too...
		 * 
		 * @return <bool>
		 */
		private function getShowTemplateHints()
		{
			return \Z::getConfig('config/global/default/developer/block_hints');
		}

		private function wrapTemplateHints($code)
		{
			$code = sprintf('<div style="border: 1px solid red; margin: 1px"><div style="padding: 3px; background: red !important; color: white !important; font-size: 12px; font-weight: normal; text-shadow: none"><span style="float:left">%s</span> <span style="float:right">%s</span><p style="clear:both"></p></div> %s</div>',
					get_class($this),
					$this->_template,
					$code);
			return $code;
		}

		protected function _escape($text)
		{
			return htmlspecialchars($text);
		}
	}
}
