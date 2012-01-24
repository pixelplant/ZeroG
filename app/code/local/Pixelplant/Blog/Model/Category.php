<?php

namespace App\Code\Local\Pixelplant\Blog\Model
{
	class Category extends \Sys\Database\Model
	{
		protected $_eventPrefix = 'blog_category';

		protected function _construct()
		{
			parent::_construct();
			$this->_init('blog/category', 'cat_id');
		}
	}
}