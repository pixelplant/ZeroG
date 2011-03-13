<?php

namespace App\Code\Local\Pixelplant\Blog\Controllers
{
	use \Sys\Template\Controller;

	class Index extends Controller
	{
		public function indexAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}
	}
}
