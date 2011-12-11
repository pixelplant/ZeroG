<?php

namespace App\Code\Local\Pixelplant\Blog\Model
{

	/**
	 * Description of Api
	 *
	 * @author radu.mogos
	 */
	class Api
	{
		public function get($postId = 1)
		{
			$post = \Z::getModel('blog/post')->load($postId);

			return $post->getData();
		}
	}
}
