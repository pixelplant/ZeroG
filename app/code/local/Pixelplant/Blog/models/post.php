<?php

namespace App\Code\Local\Pixelplant\Blog\Models
{
	class Post extends \Sys\Database\Model
	{
		public function __construct()
		{
			//parent::__construct('resource/post');
			parent::__construct('blog_record');
			//$this->data['name'] = new \Sys\Model\Resource\Field\Varchar('name');
			//$this->data['name']->setValue('JOONAH');
		}
	}
}