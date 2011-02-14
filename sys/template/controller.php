<?php

namespace Sys\Template
{
	/**
	 * The template controller class includes support for a main website template...
	 */
	class Controller extends \Sys\Controller
	{
		/**
		 * @var <Sys\Layout> the main website layout
		 */
		protected $layout;

		/**
		 * The difference between a base controller and a template controller
		 * is the fact that a TemplateController includes a link to a main layout
		 * allowing direct access to the main view.
		 *
		 * @param <string> $layoutFile Name of the main layout fle
		 */
		public function __construct($layoutFile = NULL)
		{
			parent::__construct();
			if ($layoutFile === NULL)
				$layoutFile = 'page';
			$this->layout = new \Sys\Layout($layoutFile);
		}

		/**
		 * Destroys the main view associated with our controller. Think of a main
		 * view as a main template for a page, which contains all the other bits
		 * of information a page might wantt (usually a list of other views to include)
		 */
		public function __destruct()
		{
			if (!$this->isXHR())
				echo $this->layout->render();
		}

		/**
		 * Returns a reference to the layout instance
		 * @return <Sys\Layout>
		 */
		public function getLayout()
		{
			return $this->layout;
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