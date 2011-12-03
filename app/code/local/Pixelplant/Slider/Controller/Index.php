<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of index
 *
 * @author radu.mogos
 */

namespace App\Code\Local\Pixelplant\Slider\Controller
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
