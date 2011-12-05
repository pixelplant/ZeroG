<?php

namespace App\Code\Core\ZeroG\Page\Block
{

	class Home extends \Sys\Layout\Block
	{
		public function getBlogCollection()
		{
			return \Z::getModel('blog/post')->getCollection()->load();
		}
	}
}