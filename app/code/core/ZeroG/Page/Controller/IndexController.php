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

namespace App\Code\Core\ZeroG\Page\Controller
{
	use \App\Code\Core\ZeroG\Core\Controller\Front as Controller;
	
	class IndexController extends Controller
	{
		public function indexAction()
		{
			$this->loadLayout();
			$this->renderLayout();
		}

		public function testmeAction()
		{
			if ($this->isXHR())
			{
				$posts = \Z::getModel('blog/post')->getCollection()->load();
				$html = '';
				foreach ($posts as $post)
				{
					$html .= '<li id="" class="recommendation dismissable">
																	<a class="sui-list-dismiss" title="Dismiss this recommendation" href="">Delete</a>
											<h4 class="title">
												<a href="">'.$post->getTitle().'</a>
											</h4>
											<div class="message">
												'.$post->getPostContent().'
											</div></li>';
				}
				$html = '								<h5 class="header">
									Ultimele notificări
									<a href="javascript:void(0)" class="close">close</a>
								</h5>								<div class="content-filterbar"></div>
								<div id="" class="content">

									<ul class="sui-status-list">'.$html.'</ul></div>';
				echo $html;
			}
		}
	}
}


