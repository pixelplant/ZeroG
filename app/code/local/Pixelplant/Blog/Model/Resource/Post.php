<?php

namespace App\Code\Local\Pixelplant\Blog\Model\Resource
{
	class Post extends \Sys\Database\Resource
	{
		protected function _construct()
		{
			$this->_init('blog/post', 'post_id');
		}
	}
}