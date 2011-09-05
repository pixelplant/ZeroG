<?php

namespace App\Code\Local\Pixelplant\Blog\Models\Resource
{
	class Post extends \Sys\Model\Resource
	{
		public function __construct()
		{
			parent::__construct('post');
		}
	}
}