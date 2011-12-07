<?php

namespace App\Code\Local\Pixelplant\Blog\Model
{
	class Post extends \Sys\Database\Model
	{
		protected $_eventPrefix = 'blog_post';

		protected function _construct()
		{
			parent::_construct();
			$this->_init('blog/post', 'post_id');
			//$this->data['name'] = new \Sys\Model\Resource\Field\Varchar('name');
			//$this->data['name']->setValue('JOONAH');
		}
	}
}