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
		 * @var <type>
		 */
		protected $code;

		public function __construct($name, \SimpleXMLElement $xml)
		{
			$this->children = array();
			$this->name = $name;
			$this->templateResource = NULL;
			$this->code = NULL;
			$this->setTemplate((string)$xml["template"]);
		}

		/**
		 * Adds a children to the children list
		 * @param \Sys\Layout\Block $value 
		 */
		public function addChildren(\Sys\Layout\Block $value)
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
			return \App\Config\System::APP_DIR.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'blocks'.DIRECTORY_SEPARATOR;
		}

		/**
		 * @param <string> $template the location + name of the template file
		 * @return <Sys\Layout\Block> reference to the current instance
		 */
		public function loadTemplate($template = '')
		{
			if ($template != '')
			{
				$this->templateFile = $this->getPath().$template;
				$filesize = filesize($this->templateFile);
				$file = fopen($this->templateFile, 'r');
				$this->code = fread($file, $filesize);
				fclose($file);
			}
			//else
			//	throw new \Sys\Exception('Block file missing: Please specify the block template filename!');
			return $this;
		}

		public function render()
		{
			if ($this->code !== NULL)
			{
				ob_start();
				eval("?>".$this->code);
				$renderedCode = ob_get_contents();
				ob_end_clean();
				return $renderedCode;
			}
			else
				return '';
		}

		public function getChildHtml($childName)
		{
			//print_r($this->children); die();
			if (!\array_key_exists($childName, $this->children))
				throw new \Sys\Exception("The block $childName is not defined in the xml file");
			$child = $this->children[$childName];
			$html = '';
			if ($child->getChildrenCount() > 0)
			{
				foreach ($child->getChildren() as $block)
				{
					$html .= $block->render();
				}
			}
			else
				$html = $child->render();
			return $html;
			/*$fullName = $childName;
			echo $fullName.'<br/>';
			if ($child->getChildrenCount() > 0)
			{
				$child = $child->children[$fullName];
				$name = $fullName;
				echo $name.'<hr/>';
				foreach ($child->children as $subchild)
					$this->getChildHtml($subchild->getName(), $subchild);
			}
			else
				return $this->children[$child->getName().'.'.$childName]->render();*/
		}

		public function getChildrenCount($childName = '')
		{
			if ($childName != '')
			{
				var_dump($this);
				return count($this->children[$childName]->getChildren());
			}
			return count($this->children);
		}

		public function getChild($name)
		{
			return $this->children[$name];
		}

		// getters, setters

		public function getChildren()
		{
			return $this->children;
		}

		public function setChildren($value)
		{
			$this->children = $value;
		}

		public function setName($value)
		{
			$this->name = $value;
		}

		public function getName()
		{
			return $this->name;
		}

		public function setTemplate($value)
		{
			$this->template = $value;
			$this->loadTemplate($this->template);
		}

		public function getTemplate()
		{
			return $this->template;
		}

		public function getCode()
		{
			return $this->code;
		}

		public function setCode($value)
		{
			$this->code = $value;
		}
	}
}
