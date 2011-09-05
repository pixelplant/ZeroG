<?php

namespace App\Code\Local\Pixelplant\Blog\Models
{
	class Post extends \Sys\Model
	{
		public function __construct()
		{
			//parent::__construct('resource/post');
			$this->data['name'] = new \Sys\Model\Resource\Field\Varchar('name');
			$this->data['name']->setValue('JOONAH');
		}
	}
}