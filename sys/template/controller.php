<?php

namespace Sys\Template
{
	/**
	 * The template controller class includes support for a main website template...
	 */
	class Controller extends \Sys\Controller
	{
		/**
		 * @var <Sys\View> the main website template
		 */
		protected $template;

		/**
		 * The difference between a base controller and a template controller
		 * is the fact that a TemplateController includes a link to a main view
		 * allowing direct access to the main view.
		 *
		 * @param <string> $templateFile Name of the main view
		 */
		public function __construct($templateFile = NULL)
		{
			parent::__construct();
			if ($templateFile === NULL)
				$templateFile = 'main';
			$this->template = new \Sys\View($templateFile);
		}

		/**
		 * Destroys the main view associated with our controller. Think of a main
		 * view as a main template for a page, which contains all the other bits
		 * of information a page might wantt (usually a list of other views to include)
		 */
		public function __destruct()
		{
			if (!$this->isXHR())
				echo $this->template->render();
		}

		/**
		 * Sets a variable defined in our main template. $index becomes $value
		 *
		 * @param <string> $index The name of our view variable to be replaced
		 * @param <mixed> $value The value of our index variable
		 */
		public function setTemplate($index, $value)
		{
			$this->template->set($index, $value);
		}

		/**
		 * Checks to see if we receive a normal request or an AJAX one.
		 * Most modern JS libraries (including jQuery) set a specific header to
		 * let applications know when they send an AJAX request.
		 *
		 * @return <bool> True or False - Wether the request is an AJAX one or not
		 */
		public function isXHR()
		{
			return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
		}
	}
}