<?php

namespace App\Code\Local\Pixelplant\Blog\Model\Resource
{
	class Category extends \Sys\Database\Resource
	{
		protected function _construct()
		{
			$this->_init('blog/category', 'cat_id');
		}
	}
}