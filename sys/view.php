<?php
/**
	* The base view class for zerog
	* User: radu.mogos
	* Date: 23.10.2010
	* Time: 22:21:56
*/

namespace Sys
{
	class View
	{
		/**
		 * @var <array> stores all the data passed to the view from the controller
		 */
		protected $__data;

		/**
		 * @var <string> the location + name of the template file
		 */
		protected $templateFile;

		/**
		 * @var <string> stores the code from the template file, after the file is loaded
		 */
		protected $code;

		/**
		 * The View base class constructor
		 * 
		 * @return <void>
		 */
		public function __construct($template = NULL)
		{
			$this->__data = array();
			if ($template !== NULL)
				$this->loadTemplate($template);
		}

		/**
		 * @param <string> $index the name of the field that will be accesible in the view
		 * @param <mixed> $value the value of the view field
		 * @return <mixed> the current View class instance
		 */
		public function set($index, $value)
		{
			$this->__data[strtolower($index)] = $value;
			return $this;
		}

		/**
		 * @param <string> $index the name of the field we want to retrieve
		 * @return <mixed> returns the value of the $index field
		 */
		public function get($index)
		{
			if (array_key_exists($index, $this->__data))
				return $this->__data[$index];
			return NULL;
		}

		/**
		 * Magic method to return a view variable
		 *
		 * @param <string> $index
		 * @return <mixed>
		 */
		public function __get($index)
		{
			return $this->get($index);
		}

		/**
		 * @param <string> $template the location + name of the template file
		 * @return <Sys\View> reference to the current instance
		 */
		public function loadTemplate($template = NULL)
		{
			if ($template !== NULL)
			{
				$this->templateFile = $this->getPath().$template.'.php';
			}
			else
				throw new \Sys\Exception('View file: Please specify the view template filename!');
			$filesize = filesize($this->templateFile);
			$file = fopen($this->templateFile, 'r');
			$this->code = fread($file, $filesize);
			fclose($file);
			return $this;
		}

		/**
		 * Get the relative view path
		 *
		 * @return <string> the relative path to the app/views directory
		 */
		protected function getPath()
		{
			return \App\Config\System::APP_DIR.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR;
		}

		/**
		 * @return <string> the processed template code + the view data set in the $__data array
		 */
		public function render()
		{
			ob_start();
			eval("?>".$this->code);
			$renderedCode = ob_get_contents();
			ob_end_clean();
			return $renderedCode;
		}
	}
}
