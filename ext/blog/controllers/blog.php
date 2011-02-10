<?php

namespace Ext\Blog\Controllers
{
	class Blog extends \Sys\Template\Controller
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
 
