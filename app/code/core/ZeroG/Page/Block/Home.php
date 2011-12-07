<?php

namespace App\Code\Core\ZeroG\Page\Block
{

	class Home extends \Sys\Layout\Block
	{
		public function getBlogCollection()
		{
			$blog = \Z::getModel('blog/post')->load(2);
			return \Z::getModel('blog/post')->getCollection()->load();
		}
	}
}