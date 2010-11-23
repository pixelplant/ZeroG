<?php

namespace Ext\Blog\Controllers
{
	class BlogController extends \Sys\TemplateController
	{
		public function __construct()
		{
			parent::__construct('blog/list');
		}

		public function listView()
		{
			$posts = \Sys\ZeroG::getModel('ext/blog/record')->getCollection();
			$this->setTemplate('posts', $posts);
		}
	}
}
 
