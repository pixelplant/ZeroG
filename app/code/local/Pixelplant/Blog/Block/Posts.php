<?php
/**
 * Shows a list of blog posts
 *
 * @author radu.mogos
 */
namespace App\Code\Local\Pixelplant\Blog\Block
{

	class Posts extends \Sys\Layout\Block {

		public function getAllPosts()
		{
			return array(1 => 'Post 1', 2 => 'Post XXL', 3 => 'Super duper');
		}
	}
}
