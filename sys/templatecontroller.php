<?php
/**
 * The template controller class includes support for a main website template...
 */

namespace Sys
{
	class TemplateController extends Controller
	{
		/**
		 * @var View the main website template
		 */
		protected $template;

		public function __construct($templateFile = NULL)
		{
			parent::__construct();
			if ($templateFile === NULL)
				$templateFile = 'main';
			$this->template = new View($templateFile);
		}

		public function __destruct()
		{
			if (!$this->isXHR())
				echo $this->template->render();
		}

		public function setTemplate($index, $value)
		{
			$this->template->set($index, $value);
		}

		public function isXHR()
		{
			return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
		}
	}
}